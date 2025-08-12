<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlashSale;
use App\Models\Product_variants;
use Carbon\Carbon;

class ManageFlashSales extends Command
{
    protected $signature = 'app:manage-flashsales';
    protected $description = 'Kích hoạt và kết thúc Flash Sale theo lịch và kho hàng';

    public function handle(): void
    {
        $now = Carbon::now();

        // 1) Kích hoạt flash sale nếu đã đến giờ
        FlashSale::where('status', 'upcoming')
            ->where('start_date', '<=', $now)
            ->update(['status' => 'active']);

        // 2) Kết thúc nếu hết hạn
        FlashSale::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->get()
            ->each(function ($flashSale) {
                $flashSale->status = 'ended';
                $flashSale->save();

                // Trả sản phẩm còn dư về kho
                $flashSale->items->each(function ($item) {
                    Product_variants::where('id', $item->product_variant_id)
                        ->increment('stock', $item->max_quantity);
                });
                $flashSale->items()->update(['max_quantity' => 0]);
            });

        // 3) Lấy danh sách variant CÒN HÀNG trong FS active
        $activeVariantIds = FlashSale::where('status', 'active')
            ->with('items')
            ->get()
            ->flatMap(function ($fs) {
                return $fs->items
                    ->where('max_quantity', '>', 0) // chỉ lấy còn hàng
                    ->pluck('product_variant_id');
            })
            ->unique()
            ->values()
            ->toArray();

        // 4) Lấy danh sách variant HẾT HÀNG trong FS active
        $outOfStockVariantIds = FlashSale::where('status', 'active')
            ->with('items')
            ->get()
            ->flatMap(function ($fs) {
                return $fs->items
                    ->where('max_quantity', '<=', 0) // chỉ lấy hết hàng
                    ->pluck('product_variant_id');
            })
            ->unique()
            ->values()
            ->toArray();

        // 5) Ẩn variant còn hàng
        if (!empty($activeVariantIds)) {
            Product_variants::whereIn('id', $activeVariantIds)
                ->where('is_show', 1)
                ->update(['is_show' => 0]);
        }

        // 6) Bật lại variant hết hàng
        if (!empty($outOfStockVariantIds)) {
            Product_variants::whereIn('id', $outOfStockVariantIds)
                ->where('is_show', 0)
                ->update(['is_show' => 1]);
        }

        // 7) Nếu không còn flash sale active → bật tất cả variant về 1
        if (empty($activeVariantIds) && empty($outOfStockVariantIds)) {
            Product_variants::where('is_show', 0)->update(['is_show' => 1]);
        }

        \Log::info('Flash Sale checked at ' . now(), [
            'hidden_variants' => $activeVariantIds,
            'shown_variants' => $outOfStockVariantIds
        ]);
    }
}

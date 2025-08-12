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

        // Kích hoạt flash sale nếu đã đến giờ
        FlashSale::where('status', 'upcoming')
            ->where('start_date', '<=', $now)
            ->update(['status' => 'active']);

        // Kết thúc nếu hết hạn
        FlashSale::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->get()
            ->each(function ($flashSale) {
                $flashSale->status = 'ended';
                $flashSale->save();

                // Trả sản phẩm còn dư về kho
                $flashSale->items->each(function ($item) {
                    \App\Models\Product_variants::where('id', $item->product_variant_id)
                        ->increment('stock', $item->max_quantity);
                });
                $flashSale->items()->update(['max_quantity' => 0]);
            });

        // Kết thúc nếu hết hàng
        FlashSale::where('status', 'active')->get()->each(function ($flashSale) {
            $allOut = $flashSale->items->every(function ($item) {
                return $item->max_quantity <= 0;
            });

            if ($allOut) {
                $flashSale->update(['status' => 'ended']);
            }
        });
// 4) Lấy danh sách variant đang nằm trong bất kỳ FlashSale nào active
        $activeVariantIds = FlashSale::where('status', 'active')
            ->with('items')
            ->get()
            ->flatMap(function ($fs) {
                return $fs->items->pluck('product_variant_id');
            })
            ->unique()
            ->values()
            ->toArray();

        // 5) Ẩn những variant đang được bán trong flash sale (nếu chưa ẩn)
        if (!empty($activeVariantIds)) {
            Product_variants::whereIn('id', $activeVariantIds)
                ->where('is_show', 1)
                ->update(['is_show' => 0]);
        }

        // 6) Bật lại những variant hiện bị ẩn nhưng không còn nằm trong active flash sale nào
        if (!empty($activeVariantIds)) {
            Product_variants::where('is_show', 0)
                ->whereNotIn('id', $activeVariantIds)
                ->update(['is_show' => 1]);
        } else {
            // Nếu không còn variant nào active, bật hết những variant đang bị ẩn
            Product_variants::where('is_show', 0)
                ->update(['is_show' => 1]);
        }
        \Log::info('Flash Sale checked at ' . now());
    }
}

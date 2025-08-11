<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlashSale;
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

        \Log::info('Flash Sale checked at ' . now());
    }
}

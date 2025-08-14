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
         $this->checkDeletedProducts();
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
                    // tăng số lượng bán được vào product variant
                    Product_variants::where('id', $item->product_variant_id)
                        ->increment('sold_quantity', $item->sold_quantity);
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
    /**
     * Kiểm tra sản phẩm trong flash sale bị soft/hard delete
     */
    private function checkDeletedProducts()
    {
        $flashSales = FlashSale::whereIn('status', ['active', 'upcoming'])
            ->with([
                'items',
                'items.productVariant' => function ($query) {
                    $query->withTrashed(); // để lấy cả soft delete
                }
            ])
            ->get();

        foreach ($flashSales as $flashSale) {
            $allItemsDeleted = true;

            foreach ($flashSale->items as $item) {
                $variant = $item->productVariant;

                if (!$variant || $variant->trashed()) {
                    // Nếu variant còn trong DB (soft delete) → trả kho + cộng sold_quantity
                    if ($variant) {
                        Product_variants::withTrashed()
                            ->where('id', $item->product_variant_id)
                            ->increment('stock', $item->max_quantity);

                        if ($item->sold_quantity > 0) {
                            Product_variants::withTrashed()
                                ->where('id', $item->product_variant_id)
                                ->increment('sold_quantity', $item->sold_quantity);
                        }
                    }

                    // Xóa khỏi flash sale items
                    $item->delete();

                    \Log::warning('FlashSale item removed due to deleted variant', [
                        'flash_sale_id' => $flashSale->id,
                        'variant_id'    => $item->product_variant_id,
                        'deleted_type'  => $variant ? 'soft_delete' : 'hard_delete'
                    ]);
                } else {
                    $allItemsDeleted = false;
                }
            }

            // Nếu toàn bộ sản phẩm trong flash sale bị xóa → kết thúc flash sale
            if ($allItemsDeleted) {
                $flashSale->status = 'ended';
                $flashSale->save();

                \Log::info('FlashSale ended because all items deleted', [
                    'flash_sale_id' => $flashSale->id
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use App\Models\Product_variants;
use Carbon\Carbon;

class FlashSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo flash sale active (đang diễn ra)
        $activeFlashSale = FlashSale::create([
            'start_date' => Carbon::now()->subHours(2),
            'end_date' => Carbon::now()->addHours(2),
            'status' => 'active',
            'discount' => 20
        ]);

        // Tạo flash sale upcoming (sắp diễn ra)
        $upcomingFlashSale = FlashSale::create([
            'start_date' => Carbon::now()->addHours(3),
            'end_date' => Carbon::now()->addHours(5),
            'status' => 'upcoming',
            'discount' => 15
        ]);

        // Lấy một số sản phẩm để thêm vào flash sale
        $variants = Product_variants::where('is_show', 1)
            ->where('stock', '>', 0)
            ->take(5)
            ->get();

        foreach ($variants as $index => $variant) {
            // Thêm vào flash sale active
            FlashSaleItems::create([
                'product_variant_id' => $variant->id,
                'flash_sale_id' => $activeFlashSale->id,
                'name' => $variant->name,
                'variant_image_url' => $variant->variant_image_url,
                'max_quantity' => 10,
                'sold_quantity' => rand(0, 5),
                'price_at_flash_sale' => $variant->sale_price * 0.8, // Giảm 20%
                'product_id' => $variant->product_id,
                'color_id' => $variant->color_id,
                'size_id' => $variant->size_id,
                'import_price' => $variant->import_price,
                'listed_price' => $variant->listed_price,
                'sale_price' => $variant->sale_price
            ]);

            // Thêm vào flash sale upcoming
            if ($index < 3) {
                FlashSaleItems::create([
                    'product_variant_id' => $variant->id,
                    'flash_sale_id' => $upcomingFlashSale->id,
                    'name' => $variant->name,
                    'variant_image_url' => $variant->variant_image_url,
                    'max_quantity' => 8,
                    'sold_quantity' => 0,
                    'price_at_flash_sale' => $variant->sale_price * 0.85, // Giảm 15%
                    'product_id' => $variant->product_id,
                    'color_id' => $variant->color_id,
                    'size_id' => $variant->size_id,
                    'import_price' => $variant->import_price,
                    'listed_price' => $variant->listed_price,
                    'sale_price' => $variant->sale_price
                ]);
            }
        }
    }
} 
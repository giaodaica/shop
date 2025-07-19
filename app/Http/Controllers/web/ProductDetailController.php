<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Product_variants;
use App\Models\ImageProductVariants;
use App\Models\Review;

class ProductDetailController extends Controller
{
    public function index($slug)
    {
        $product = Products::with(['category', 'variants.color', 'variants.size'])
            ->where('slug', $slug)
            ->firstOrFail();
        $product->increment('views_page');

        // dd($product);
        // Lấy tất cả biến thể của sản phẩm
        $variants = Product_variants::with(['color', 'size'])
            ->where('product_id', '=', $product->id)
            ->where('is_show', 1)->get();
        // dd($variants);
        $colors = $variants->pluck('color')->unique('id'); // Lấy tất cả màu không trùng
        $sizes = $variants->pluck('size')->unique('id'); // Lấy tất cả màu không trùng

        // Tạo map màu với ảnh tương ứng
        $colorImageMap = [];
        foreach ($variants as $variant) {
            if ($variant->color_id && $variant->variant_image_url) {
                // Biến thành mảng nếu có nhiều ảnh, ví dụ ngăn cách bởi dấu phẩy
                $images = explode(',', $variant->variant_image_url);
                $colorImageMap[$variant->color_id] = array_map(function ($img) {
                    return asset(trim($img));
                }, $images);
            }
        }
        // Debug để kiểm tra colorImageMap
        // dd($colorImageMap);

        // Debug chi tiết hơn
        // dd([
        //     'variants_count' => $variants->count(),
        //     'colorImageMap' => $colorImageMap,
        //     'variants_data' => $variants->map(function($v) {
        //         return [
        //             'id' => $v->id,
        //             'color_id' => $v->color_id,
        //             'variant_image_url' => $v->variant_image_url
        //         ];
        //     })->toArray()
        // ]);

        // Lấy ảnh của từng biến thể
        $images = Product_variants::where('product_id', $product->id)
            ->where('is_show', 1)
            ->get()
            ->unique('color_id')
            ->pluck('variant_image_url');

        $reviews = Review::where('product_id', $product->id)
            ->where('is_show', 1)
            ->with('user') // Eager load the user information
            ->latest()
            ->get();

        // Giả sử biến $product là sản phẩm hiện tại
        $relatedProducts = Products::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest() // hoặc ->inRandomOrder() nếu muốn ngẫu nhiên
            ->take(4)
            ->get();

      
        return view('pages.shop.show', compact('product', 'variants', 'reviews', 'colors', 'sizes', 'images', 'colorImageMap','relatedProducts'));
    }
}

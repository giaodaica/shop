<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Product_variants;
use App\Models\ImageProductVariants;
use App\Models\Review;

class ProductDetailController extends Controller
{
    public function index($slug, Request $request)
    {
        // Lấy giá trị `flash_item_id` từ query string trên URL (vd: ?flash_item_id=3)
        $flashItemId = request()->query('flash_item_id');

        // Khởi tạo biến mặc định
        $flashSaleItem = null;  // Biến lưu thông tin chi tiết sản phẩm trong flash sale (nếu có)
        $isFlashSale = false;   // Biến kiểm tra sản phẩm có thuộc flash sale đang hoạt động hay không

        // Nếu có truyền `flash_item_id` từ URL
        if ($flashItemId) {
            // Truy vấn thông tin flash sale item kèm theo các quan hệ: color, size, flashSale
            $flashSaleItem = FlashSaleItems::with(['color', 'size', 'flashSale'])
                // Đảm bảo chỉ lấy những flash sale đang hoạt động
                ->whereHas('flashSale', function ($q) {
                    $q->where('status', 'active')                      // Trạng thái active
                        ->whereDate('start_date', '<=', now())           // Đã bắt đầu
                        ->whereDate('end_date', '>=', now());            // Chưa kết thúc
                })
                ->where('id', $flashItemId)                            // So sánh đúng theo ID item
                ->first();                                             // Lấy bản ghi đầu tiên (nếu có)

            // Nếu có bản ghi flash sale hợp lệ, đánh dấu là sản phẩm flash sale thật
            $isFlashSale = $flashSaleItem !== null;
        }

        //  dd($flashSaleItem);


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
        if ($variants->isEmpty()) {
            return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại hoặc đã bị xóa.');
        }
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


        // Lấy ảnh của từng biến thể
        $images = Product_variants::where('product_id', $product->id)
            ->where('is_show', 1)
            ->get()
            ->unique('color_id')
            ->pluck('variant_image_url');
        // $disabled = Product_variants::where()
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


        return view('pages.shop.show', compact(
            'product',
            'variants',
            'reviews',
            'colors',
            'sizes',
            'images',
            'colorImageMap',
            'relatedProducts',
            'isFlashSale',
            'flashSaleItem',
            'flashItemId'
        ));
    }
}

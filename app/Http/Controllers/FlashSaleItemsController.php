<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use App\Models\Product_variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashSaleItemsController extends Controller
{
    public function create($flash_sale_id)
    {
        $products = Product_variants::where('is_show', 1)
            ->where('use_flash_sale', 1)
            ->join('colors', 'product_variants.color_id', 'colors.id')
            ->join('sizes', 'product_variants.size_id', 'sizes.id')
            ->select(
                'product_variants.*',
                'colors.color_name as color_name',
                'sizes.size_name as size_name',
                'product_variants.id as variant_id'
            )
            ->get();

        // dd($products);
        // dd($flash_sale_id);
        return view('dashboard.pages.flashsale.create_items', compact('products', 'flash_sale_id'));
    }
    public function add_flash_sale_items(Request $request, $id)
    {
        // dd($_POST);
        $flash_sale = $request->input('flash_sale');
        $checkAll = $request->input('checkall');
        $flashsale = FlashSale::where('id',$id)->where('status','upcoming')->first();

        if(!$flashsale){
            return abort(403,'Bạn chỉ được thao tác khi chương trình giảm giá này ở trạng thái đang chờ');
        }
        foreach ($flash_sale as $product_id => $data) {
            if ($checkAll == 1 || isset($data['selected']) || !isset($checkAll)) {
                $quantity = $data['quantity'];
                $data_product_variant = Product_variants::findOrFail($product_id);
                if($quantity <= 0){
                    $errors[] = "Số lượng cho sản phẩm {$data_product_variant->name} không hợp lệ";
                    continue;
                }
                if ($data_product_variant->stock <= $quantity) {
                    $errors[] = "Sản phẩm {$data_product_variant->name} không đủ tồn kho";
                    continue;
                }
                $data_flash_sale_item = FlashSaleItems::where('product_variant_id', $product_id)
                    ->where('flash_sale_id', $id)->first();
                if ($data_flash_sale_item) {
                    // dd($data_product_variant);
                    $data_flash_sale_item->update(
                        ['max_quantity' => $data_flash_sale_item->max_quantity + $quantity]
                    );
                    $data_product_variant->update([
                        'stock' => $data_product_variant->stock - $quantity
                    ]);
                    // return redirect()->back()->with('success','Thành công');
                } else {
                    FlashSaleItems::create([
                        'product_variant_id' => $product_id,
                        'flash_sale_id' => $id,
                        'name' => $data_product_variant->name,
                        'variant_image_url' => $data_product_variant->variant_image_url,
                        'max_quantity' => $quantity,
                        'sold_quantity' => 0,
                        'price_at_flash_sale' => $data_product_variant->sale_price * ((100 - $flashsale->discount) / 100),
                        'product_id' => $data_product_variant->product_id,
                        'color_id' => $data_product_variant->color_id,
                        'size_id' => $data_product_variant->size_id,
                        'import_price' => $data_product_variant->import_price,
                        'listed_price' => $data_product_variant->listed_price,
                        'sale_price' => $data_product_variant->sale_price
                    ]);
                     $data_product_variant->update([
                        'stock' => $data_product_variant->stock - $quantity
                    ]);
                }
            }
        }
        if (!empty($errors)) {
            // dd($errors);
            return redirect()->back()->with('error', implode('<br>', $errors));
        }
           return redirect()->back()->with('success', 'Thành công.');
    }
    public function remove_flash_sale_items($id){
        $data_item = FlashSaleItems::where('product_variant_id',$id)->first();
        if(!$data_item){
            return redirect()->back()->with('error','Không tìm thấy sản phẩm này');
        }
        $data_item->delete();
            return redirect()->back()->with('success','Thành công');
        // dd($data_item);
    }
}

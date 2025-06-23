<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\Categories;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active'); // active | trashed | all

        if ($status == 'trashed') {
            $products = Products::onlyTrashed()->with('category')->paginate(10);
        } elseif ($status == 'all') {
            $products = Products::withTrashed()->with('category')->paginate(10);
        } else {
            $products = Products::with('category')->paginate(10);
        }

        $totalActive = Products::count();
        $totalTrashed = Products::onlyTrashed()->count();
        $totalAll = $totalActive + $totalTrashed;

        return view('dashboard.pages.product.index', compact('products', 'status', 'totalActive', 'totalTrashed', 'totalAll'));
    }

    public function create()
    {
        $categories = Categories::all();
        $colors = \App\Models\Color::all();
        $sizes = \App\Models\Size::all(); // Truy vấn thêm size

        return view('dashboard.pages.product.create', compact('categories', 'colors', 'sizes'));
    }

     public function uploadTempImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/temp'), $filename);

            return response()->json(['url' => asset('uploads/temp/' . $filename)]);
        }

        return response()->json(['url' => '']);
    }

    public function uploadTempVariantImage(Request $request)
    {
        if ($request->hasFile('variant_image')) {
            $file = $request->file('variant_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/temp'), $filename);

            return response()->json(['url' => asset('uploads/temp/' . $filename)]);
        }

        return response()->json(['url' => '']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'slug' => 'nullable|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'temp_image_url' => 'required|string',

            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.import_price' => 'required|numeric|min:0',
            'variants.*.listed_price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0|lte:variants.*.listed_price',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.temp_variant_image_url' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
            'slug.unique' => 'Slug đã tồn tại.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'temp_image_url.required' => 'Vui lòng chọn ảnh sản phẩm.',

            'variants.required' => 'Vui lòng thêm ít nhất một biến thể.',
            'variants.*.size_id.required' => 'Vui lòng chọn size.',
            'variants.*.color_id.required' => 'Vui lòng chọn màu.',

            'variants.*.import_price.required' => 'Giá nhập không được để trống.',
            'variants.*.import_price.numeric' => 'Giá nhập phải là số.',
            'variants.*.import_price.min' => 'Giá nhập không được nhỏ hơn 0.',

            'variants.*.listed_price.required' => 'Giá niêm yết không được để trống.',
            'variants.*.listed_price.numeric' => 'Giá niêm yết phải là số.',
            'variants.*.listed_price.min' => 'Giá niêm yết không được nhỏ hơn 0.',

            'variants.*.sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'variants.*.sale_price.min' => 'Giá khuyến mãi không được nhỏ hơn 0.',
            'variants.*.sale_price.lte' => 'Giá khuyến mãi phải nhỏ hơn hoặc bằng giá niêm yết.',

            'variants.*.stock.required' => 'Số lượng kho không được để trống.',
            'variants.*.stock.integer' => 'Số lượng kho phải là số nguyên.',
            'variants.*.stock.min' => 'Số lượng kho không được nhỏ hơn 0.',

            'variants.*.temp_variant_image_url.required' => 'Vui lòng chọn ảnh cho biến thể.',
        ]);

        $combinations = [];
        $errors = [];

        foreach ($request->variants as $index => $variant) {
            $key = $variant['color_id'] . '-' . $variant['size_id'];
            if (in_array($key, $combinations)) {
                $errors["variants.$index.size_id"] = ['Size này đã bị trùng cho cùng một màu.'];
                $errors["variants.$index.color_id"] = ['Màu này đã bị trùng cho cùng một size.'];
            }
            $combinations[] = $key;
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        try {
            DB::beginTransaction();

            $imagePath = null;
            if ($request->temp_image_url) {
                $tempPath = public_path(parse_url($request->temp_image_url, PHP_URL_PATH));
                if (file_exists($tempPath)) {
                    $filename = time() . '_' . basename($tempPath);
                    rename($tempPath, public_path('uploads/products/' . $filename));
                    $imagePath = 'uploads/products/' . $filename;
                }
            }
            $slug = Str::slug($request->slug ?: $request->name, '-');
            $product = Products::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $slug,
                'image_url' => $imagePath,
                'category_id' => $request->category_id,
            ]);

            foreach ($request->variants as $index => $variantData) {
                $variantImagePath = null;

                if ($variantData['temp_variant_image_url']) {
                    $tempVariantPath = public_path(parse_url($variantData['temp_variant_image_url'], PHP_URL_PATH));
                    if (file_exists($tempVariantPath)) {
                        $variantFileName = time() . '_' . basename($tempVariantPath);
                        rename($tempVariantPath, public_path('uploads/product_variants/' . $variantFileName));
                        $variantImagePath = 'uploads/product_variants/' . $variantFileName;
                    }
                }

                $color = \App\Models\Color::find($variantData['color_id']);
                $size = \App\Models\Size::find($variantData['size_id']);

                $variantName = $product->name . ' - ' . ($color ? $color->color_name : '') . ' - ' . ($size ? $size->size_name : '');

                $product->variants()->create([
                    'color_id' => $variantData['color_id'],
                    'size_id' => $variantData['size_id'],
                    'name' => $variantName,
                    'variant_image_url' => $variantImagePath,
                    'import_price' => $variantData['import_price'],
                    'listed_price' => $variantData['listed_price'],
                    'sale_price' => $variantData['sale_price'],
                    'stock' => $variantData['stock'],
                 
                ]);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Thêm sản phẩm và biến thể thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }




    public function restore($id)
    {
        $product = Products::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')->with('success', 'Khôi phục sản phẩm thành công!');
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:products,name,' . $id,
            'slug' => 'required|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'category_id.required' => 'Bạn chưa chọn danh mục',
            'category_id.exists' => 'Danh mục không hợp lệ',
            'image_url.image' => 'File tải lên phải là ảnh',
            'image_url.mimes' => 'Ảnh phải có định dạng: jpg, webp, jpeg hoặc png',
            'image_url.max' => 'Kích thước ảnh tối đa là 2MB',
        ]);

        // Lấy dữ liệu để update
        $data = $request->only(['name', 'slug', 'description', 'category_id']);

        // Nếu có ảnh mới
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $data['image_url'] = 'uploads/products/' . $filename;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }


    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();
        return view('dashboard.pages.product.edit', compact('product', 'categories'));
    }



    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show($id)
    {
        $product = Products::with(relations: 'category')->findOrFail($id);
        return view('dashboard.pages.product.show', compact('product'));
    }
}

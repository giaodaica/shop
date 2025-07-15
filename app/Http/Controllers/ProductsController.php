<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Validation\ValidationException;
use App\Models\Categories;
use App\Models\Color;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active'); // active | trashed | all
        $search = $request->get('keyword');
        $categoryId = $request->get('category_id');

        $query = Products::query()->with('category');

        if ($status === 'trashed') {
            $query = Products::onlyTrashed()->with('category');
        } elseif ($status === 'all') {
            $query = Products::withTrashed()->with('category');
        }

        // Lọc theo tên hoặc slug
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('slug', 'like', "%$search%");
            });
        }

        // Lọc theo danh mục
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(10)->appends(request()->query());

        $totalActive = Products::count();
        $totalTrashed = Products::onlyTrashed()->count();
        $totalAll = $totalActive + $totalTrashed;

        // Lấy danh sách danh mục để render form lọc
        $categories = Categories::all();

        return view('dashboard.pages.product.index', compact(
            'products',
            'status',
            'totalActive',
            'totalTrashed',
            'totalAll',
            'categories'
        ));
    }

    public function create()
    {
        $categories = Categories::all();
        $colors = Color::all();
        $sizes = Size::all(); // Truy vấn thêm size

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
        // dd($request);
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

            'variants.*.sale_price.numeric' => 'Giá bán phải là số.',
            'variants.*.sale_price.min' => 'Giá bán không được nhỏ hơn 0.',
            'variants.*.sale_price.lte' => 'Giá bán phải nhỏ hơn hoặc bằng giá niêm yết.',

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

                $color = Color::find($variantData['color_id']);
                $size = Size::find($variantData['size_id']);

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
        // dd($request);
        $product = Products::with('variants')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'slug' => 'nullable|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'temp_image_url' => $request->filled('temp_image_url') ? 'required|string' : 'nullable|string',

            'variants' => 'required|array|min:1',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.import_price' => 'required|numeric|min:0',
            'variants.*.listed_price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0|lte:variants.*.listed_price',
            'variants.*.stock' => 'required|integer|min:0',


            'variants.*.temp_variant_image_url' => function ($attribute, $value, $fail) use ($request) {
                preg_match('/variants\.(\d+)\.temp_variant_image_url/', $attribute, $matches);
                $index = $matches[1] ?? null;
                if ($index !== null && empty($request->variants[$index]['id']) && empty($value)) {
                    $fail('Vui lòng chọn ảnh cho biến thể mới.');
                }
            },
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

            'variants.*.sale_price.numeric' => 'Giá bán phải là số.',
            'variants.*.sale_price.min' => 'Giá bán không được nhỏ hơn 0.',
            'variants.*.sale_price.lte' => 'Giá bán phải nhỏ hơn hoặc bằng giá niêm yết.',

            'variants.*.stock.required' => 'Số lượng kho không được để trống.',
            'variants.*.stock.integer' => 'Số lượng kho phải là số nguyên.',
            'variants.*.stock.min' => 'Số lượng kho không được nhỏ hơn 0.',
        ]);


        // Kiểm tra trùng biến thể
        $combinations = [];
        $errors = [];

        foreach ($request->variants as $index => $variant) {
            $key = $variant['color_id'] . '-' . $variant['size_id'];

            if (array_key_exists($key, $combinations)) {
                $firstIndex = $combinations[$key]['index'];
                $firstId = $combinations[$key]['id'] ?? null;
                $currentId = $variant['id'] ?? null;

                // Nếu là biến thể mới hoặc khác ID cũ thì tính là trùng
                if ($currentId !== $firstId) {
                    // ⚠ Gán lỗi cho dòng đầu tiên xuất hiện
                    $errors["variants.$firstIndex.size_id"] = ['Trùng với biến thể ở dòng ' . ($index + 1)];
                    $errors["variants.$firstIndex.color_id"] = ['Trùng với biến thể ở dòng ' . ($index + 1)];
                }
            } else {
                $combinations[$key] = [
                    'index' => $index,
                    'id' => $variant['id'] ?? null
                ];
            }
        }

        if (!empty($errors)) {
            throw \Illuminate\Validation\ValidationException::withMessages($errors);
        }


        try {
            DB::beginTransaction();

            $imagePath = $product->image_url;
            if ($request->filled('temp_image_url')) {
                $tempPath = public_path(parse_url($request->temp_image_url, PHP_URL_PATH));
                if (file_exists($tempPath)) {
                    $filename = time() . '_' . basename($tempPath);
                    $newPath = 'uploads/products/' . $filename;
                    rename($tempPath, public_path($newPath));
                    $imagePath = $newPath;
                }
            }

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'description' => $request->description,
                'category_id' => $request->category_id,
                'image_url' => $imagePath,
            ]);

            $existingIds = [];

            foreach ($request->variants as $variantData) {
                $variantImagePath = $variantData['variant_image_url'] ?? null;
                if (!empty($variantData['temp_variant_image_url'])) {
                    $tempPath = public_path(parse_url($variantData['temp_variant_image_url'], PHP_URL_PATH));
                    if (file_exists($tempPath)) {
                        $filename = time() . '_' . basename($tempPath);
                        $newPath = 'uploads/product_variants/' . $filename;
                        rename($tempPath, public_path($newPath));
                        $variantImagePath = $newPath;
                    }
                }

                $color = Color::find($variantData['color_id']);
                $size = Size::find($variantData['size_id']);
                $variantName = $product->name . ' - ' . ($color->color_name ?? '') . ' - ' . ($size->size_name ?? '');

                if (isset($variantData['id'])) {
                    $variant = $product->variants()->find($variantData['id']);
                    if ($variant) {
                        $variant->update([
                            'color_id' => $variantData['color_id'],
                            'size_id' => $variantData['size_id'],
                            'name' => $variantName,
                            'import_price' => $variantData['import_price'],
                            'listed_price' => $variantData['listed_price'],
                            'sale_price' => $variantData['sale_price'],
                            'stock' => $variantData['stock'],
                            'variant_image_url' => $variantImagePath,
                        ]);
                        $existingIds[] = $variant->id;
                    }
                } else {
                    $newVariant = $product->variants()->create([
                        'color_id' => $variantData['color_id'],
                        'size_id' => $variantData['size_id'],
                        'name' => $variantName,
                        'import_price' => $variantData['import_price'],
                        'listed_price' => $variantData['listed_price'],
                        'sale_price' => $variantData['sale_price'],
                        'stock' => $variantData['stock'],
                        'variant_image_url' => $variantImagePath,
                    ]);
                    $existingIds[] = $newVariant->id;
                }
            }

            $product->variants()->whereNotIn('id', $existingIds)->delete();

            DB::commit();

            return redirect()->route('products.edit', $product->id)->with('success', 'Cập nhật sản phẩm và biến thể thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('dashboard.pages.product.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    public function renderVariantPartial(Request $request)
    {
        $index = $request->get('index');
        $colors = Color::all();
        $sizes = Size::all();

        return view('dashboard.pages.product.partials.variant', [
            'index' => $index,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }



    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show($id)
    {
        $product = Products::with(['category', 'variants.color', 'variants.size'])->findOrFail($id);
        return view('dashboard.pages.product.show', compact('product'));
    }
}

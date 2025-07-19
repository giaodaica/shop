<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSuggestionResource;
use App\Models\Categories;
use App\Models\Color;
use App\Models\Products;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\ProductFilterTrait;

class SearchController extends Controller
{
    use ProductFilterTrait;

    public function index(Request $request)
    {
        try {
            $query = $request->get('q');

            if (!$query) {
                if ($request->ajax()) {
                    return response()->json([
                        'error' => 'Query parameter is required'
                    ], 400);
                }
                return redirect()->route('home.shop');
            }

            $products = Products::with(['category', 'variants.color', 'variants.size'])
                ->whereHas('category', function ($query) {
                    $query->where('status', '1')
                          ->whereNull('categories.deleted_at');
                })
                ->whereHas('variants', function ($query) {
                    $query->where('is_show', 1)
                          ->where('stock', '>', 0)
                          ->whereNull('product_variants.deleted_at');
                })
                ->where('name', 'LIKE', "%{$query}%")
                ->whereNull('products.deleted_at')
                ->paginate(5);

            // Lấy dữ liệu cho sidebar
            $categories = Categories::withCount('products')->get();
            $colors = Color::withCount('productVariants')->get();
            $sizes = Size::withCount('productVariants')->get();

            if ($request->ajax()) {
                $view = view('pages.shop.search-results', [
                    'products' => $products,
                    'categories' => $categories,
                    'colors' => $colors,
                    'sizes' => $sizes,
                    'searchQuery' => $query
                ])->render();

                return response()->json([
                    'html' => $view,
                    'query' => $query,
                    'total' => $products->total(),
                    'success' => true
                ]);
            }

            return view('pages.shop.shop', [
                'products' => $products,
                'categories' => $categories,
                'colors' => $colors,
                'sizes' => $sizes,
                'searchQuery' => $query
            ]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'An error occurred while searching',
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Có lỗi xảy ra khi tìm kiếm');
        }
    }


    public function suggestions(Request $request)
    {
        try {
            $query = $request->get('q');
    
            if (!$query) {
                return response()->json([
                    'suggestions' => [],
                    'featured_products' => []
                ]);
            }
    
            // Get keyword suggestions (5 distinct product names)
            $suggestions = Products::where('name', 'LIKE', "%{$query}%")
                ->whereNull('deleted_at')
                ->select('name')
                ->distinct()
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return ['name' => $item->name];
                });
    
            // Get featured products (3 active products)
            $featuredProducts = Products::join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->where('products.name', 'LIKE', "%{$query}%")
                ->where('product_variants.is_show', 1)
                ->where('product_variants.stock', '>', 0)
                ->whereNull('product_variants.deleted_at')
                ->whereNull('products.deleted_at')
                ->select(
                    'products.id',
                    'products.name',
                    'products.image_url as image',
                    'product_variants.sale_price as price',
                    'product_variants.listed_price as old_price'
                )
                ->limit(3)
                ->get();
                $featuredProducts = ProductSuggestionResource::collection($featuredProducts);
    
            return response()->json([
                'suggestions' => $suggestions,
                'featured_products' => $featuredProducts
            ]);
        } catch (\Throwable $e) {
            Log::error('Suggestion error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter a search term'
                ]);
            }
            return redirect()->route('shop');
        }

        // Get selected filters
        $selectedCategories = $request->input('categories', []);
        $selectedColors = $request->input('colors', []);
        $selectedSizes = $request->input('sizes', []);

        // Get filtered data using trait
        $filteredData = $this->getFilteredData($query, $selectedCategories, $selectedColors, $selectedSizes);

        // Get products with search query
        $products = Products::with(['category', 'variants.color', 'variants.size'])
            ->where(function($q) use ($query) {
                $q->where('products.name', 'like', "%{$query}%");
                  
            })
            ->whereHas('category', function ($q) {
                $q->where('status', '1')
                  ->whereNull('categories.deleted_at');
            })
            ->whereHas('variants', function ($q) {
                $q->where('is_show', 1)
                  ->where('stock', '>', 0)
                  ->whereNull('product_variants.deleted_at');
            })
            ->whereNull('products.deleted_at');

        // Apply filters only if they are selected
        if (!empty($selectedCategories)) {
            $products->whereIn('category_id', $selectedCategories);
        }

        if (!empty($selectedColors)) {
            $products->whereHas('variants', function ($q) use ($selectedColors) {
                $q->whereIn('color_id', $selectedColors);
            });
        }

        if (!empty($selectedSizes)) {
            $products->whereHas('variants', function ($q) use ($selectedSizes) {
                $q->whereIn('size_id', $selectedSizes);
            });
        }

        // Filter by price range
        $priceRange = $request->input('price_range');
        if (!empty($priceRange) && str_contains($priceRange, '-')) {
            [$min, $max] = explode('-', $priceRange);
            $products->whereHas('variants', function ($q) use ($min, $max) {
                $q->whereBetween('sale_price', [(int)$min, (int)$max]);
            });
        }

        // Handle sorting
        $sort = $request->input('sort');
        if (in_array($sort, ['price_asc', 'price_desc'])) {
            $products->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                    ->where('product_variants.is_show', 1)
                    ->where('product_variants.stock', '>', 0)
                    ->whereNull('product_variants.deleted_at')
                    ->whereNull('products.deleted_at')
                    ->select('products.*', 'products.id as product_id', 'product_variants.sale_price')
                    ->distinct();
        }

        switch ($sort) {
            case 'price_asc':
                $products->orderBy('product_variants.sale_price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('product_variants.sale_price', 'desc');
                break;
            case 'newest':
            default:
                $products->orderBy('products.created_at', 'desc');
                break;
        }

        $products = $products->paginate(12);

        if ($request->ajax()) {
            $view = view('pages.shop.search-results', array_merge(
                ['products' => $products],
                $filteredData
            ))->render();

            return response()->json([
                'success' => true,
                'html' => $view,
                'query' => $query,
                'total' => $products->total()
            ]);
        }

        return view('pages.shop.search-results', array_merge(
            ['products' => $products],
            $filteredData
        ));
    }

    public function trendingCategories()
    {
        // Lấy 5 danh mục có tổng views_page sản phẩm cao nhất
        $categories = \App\Models\Categories::select('categories.id', 'categories.name', 'categories.image')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->whereNull('categories.deleted_at')
            ->whereNull('products.deleted_at')
            ->groupBy('categories.id', 'categories.name', 'categories.image')
            ->selectRaw('SUM(products.views_page) as total_views')
            ->orderByDesc('total_views')
            ->take(5)
            ->get();

        return response()->json([
            'data' => $categories
        ]);
    }
}

<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
        ], [
            'content.required' => 'Nội dung đánh giá không được để trống!',
            'content.min' => 'Nội dung đánh giá phải tối thiểu :min ký tự!',
        ]);
        // dd($request);
        Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'content' => $request->content,
            'is_show' => 1, // chờ admin duyệt
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'alert' => 'alert-success',
                'message' => 'Đánh giá của bạn đã được gửi'
            ]);
        }

        return back()->with('success', 'Đánh giá của bạn đã được gửi');
    }

    public function list($product_id)
    {
        $product = Products::findOrFail($product_id);
        $reviews = $product->reviews()->with('user')->where('is_show', 1)->latest()->get();
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;
        $ratingCounts = $reviews->groupBy('rating')->map->count();
        $ratingPercentages = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $ratingCounts->get($i, 0);
            $ratingPercentages[$i] = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
        }
        return view('pages.shop.partials.reviews', compact('reviews', 'totalReviews', 'averageRating', 'ratingPercentages'))->render();
    }
}

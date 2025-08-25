<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Review;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Bình luận')->only(['index']);
        $this->middleware('permission:Sửa bình luận')->only(['update']);
    }

public function index(Request $request)
{
    $query = Review::with(['user', 'product', 'productVariant']);


    // --- Bộ lọc ---
    if ($request->filled('status')) {
        $query->where('is_show', $request->status);
    }

    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    if ($request->filled('replied')) {
        $request->replied === 'yes'
            ? $query->whereNotNull('admin_reply')
            : $query->whereNull('admin_reply');
    }

    $comments = $query->latest()->paginate(10)->appends($request->all());

    // --- Thống kê nhanh ---
    $stats = [
        'total'      => Review::count(),
        'visible'    => Review::where('is_show', 1)->count(),
        'hidden'     => Review::where('is_show', 0)->count(),
        'unreplied'  => Review::whereNull('admin_reply')->count(),
    ];
if ($request->filled('keyword')) {
    $keyword = $request->keyword;
    $query->where(function ($q) use ($keyword) {
        $q->where('content', 'LIKE', "%$keyword%")
          ->orWhereHas('user', fn($uq) => $uq->where('name', 'LIKE', "%$keyword%"));
    });
}

    // --- Danh sách sản phẩm cho filter ---
    $products = Products::pluck('name', 'id');

    return view('dashboard.pages.comment.index', compact(
        'comments',
        'products',
        'stats'
    ));
}

public function update(Request $request, $id)
{
    $comment = Review::findOrFail($id);

    $data = [];

    if ($request->has('is_show')) {
        $data['is_show'] = $request->input('is_show');
    }

    if ($request->has('admin_reply')) {
        $data['admin_reply'] = $request->input('admin_reply');
        $data['admin_id'] = auth()->id(); // Lưu id của admin đang đăng nhập
        // dd($data);
    }
    

    $comment->update($data);

    return back()->with('success', 'Cập nhật bình luận thành công!');
}




}


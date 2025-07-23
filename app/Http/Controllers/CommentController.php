<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Review::with('user', 'product')->latest()->paginate(10);
        return view('dashboard.pages.comment.index', compact('comments'));
    }
public function update(Request $request, $id)
{
    $comment = Review::findOrFail($id);

    // Chỉ update những field nào có gửi lên
    $data = [];

    if ($request->has('is_show')) {
        $data['is_show'] = $request->input('is_show');
    }

    if ($request->has('admin_reply')) {
        $data['admin_reply'] = $request->input('admin_reply');
    }

    $comment->update($data);

    return back()->with('success', 'Cập nhật bình luận thành công!');
}



}


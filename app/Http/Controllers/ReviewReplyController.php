<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewReplyController extends Controller
{
    /**
     * Lưu phản hồi mới cho một review
     */
    public function store(Request $request, $reviewId)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ], [
            'reply.required' => 'Vui lòng nhập nội dung phản hồi.',
        ]);

        $review = Review::findOrFail($reviewId);

        ReviewReply::create([
            'review_id' => $review->id,
            'reply' => $request->reply,
        ]);

        return back()->with('success', 'Đã gửi phản hồi cho bình luận.');
    }

    /**
     * Xóa phản hồi (nếu cần)
     */
    public function destroy($id)
    {
        $reply = ReviewReply::findOrFail($id);
        $reply->delete();

        return back()->with('success', 'Đã xóa phản hồi.');
    }
}

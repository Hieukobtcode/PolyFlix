<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\Phim;
use App\Models\DatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Lưu bình luận mới
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để bình luận.'
            ], 401);
        }

        $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'content' => 'required|string|max:1000|min:10',
        ]);

        $user = Auth::user();
        $phimId = $request->phim_id;

        // Kiểm tra user có đã đặt vé phim này chưa (tùy chọn)
        $hasBookedTicket = DatVe::where('nguoi_dung_id', $user->id)
            ->where('phim_id', $phimId)
            ->where('trang_thai', 'da_thanh_toan')
            ->exists();

        if (!$hasBookedTicket) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đặt vé xem phim này trước khi có thể bình luận.'
            ], 403);
        }

        // Kiểm tra user đã bình luận phim này chưa
        $existingComment = Comment::where('user_id', $user->id)
            ->where('phim_id', $phimId)
            ->first();

        if ($existingComment) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã bình luận về phim này rồi.'
            ], 400);
        }

        try {
            $comment = Comment::create([
                'user_id' => $user->id,
                'phim_id' => $phimId,
                'content' => $request->content,
                'visible' => true
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Bình luận của bạn đã được gửi thành công!',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('logo/user.jpg'),
                    'created_at' => $comment->created_at->format('d/m/Y H:i'),
                    'reply' => $comment->reply
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi bình luận. Vui lòng thử lại.'
            ], 500);
        }
    }

    /**
     * Lưu đánh giá (rating)
     */
    public function rate(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để đánh giá.'
            ], 401);
        }

        $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $phimId = $request->phim_id;

        // Kiểm tra user có đã đặt vé phim này chưa
        $hasBookedTicket = DatVe::where('nguoi_dung_id', $user->id)
            ->where('phim_id', $phimId)
            ->where('trang_thai', 'da_thanh_toan')
            ->exists();

        if (!$hasBookedTicket) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đặt vé xem phim này trước khi có thể đánh giá.'
            ], 403);
        }

        try {
            // Sử dụng updateOrCreate để tránh duplicate
            $rating = Rating::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'phim_id' => $phimId
                ],
                [
                    'rating' => $request->rating
                ]
            );

            // Tính toán lại rating trung bình
            $averageRating = Rating::where('phim_id', $phimId)->avg('rating');
            $ratingCount = Rating::where('phim_id', $phimId)->count();

            return response()->json([
                'success' => true,
                'message' => $rating->wasRecentlyCreated ? 'Đánh giá của bạn đã được gửi!' : 'Đánh giá của bạn đã được cập nhật!',
                'rating' => $request->rating,
                'average_rating' => round($averageRating, 1),
                'rating_count' => $ratingCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.'
            ], 500);
        }
    }

    /**
     * Lấy bình luận và đánh giá của một phim
     */
    public function getComments($phimId)
    {
        $phim = Phim::findOrFail($phimId);

        $comments = Comment::with('user')
            ->where('phim_id', $phimId)
            ->where('visible', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $ratings = Rating::where('phim_id', $phimId)->get();
        $averageRating = $ratings->avg('rating');
        $ratingCount = $ratings->count();

        // Phân bố rating
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratings->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $ratingCount > 0 ? round(($count / $ratingCount) * 100, 1) : 0
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'comments' => $comments->map(function ($comment) use ($ratings) {
                    $userRating = $ratings->firstWhere('user_id', $comment->user_id);
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user_name' => $comment->user->name,
                        'user_avatar' => $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('logo/user.jpg'),
                        'user_rating' => $userRating ? $userRating->rating : null,
                        'created_at' => $comment->created_at->format('d/m/Y H:i'),
                        'reply' => $comment->reply
                    ];
                }),
                'average_rating' => round($averageRating, 1),
                'rating_count' => $ratingCount,
                'rating_distribution' => $ratingDistribution
            ]
        ]);
    }
}

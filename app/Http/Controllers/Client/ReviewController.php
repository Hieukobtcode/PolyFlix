<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function storeReview(Request $request, Phim $phim)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập.');
        }

        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:2000',
            'dat_ve_id' => 'required|exists:dat_ves,id',
        ]);

        $userId = Auth::id();
        $datVeId = $data['dat_ve_id'];
        $phimId = $phim->id;

        // Xác thực vé hợp lệ
        $okStates = ['Đã thanh toán', 'Đã xuất vé'];
        $datVe = DatVe::where('id', $datVeId)
            ->where('user_id', $userId)
            ->whereIn('trang_thai', $okStates)
            ->whereHas('suatChieu', fn($q) => $q->where('phim_id', $phimId))
            ->first();

        if (!$datVe) {
            return back()->with('error', 'Bạn cần thanh toán thành công vé này để đánh giá/bình luận.');
        }

        try {
            // Sử dụng transaction để đảm bảo tính nhất quán
            DB::beginTransaction();

            // Kiểm tra và cập nhật đánh giá nếu đã tồn tại
            $existingRating = Rating::where('user_id', $userId)
                ->where('phim_id', $phimId)
                ->first();

            if ($existingRating) {
                // Cập nhật đánh giá hiện có
                $existingRating->update([
                    'dat_ve_id' => $datVeId,
                    'rating' => $data['rating'],
                ]);
            } else {
                // Tạo đánh giá mới
                Rating::create([
                    'user_id'   => $userId,
                    'phim_id'   => $phimId,
                    'dat_ve_id' => $datVeId,
                    'rating'    => $data['rating'],
                ]);
            }

            // Kiểm tra và cập nhật bình luận nếu đã tồn tại
            $existingComment = Comment::where('user_id', $userId)
                ->where('phim_id', $phimId)
                ->first();

            if ($existingComment) {
                // Cập nhật bình luận hiện có
                $existingComment->update([
                    'dat_ve_id' => $datVeId,
                    'content' => $data['content'],
                ]);
            } else {
                // Tạo bình luận mới
                Comment::create([
                    'user_id'   => $userId,
                    'phim_id'   => $phimId,
                    'dat_ve_id' => $datVeId,
                    'content'   => $data['content'],
                    'visible'   => 1,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Cảm ơn bạn đã đánh giá và bình luận!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi lưu đánh giá: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Phim;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\RapPhim;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $chiNhanhId = $request->input('chi_nhanh_id');
        $rapPhimId = $request->input('rap_phim_id');
        $view = $request->input('view', 'movies'); // 'movies' hoặc 'comments'

        if ($view === 'comments') {
            // Hiển thị tất cả comments
            $query = Comment::with(['user', 'phim']);

            // Lọc theo trạng thái
            if ($request->filled('status')) {
                if ($request->status === 'visible') {
                    $query->where('visible', true);
                } elseif ($request->status === 'hidden') {
                    $query->where('visible', false);
                } elseif ($request->status === 'replied') {
                    $query->whereNotNull('reply');
                } elseif ($request->status === 'unreplied') {
                    $query->whereNull('reply');
                }
            }

            // Lọc theo rạp/chi nhánh
            if ($rapPhimId) {
                $query->whereHas('phim.rapPhims', function ($q) use ($rapPhimId) {
                    $q->where('rap_phims.id', $rapPhimId);
                });
            } elseif ($chiNhanhId) {
                $query->whereHas('phim.rapPhims', function ($q) use ($chiNhanhId) {
                    $q->where('chi_nhanh_id', $chiNhanhId);
                });
            }

            $comments = $query->latest()->paginate(15);
            $chiNhanhs = ChiNhanh::with('rapPhims')->get();

            // Thống kê
            $totalComments = Comment::count();
            $visibleComments = Comment::where('visible', true)->count();
            $hiddenComments = Comment::where('visible', false)->count();
            $repliedComments = Comment::whereNotNull('reply')->count();

            return view('admin.comments.all', compact(
                'comments',
                'chiNhanhs',
                'chiNhanhId',
                'rapPhimId',
                'totalComments',
                'visibleComments',
                'hiddenComments',
                'repliedComments'
            ));
        }

        // View mặc định - hiển thị theo phim
        $query = Phim::whereHas('comments')
            ->orWhereHas('ratings');

        // Nếu lọc theo rạp phim
        if ($rapPhimId) {
            $query->whereHas('rapPhims', function ($q) use ($rapPhimId) {
                $q->where('rap_phims.id', $rapPhimId);
            });
        }
        // Nếu chỉ lọc theo chi nhánh
        elseif ($chiNhanhId) {
            $query->whereHas('rapPhims', function ($q) use ($chiNhanhId) {
                $q->where('chi_nhanh_id', $chiNhanhId);
            });
        }

        $phims = $query->with(['comments', 'ratings'])->distinct()->get();

        $chiNhanhs = ChiNhanh::with('rapPhims')->get();

        // Thống kê tổng quan
        $totalComments = Comment::count();
        $visibleComments = Comment::where('visible', true)->count();
        $hiddenComments = Comment::where('visible', false)->count();
        $repliedComments = Comment::whereNotNull('reply')->count();
        $totalRatings = Rating::count();
        $averageRating = Rating::avg('rating');

        return view('admin.comments.index', compact(
            'phims',
            'chiNhanhs',
            'chiNhanhId',
            'rapPhimId',
            'totalComments',
            'visibleComments',
            'hiddenComments',
            'repliedComments',
            'totalRatings',
            'averageRating'
        ));
    }
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->reply = $request->reply;
        $comment->save();

        return back()->with('success', 'Đã gửi phản hồi.');
    }


    public function hide($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->visible = false;
        $comment->save();

        return back()->with('success', 'Đã ẩn bình luận.');
    }

    public function show($phimId, Request $request)
    {
        $phim = Phim::findOrFail($phimId);

        $query = Comment::with('user')->where('phim_id', $phimId);

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            if ($request->status === 'visible') {
                $query->where('visible', true);
            } elseif ($request->status === 'hidden') {
                $query->where('visible', false);
            } elseif ($request->status === 'replied') {
                $query->whereNotNull('reply');
            } elseif ($request->status === 'unreplied') {
                $query->whereNull('reply');
            }
        }

        $comments = $query->latest()->paginate(10);

        $averageRating = Rating::where('phim_id', $phimId)->avg('rating');
        $ratingCount = Rating::where('phim_id', $phimId)->count();

        // Lấy rating của từng user để hiển thị
        $ratings = Rating::where('phim_id', $phimId)->get()->keyBy('user_id');

        // Thống kê comments
        $totalComments = Comment::where('phim_id', $phimId)->count();
        $visibleComments = Comment::where('phim_id', $phimId)->where('visible', true)->count();
        $hiddenComments = Comment::where('phim_id', $phimId)->where('visible', false)->count();
        $repliedComments = Comment::where('phim_id', $phimId)->whereNotNull('reply')->count();

        return view('admin.comments.show', compact(
            'phim',
            'comments',
            'averageRating',
            'ratingCount',
            'ratings',
            'totalComments',
            'visibleComments',
            'hiddenComments',
            'repliedComments'
        ));
    }
    public function unhide($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->visible = true;
        $comment->save();

        return back()->with('success', 'Đã hiện lại bình luận.');
    }
}

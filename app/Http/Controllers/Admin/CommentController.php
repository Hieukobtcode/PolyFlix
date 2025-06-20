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

    $query = Phim::query();

    // Nếu lọc theo rạp phim
    if ($rapPhimId) {
        $query->whereHas('rapPhims', function ($q) use ($rapPhimId) {
            $q->where('rap_phim_id', $rapPhimId);
        });
    }
    // Nếu chỉ lọc theo chi nhánh
    elseif ($chiNhanhId) {
        $query->whereHas('rapPhims', function ($q) use ($chiNhanhId) {
            $q->whereHas('chiNhanh', function ($q2) use ($chiNhanhId) {
                $q2->where('id', $chiNhanhId);
            });
        });
    }

    $phims = $query->distinct()->get();

    $chiNhanhs = ChiNhanh::with('rapPhims')->get();

    return view('admin.comments.index', compact('phims', 'chiNhanhs', 'chiNhanhId', 'rapPhimId'));
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
   
public function show($phimId)
{
    $phim = Phim::findOrFail($phimId);

    $comments = Comment::with('user.ratings')
        ->where('phim_id', $phimId)
        // ->where('visible', true)
        ->latest()
        ->get();

    $averageRating = Rating::where('phim_id', $phimId)->avg('rating');
    $ratingCount = Rating::where('phim_id', $phimId)->count();

    return view('admin.comments.show', compact('phim', 'comments', 'averageRating', 'ratingCount'));
}
public function unhide($id)
{
    $comment = Comment::findOrFail($id);
    $comment->visible = true;
    $comment->save();

    return back()->with('success', 'Đã hiện lại bình luận.');
}



}

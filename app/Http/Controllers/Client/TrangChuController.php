<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\Comment;
use App\Models\Phim;
use App\Models\Rating;
use Illuminate\Http\Request;

class TrangChuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phims = Phim::whereHas('comments')
            ->with(['comments.user'])
            ->latest()
            ->take(3)
            ->get();

        $phims->each(function ($phim) {
            $latestComments = $phim->comments->sortByDesc('created_at')->take(3);
            $phim->setRelation('comments', $latestComments);
        });

        $ratings = Rating::all();
        $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();


        return view('client.trang-chu', compact('phims', 'ratings', 'baiViet'));
    }
}

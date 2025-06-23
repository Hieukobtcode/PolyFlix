<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Phim;
use App\Models\Rating;
use Illuminate\Http\Request;

class TrangChuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

    $ratings = Rating::all();

    $baiViet = BaiViet::where('status', '!=', 'draft')
        ->orderBy('ngay_tao', 'desc')
        ->limit(4)
        ->get();

    $phims = Phim::whereHas('comments')
        ->with(['comments.user'])
        ->latest()
        ->take(3)
        ->get();

    $phims->each(function ($phim) {
        $latestComments = $phim->comments->sortByDesc('created_at')->take(3);
        $phim->setRelation('comments', $latestComments);
    });

    // Xác định tab đang chọn
    $tab = $request->get('tab', 'dang-chieu');

    if ($tab === 'sap-chieu') {
        $allPhims = Phim::where('ngay_phat_hanh', '>', now())
            ->orderBy('ngay_phat_hanh', 'asc')
            ->take(8)
            ->get();
    } else {
        // Mặc định là "đang chiếu"
        $allPhims = Phim::where('ngay_phat_hanh', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->orderBy('ngay_phat_hanh', 'desc')
            ->take(8)
            ->get();
    }

    return view('client.trang-chu', compact('phims', 'ratings', 'baiViet', 'banners', 'allPhims', 'tab'));
}
public function loadPhimTab(Request $request)
{
    $tab = $request->get('tab', 'dang-chieu');

    if ($tab === 'sap-chieu') {
        $allPhims = Phim::where('ngay_phat_hanh', '>', now())
            ->orderBy('ngay_phat_hanh', 'asc')
            ->take(8)
            ->get();
    } else {
        // Đang chiếu
        $allPhims = Phim::where('ngay_phat_hanh', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->orderBy('ngay_phat_hanh', 'desc')
            ->take(8)
            ->get();
    }

    return response()->json([
        'phims' => $allPhims
    ]);
}

}



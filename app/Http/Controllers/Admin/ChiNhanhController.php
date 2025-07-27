<?php

namespace App\Http\Controllers\Admin;

use App\Models\LoaiGhe;
use App\Models\ChiNhanh;
use Illuminate\Support\Str;
use App\Models\QuanLyInvite;
use Illuminate\Http\Request;
use App\Mail\MoiQuanLyChiNhanh;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChiNhanhController extends Controller
{
    public function index(Request $request)
    {
        $query = ChiNhanh::with('rapPhims');
        $query->orderBy('id', 'desc');

        //  Nếu user là quản lý chi nhánh (vai_tro_id == 2)
        if (Auth::user()->vai_tro_id == 2) {
            $query->where('quan_ly_id', Auth::id());
        }

        //  Filter theo từ khóa
        if ($request->has('keyword') && $request->keyword) {
            $query->where('ten_chi_nhanh', 'like', '%' . $request->keyword . '%');
        }

        //  Filter theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('trang_thai', $request->status);
        }
        //  Phân trang
        $chiNhanhs = $query->paginate(10);

        //  Lấy danh sách lời mời quản lý (chỉ cần cho super admin)
        $pendingInvites = [];
        $pendingEmails = [];
        if (Auth::user()->vai_tro_id == 1) {
            $pendingInvites = DB::table('quan_ly_invites')
                ->where('used', 0)
                ->pluck('chi_nhanh_id')
                ->toArray();

            $pendingEmails = DB::table('quan_ly_invites')
                ->where('used', 0)
                ->pluck('email', 'chi_nhanh_id')
                ->toArray();
        }

        return view('admin.chi-nhanh.index', compact('chiNhanhs', 'pendingInvites', 'pendingEmails'));
    }

    public function create()
    {
        return view('admin.chi-nhanh.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_chi_nhanh' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|in:hoat_dong,tam_dung,dong_cua',
        ]);

        ChiNhanh::create($request->all());

        return redirect()->route('admin.chi-nhanh.index')->with('success', 'Thêm chi nhánh thành công');
    }

    public function edit($id)
    {
        $chiNhanh = ChiNhanh::findOrFail($id);
        return view('admin.chi-nhanh.edit', compact('chiNhanh'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_chi_nhanh' => 'required|string|max:255',
            'dia_chi' => 'required|string',
            'trang_thai' => 'required|in:hoat_dong,tam_dung,dong_cua',
        ]);

        $chiNhanh = ChiNhanh::findOrFail($id);
        $chiNhanh->update($request->all());

        return redirect()->route('admin.chi-nhanh.index')->with('success', 'Cập nhật chi nhánh thành công');
    }


    public function show($id)
    {
        $chiNhanh = ChiNhanh::with(['rapPhims' => function ($query) {
            // Nếu admin rạp, chỉ load các rạp họ quản lý
            if (Auth::user()->vai_tro_id == 3) {
                $query->where('quan_ly_id', Auth::id());
            }
        }])->findOrFail($id);

        // Check quyền: Admin chi nhánh
        if (Auth::user()->vai_tro_id == 2 && $chiNhanh->quan_ly_id != Auth::id()) {
            return redirect()->route('admin.chi-nhanh.index')
                ->with('error', 'Bạn không có quyền truy cập chi nhánh này.');
        }

        // Check quyền: Admin rạp
        if (Auth::user()->vai_tro_id == 3) {
            $hasRap = $chiNhanh->rapPhims->contains('quan_ly_id', Auth::id());

            if (!$hasRap) {
                return back()->with('error', 'Bạn không có quyền truy cập chi nhánh này.');

            }
        }

        $pendingRapInvites = DB::table('quan_ly_invites')
            ->where('used', 0)
            ->pluck('rap_phim_id')
            ->toArray();

        $pendingRapEmails = DB::table('quan_ly_invites')
            ->where('used', 0)
            ->pluck('email', 'rap_phim_id')
            ->toArray();

        $pendingInvites = DB::table('quan_ly_invites')
            ->where('used', 0)
            ->pluck('chi_nhanh_id')
            ->toArray();

        return view('admin.chi-nhanh.show', compact(
            'chiNhanh',
            'pendingRapInvites',
            'pendingRapEmails',
            'pendingInvites'
        ));
    }


    public function destroy($id)
    {
        ChiNhanh::findOrFail($id)->delete();
        return redirect()->route('admin.chi-nhanh.index')->with('success', 'Xóa chi nhánh thành công');
    }
    public function invite(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::random(40);
        QuanLyInvite::create([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addHour(),
        ]);

        $link = route('invite.form', ['token' => $token]);
        Mail::to($request->email)->send(new MoiQuanLyChiNhanh($link));

        return back()->with('success', 'Đã gửi lời mời thành công');
    }
}
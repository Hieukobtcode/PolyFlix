<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CapBacThe;
use App\Models\DatVe;
use App\Models\LichSuDiem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        $user = Auth::user();

        $milestones = CapBacThe::orderBy('tong_chi_tieu', 'asc')
            ->pluck('tong_chi_tieu');

        $lichSuDiem = LichSuDiem::where('users_id', $user->id)
            ->orderBy('thoi_gian', 'desc')
            ->paginate(5);

        // Lấy đơn đặt vé đã thanh toán
        $donDatVeDaThanhToan = DatVe::with('suatChieu.phim')
            ->where('user_id', $user->id)
            ->whereIn('trang_thai', ['Đã thanh toán', 'Đã xuất vé'])
            ->orderBy('ngay_thanh_toan', 'desc')
            ->get();

        // Tính tổng chi tiêu
        $tongChiTieu = DatVe::where('user_id', $user->id)
            ->where('trang_thai', 'Đã thanh toán')
            ->sum('tong_tien');

        // Tìm cấp bậc phù hợp
        $capBac = CapBacThe::where('tong_chi_tieu', '<=', $tongChiTieu)
            ->orderByDesc('tong_chi_tieu')
            ->first();

        if ($capBac) {
            if ($user->cap_bac_id != $capBac->id) {
                $user->cap_bac_id = $capBac->id;
                $user->save();

                Log::info("Người dùng #{$user->id} được cập nhật cấp bậc: {$capBac->ten}");
            }

            $tenCapBac = $capBac->ten;
        } else {
            $tenCapBac = 'Chưa có cấp bậc';
        }

        // Tính % tiến độ chi tiêu
        $maxMoc = $milestones->max() ?? 1; // tránh chia 0
        $phanTramChiTieu = min(100, round(($tongChiTieu / $maxMoc) * 100));

        return view("client.profile", compact(
            'milestones',
            'lichSuDiem',
            'user',
            'donDatVeDaThanhToan',
            'tongChiTieu',
            'tenCapBac',
            'phanTramChiTieu'
        ));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $path;
            $user->save();

            return response()->json([
                'avatar_url' => asset('storage/' . $path),
                'message' => 'Cập nhật ảnh đại diện thành công!'
            ]);
        }

        return response()->json(['error' => 'Không có ảnh nào được tải lên.'], 400);
    }

    public function chiTietVe($id)
    {
        try {
            // Kiểm tra quyền truy cập - chỉ chủ vé hoặc admin mới xem được
            $ve = DatVe::with([
                'suatChieu.phim',
                'suatChieu.phongChieu.rapPhim.chiNhanh',
                'suatChieu.phongChieu.loaiPhong',
                'gheNgois.loaiGhe',
                'combos',
                'doAns',
                'nguoiDung',
                'successTransaction',
                'chiTietDatVes'
            ])->findOrFail($id);

            // Kiểm tra quyền truy cập - chỉ admin (vai_tro_id = 1) hoặc chủ vé mới xem được
            if (Auth::id() !== $ve->user_id && Auth::user()->vai_tro_id !== 1) {
                abort(403, 'Bạn không có quyền xem vé này.');
            }

            // Tạo mã vạch
            $maVachHtml = (new DNS1D)->getBarcodeHTML($ve->ma_dat_ve, 'C128', 2, 60);

            return view('client.chi-tiet-ve', compact('ve', 'maVachHtml'));
        } catch (\Exception $e) {
            Log::error('Lỗi lấy chi tiết vé: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể lấy chi tiết vé!');
        }
    }

    public function printVe($id)
    {
        try {
            // Kiểm tra quyền truy cập - chỉ admin (vai_tro_id = 1) hoặc chủ vé mới in được
            $ve = DatVe::with([
                'suatChieu.phim',
                'suatChieu.phongChieu.rapPhim.chiNhanh',
                'suatChieu.phongChieu.loaiPhong',
                'gheNgois.loaiGhe',
                'combos',
                'doAns',
                'nguoiDung',
                'successTransaction',
                'chiTietDatVes'
            ])->findOrFail($id);

            // Kiểm tra quyền truy cập
            if (Auth::id() !== $ve->user_id && Auth::user()->vai_tro_id !== 1) {
                abort(403, 'Bạn không có quyền in vé này.');
            }

            // Tạo mã vạch
            $maVachHtml = (new DNS1D)->getBarcodeHTML($ve->ma_dat_ve, 'C128', 2, 60);

            $pdf = Pdf::loadView('client.print-ve', compact('ve', 'maVachHtml'))
                ->setPaper('a4')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->stream('ve_xem_phim_' . $ve->ma_dat_ve . '.pdf');
        } catch (\Exception $e) {
            Log::error('Lỗi in vé: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể in vé!');
        }
    }
}
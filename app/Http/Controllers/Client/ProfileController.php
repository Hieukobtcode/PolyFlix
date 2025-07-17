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
            ->where('trang_thai', 'Đã thanh toán')
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
            $ve = DatVe::with([
                'suatChieu.phim',
                'suatChieu.phongChieu.rapPhim.chiNhanh',
                'suatChieu.phongChieu.loaiPhong',
                'gheNgois.loaiGhe',
                'combos',
                'doAns'
            ])->findOrFail($id);

            $maVachHtml = (new DNS1D)->getBarcodeHTML($ve->ma_dat_ve, 'C128', 2, 60);

            $data = [
                'ma_dat_ve' => $ve->ma_dat_ve,
                'ten_phim' => optional($ve->suatChieu->phim)->ten_phim,
                'thoi_gian_chieu' => optional($ve->suatChieu)->bat_dau,
                'phong' => optional($ve->suatChieu->phongChieu)->ten_phong,
                'ma_vach_html' => $maVachHtml,
                'rap' => optional(optional($ve->suatChieu->phongChieu)->rapPhim)->ten_rap,
                'chi_nhanh' => optional(optional(optional($ve->suatChieu->phongChieu)->rapPhim)->chiNhanh)->dia_chi,
                'danh_sach_ghe' => $ve->gheNgois->pluck('ma_ghe')->implode(', '),

                'combo' => $ve->combos->map(function ($combo) {
                    return [
                        'ten' => $combo->ten_combo,
                        'gia' => number_format($combo->gia, 0, ',', '.') . 'đ',
                        'so_luong' => $combo->pivot->so_luong
                    ];
                }),

                'do_an' => $ve->doAns->map(function ($doAn) {
                    return [
                        'ten' => $doAn->ten_do_an,
                        'gia' => number_format($doAn->gia, 0, ',', '.') . 'đ',
                        'so_luong' => $doAn->pivot->so_luong
                    ];
                }),

                'tong_tien' => number_format($ve->tong_tien, 0, ',', '.') . ' đ',
                'thanh_toan_luc' => optional($ve->thanh_toan) ? \Carbon\Carbon::parse($ve->thanh_toan)->format('H:i d/m/Y') : null,
            ];
            Log::info($data);
            return response()->json($data);
        } catch (\Exception $e) {
            // Ghi log lỗi để kiểm tra chi tiết
            Log::error('Lỗi lấy chi tiết vé: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Không thể lấy chi tiết vé!',
                'debug' => $e->getMessage(),
            ], 500);
        }
    }
}

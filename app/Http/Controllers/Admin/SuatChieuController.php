<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\ChiNhanh;
use App\Models\RapPhim;
use App\Models\PhongChieu;

class SuatChieuController extends Controller
{
    /**
     * Xem danh sách suất chiếu, lọc theo rạp, chi nhánh, ngày.
     */
    public function index(Request $request)
    {
        $query = SuatChieu::with(['phim', 'chiNhanh', 'rapPhim', 'phongChieu']);

        if ($request->filled('chi_nhanh_id')) {
            $query->where('chi_nhanh_id', $request->chi_nhanh_id);
        }

        if ($request->filled('rap_phim_id')) {
            $query->where('rap_phim_id', $request->rap_phim_id);
        }

        if ($request->filled('ngay_chieu')) {
            $query->whereDate('ngay_chieu', $request->ngay_chieu);
        }

        $suatChieus = $query->orderBy('ngay_chieu', 'desc')->get();

        $chiNhanhs = ChiNhanh::all();
        $rapPhims = RapPhim::all();

        return view('admin.suat-chieu.index', compact('suatChieus', 'chiNhanhs', 'rapPhims'));
    }

    /**
     * Hiển thị form tạo suất chiếu mới.
     */
    public function create(Request $request)
    {
        $phims = Phim::all();
        $chiNhanhs = ChiNhanh::all();
        $rapPhims = RapPhim::all();
        $phongChieus = PhongChieu::all();

        $suatChieusHienTai = [];

        // Nếu có chọn ngày chiếu và phòng chiếu thì lấy các suất hiện có
        if ($request->filled(['ngay_chieu', 'phong_chieu_id'])) {
            $suatChieusHienTai = SuatChieu::with('phongChieu')
                ->where('phong_chieu_id', $request->phong_chieu_id)
                ->whereDate('ngay_chieu', $request->ngay_chieu)
                ->orderBy('bat_dau')
                ->get(['bat_dau', 'ket_thuc', 'phong_chieu_id']);
        }

        return view('admin.suat-chieu.create', [
            'phims' => $phims,
            'chiNhanhs' => $chiNhanhs,
            'rapPhims' => $rapPhims,
            'phongChieus' => $phongChieus,
            'suatChieusHienTai' => $suatChieusHienTai,
            'ngay_chieu' => $request->ngay_chieu,
            'phong_chieu_id' => $request->phong_chieu_id,
        ]);
    }

    /**
     * Lưu suất chiếu mới (tự động hoặc thủ công).
     */
    public function store(Request $request)
    {
        // Validate các trường chung
        $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
            'rap_phim_id' => 'nullable|exists:rap_phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'phien_ban_phim' => 'required|in:long_tieng,phu_de',
            'ngay_chieu' => 'required|date',
            'trang_thai' => 'in:hoat_dong,tam_dung',
        ]);

        // Nếu có generated_slots (nhiều suất)
        if ($request->filled('generated_slots')) {
            $slots = json_decode($request->generated_slots, true);

            foreach ($slots as $slot) {
                SuatChieu::create([
                    'phim_id' => $request->phim_id,
                    'chi_nhanh_id' => $request->chi_nhanh_id,
                    'rap_phim_id' => $request->rap_phim_id,
                    'phong_chieu_id' => $request->phong_chieu_id,
                    'phien_ban_phim' => $request->phien_ban_phim,
                    'ngay_chieu' => $request->ngay_chieu,
                    'bat_dau' => $request->ngay_chieu . ' ' . $slot['bat_dau'],
                    'ket_thuc' => $request->ngay_chieu . ' ' . $slot['ket_thuc'],
                    'trang_thai' => $request->trang_thai ?? 'hoat_dong',
                ]);
            }

            return redirect()->route('admin.suat-chieu.index')
                ->with('success', 'Đã tạo ' . count($slots) . ' suất chiếu tự động.');
        }

        // Nếu là nhập thủ công
        $request->validate([
            'bat_dau' => 'required|date_format:Y-m-d H:i:s',
            'ket_thuc' => 'required|date_format:Y-m-d H:i:s|after:bat_dau',
        ]);

        SuatChieu::create([
            'phim_id' => $request->phim_id,
            'chi_nhanh_id' => $request->chi_nhanh_id,
            'rap_phim_id' => $request->rap_phim_id,
            'phong_chieu_id' => $request->phong_chieu_id,
            'phien_ban_phim' => $request->phien_ban_phim,
            'ngay_chieu' => $request->ngay_chieu,
            'bat_dau' => $request->bat_dau,
            'ket_thuc' => $request->ket_thuc,
            'trang_thai' => $request->trang_thai ?? 'hoat_dong',
        ]);

        return redirect()->route('admin.suat-chieu.index')
            ->with('success', 'Tạo suất chiếu thủ công thành công!');
    }

    /**
     * Xem chi tiết suất chiếu.
     */
    public function show($id)
    {
        $suatChieu = SuatChieu::with(['phim', 'chiNhanh', 'rapPhim', 'phongChieu'])->findOrFail($id);

        return view('admin.suat-chieu.show', compact('suatChieu'));
    }

    /**
     * Hiển thị form chỉnh sửa suất chiếu (nếu chưa tới giờ).
     */
    public function edit($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);

        // Chặn nếu đã tới giờ chiếu
        if (Carbon::now()->greaterThanOrEqualTo($suatChieu->bat_dau)) {
            return redirect()->back()->with('error', 'Không thể chỉnh sửa vì suất chiếu đã bắt đầu.');
        }

        $phims = Phim::all();
        $chiNhanhs = ChiNhanh::all();
        $rapPhims = RapPhim::all();
        $phongChieus = PhongChieu::all();

        return view('admin.suat-chieu.edit', compact('suatChieu', 'phims', 'chiNhanhs', 'rapPhims', 'phongChieus'));
    }

    /**
     * Cập nhật suất chiếu.
     */
    public function update(Request $request, $id)
    {
        $suatChieu = SuatChieu::findOrFail($id);

        $validated = $request->validate([
            'phim_id' => 'required|exists:phims,id',
            'chi_nhanh_id' => 'required|exists:chi_nhanhs,id',
            'rap_phim_id' => 'nullable|exists:rap_phims,id',
            'phong_chieu_id' => 'required|exists:phong_chieus,id',
            'phien_ban_phim' => 'required|in:long_tieng,phu_de',
            'ngay_chieu' => 'required|date',
            'bat_dau' => 'required|date_format:Y-m-d H:i:s',
            'ket_thuc' => 'required|date_format:Y-m-d H:i:s|after:bat_dau',
            'trang_thai' => 'in:hoat_dong,tam_dung',
        ]);

        $suatChieu->update($validated);

        return redirect()->route('admin.suat-chieu.index')->with('success', 'Cập nhật suất chiếu thành công!');
    }

    /**
     * Xoá suất chiếu nếu chưa diễn ra.
     */
    public function destroy($id)
    {
        $suatChieu = SuatChieu::findOrFail($id);

        if (Carbon::now()->greaterThanOrEqualTo($suatChieu->bat_dau)) {
            return redirect()->back()->with('error', 'Không thể xoá vì suất chiếu đã bắt đầu.');
        }

        $suatChieu->delete();

        return redirect()->route('admin.suat-chieu.index')->with('success', 'Xoá suất chiếu thành công!');
    }
}
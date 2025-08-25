<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\RapPhim;
use App\Models\KhuyenMaiRapPhim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChiNhanhRapKhuyenMaiController extends Controller
{
    /**
     * Hiển thị trang quản lý khuyến mãi tổng hợp cho admin chi nhánh
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra user có phải admin chi nhánh và có chi nhánh không
        if ($user->vai_tro_id != 2 || !$user->chiNhanhDangQuanLy) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $chiNhanh = $user->chiNhanhDangQuanLy;

        // Lấy danh sách rạp thuộc chi nhánh
        $rapPhims = $chiNhanh->rapPhims()->where('trang_thai', 'hoat_dong')->get();

        // Debug: Nếu không có rạp, thử lấy tất cả rạp không phân biệt trạng thái
        if ($rapPhims->isEmpty()) {
            $rapPhims = $chiNhanh->rapPhims()->get();
        }

        // Debug: Nếu vẫn không có, tạo dữ liệu mẫu
        if ($rapPhims->isEmpty()) {
            // Tạo dữ liệu mẫu để test
            $rapPhims = collect([
                (object)[
                    'id' => 1,
                    'ten_rap' => 'PolyFlix Bà Triệu',
                    'dia_chi' => 'Quận Hai Bà Trưng, Hà Nội',
                    'chi_nhanh_id' => $chiNhanh->id
                ],
                (object)[
                    'id' => 2,
                    'ten_rap' => 'PolyFlix Long Biên',
                    'dia_chi' => 'Quận Long Biên, Hà Nội',
                    'chi_nhanh_id' => $chiNhanh->id
                ],
                (object)[
                    'id' => 3,
                    'ten_rap' => 'PolyFlix Royal City',
                    'dia_chi' => 'Quận Thanh Xuân, Hà Nội',
                    'chi_nhanh_id' => $chiNhanh->id
                ]
            ]);
        }

        // Khuyến mãi chung (không cần nữa)
        $khuyenMaisChung = collect();

        // Khuyến mãi có thể gán cho rạp (khuyến mãi thuộc chi nhánh này)
        $khuyenMaisCoTheGan = $chiNhanh->khuyenMais()
            ->where('trang_thai', 'hoat_dong')
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->with(['rapPhims' => function ($query) use ($chiNhanh) {
                $query->where('chi_nhanh_id', $chiNhanh->id);
            }])
            ->get();

        // Debug: Nếu không có khuyến mãi, tạo dữ liệu mẫu
        if ($khuyenMaisCoTheGan->isEmpty()) {
            $khuyenMaisCoTheGan = collect([
                (object)[
                    'id' => 1,
                    'ten' => 'test2',
                    'mo_ta' => 'Khuyến mãi test cho rạp',
                    'loai_giam_gia' => 'phan_tram',
                    'gia_tri_giam' => 20,
                    'ap_dung_cho' => 've',
                    'rapPhims' => collect([
                        (object)['id' => 1, 'ten_rap' => 'PolyFlix Bà Triệu'],
                        (object)['id' => 2, 'ten_rap' => 'PolyFlix Long Biên']
                    ])
                ]
            ]);
        }

        return view('admin.chi-nhanh-khuyen-mai.manager', compact(
            'chiNhanh',
            'rapPhims',
            'khuyenMaisChung',
            'khuyenMaisCoTheGan'
        ));
    }

    /**
     * Gán khuyến mãi cho rạp cụ thể
     */
    public function assignToRap(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra quyền admin chi nhánh
        if ($user->vai_tro_id != 2) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.']);
        }

        $validated = $request->validate([
            'khuyen_mai_id' => 'required|exists:khuyen_mais,id',
            'rap_phim_ids' => 'required|array',
            'rap_phim_ids.*' => 'exists:rap_phims,id'
        ]);

        try {
            DB::beginTransaction();

            $khuyenMai = KhuyenMai::findOrFail($validated['khuyen_mai_id']);
            $chiNhanh = $user->chiNhanhDangQuanLy;

            // Kiểm tra khuyến mãi có thuộc chi nhánh không
            if (!$chiNhanh->khuyenMais()->where('khuyen_mais.id', $khuyenMai->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Khuyến mãi này không thuộc chi nhánh bạn quản lý.']);
            }

            // Kiểm tra tất cả rạp có thuộc chi nhánh không
            $rapPhimsInChiNhanh = $chiNhanh->rapPhims()->whereIn('id', $validated['rap_phim_ids'])->pluck('id');
            if ($rapPhimsInChiNhanh->count() !== count($validated['rap_phim_ids'])) {
                return response()->json(['success' => false, 'message' => 'Một số rạp không thuộc chi nhánh bạn quản lý.']);
            }

            // Xóa các liên kết cũ của khuyến mãi với rạp thuộc chi nhánh này
            $khuyenMai->rapPhims()->wherePivotIn('rap_phim_id', $chiNhanh->rapPhims()->pluck('id'))->detach();

            // Tạo các liên kết mới
            $khuyenMai->rapPhims()->attach($validated['rap_phim_ids']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gán khuyến mãi cho rạp thành công!',
                'data' => [
                    'khuyen_mai' => $khuyenMai->ten,
                    'so_rap' => count($validated['rap_phim_ids'])
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Hủy gán khuyến mãi khỏi rạp
     */
    public function removeFromRap(Request $request)
    {
        $user = Auth::user();

        if ($user->vai_tro_id != 2) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này.']);
        }

        try {
            $validated = $request->validate([
                'khuyen_mai_id' => 'required|exists:khuyen_mais,id',
                'rap_phim_id' => 'required|exists:rap_phims,id'
            ]);

            $khuyenMai = KhuyenMai::findOrFail($validated['khuyen_mai_id']);
            $rapPhim = RapPhim::findOrFail($validated['rap_phim_id']);
            $chiNhanh = $user->chiNhanhDangQuanLy;

            // Kiểm tra rạp có thuộc chi nhánh không
            if ($rapPhim->chi_nhanh_id !== $chiNhanh->id) {
                return response()->json(['success' => false, 'message' => 'Rạp này không thuộc chi nhánh bạn quản lý.']);
            }

            // Kiểm tra xem liên kết có tồn tại không
            $exists = $khuyenMai->rapPhims()->where('rap_phim_id', $validated['rap_phim_id'])->exists();

            if (!$exists) {
                return response()->json(['success' => false, 'message' => 'Khuyến mãi chưa được gán cho rạp này.']);
            }

            // Hủy liên kết
            $detached = $khuyenMai->rapPhims()->detach($validated['rap_phim_id']);

            return response()->json(['success' => true, 'message' => 'Đã hủy gán khuyến mãi khỏi rạp.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ: ' . implode(', ', $e->errors())]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Lấy danh sách rạp đã được gán khuyến mãi
     */
    public function getAssignedRaps($khuyenMaiId)
    {
        $user = Auth::user();

        if ($user->vai_tro_id != 2) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập.']);
        }

        $chiNhanh = $user->chiNhanhDangQuanLy;
        if (!$chiNhanh) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa được gán quản lý chi nhánh nào.']);
        }

        $khuyenMai = KhuyenMai::findOrFail($khuyenMaiId);

        // Lấy rạp đã được gán khuyến mãi trong chi nhánh này
        $assignedRaps = $khuyenMai->rapPhims()
            ->where('chi_nhanh_id', $chiNhanh->id)
            ->get(['rap_phims.id', 'rap_phims.ten_rap']);

        return response()->json([
            'success' => true,
            'assignedCinemas' => $assignedRaps->pluck('id')->toArray(),
            'data' => $assignedRaps
        ]);
    }
}
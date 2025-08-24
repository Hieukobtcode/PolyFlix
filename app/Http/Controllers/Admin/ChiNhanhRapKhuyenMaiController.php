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
     * Hiển thị danh sách khuyến mãi có thể gán cho rạp
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra quyền admin chi nhánh
        if ($user->vai_tro_id != 2) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Lấy chi nhánh mà admin này quản lý
        $chiNhanh = $user->chiNhanhDangQuanLy;
        if (!$chiNhanh) {
            return redirect()->back()->with('error', 'Bạn chưa được gán quản lý chi nhánh nào.');
        }

        // Lấy tất cả rạp thuộc chi nhánh
        $rapPhims = $chiNhanh->rapPhims()->get();

        // Lấy các khuyến mãi áp dụng cho chi nhánh này
        $khuyenMais = $chiNhanh->khuyenMais()
            ->where('trang_thai', 'hoat_dong')
            ->where('ngay_ket_thuc', '>=', now())
            ->with(['rapPhims' => function($query) use ($chiNhanh) {
                $query->where('chi_nhanh_id', $chiNhanh->id);
            }])
            ->get();

        return view('admin.chi-nhanh-rap-khuyen-mai.index', compact('chiNhanh', 'rapPhims', 'khuyenMais'));
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

        $validated = $request->validate([
            'khuyen_mai_id' => 'required|exists:khuyen_mais,id',
            'rap_phim_id' => 'required|exists:rap_phims,id'
        ]);

        try {
            $khuyenMai = KhuyenMai::findOrFail($validated['khuyen_mai_id']);
            $rapPhim = RapPhim::findOrFail($validated['rap_phim_id']);
            $chiNhanh = $user->chiNhanhDangQuanLy;

            // Kiểm tra rạp có thuộc chi nhánh không
            if ($rapPhim->chi_nhanh_id !== $chiNhanh->id) {
                return response()->json(['success' => false, 'message' => 'Rạp này không thuộc chi nhánh bạn quản lý.']);
            }

            // Hủy liên kết
            $khuyenMai->rapPhims()->detach($validated['rap_phim_id']);

            return response()->json(['success' => true, 'message' => 'Đã hủy gán khuyến mãi khỏi rạp.']);

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
            'data' => $assignedRaps
        ]);
    }
}

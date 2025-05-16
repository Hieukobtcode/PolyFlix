<?php

namespace App\Http\Controllers\Admin;


use App\Models\ChiNhanh;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\KhuyenMaiChiNhanh;
use App\Models\LichSuSuDungKhuyenMai;

class KhuyenMaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy các tham số lọc từ request
        $trangThai = $request->input('trang_thai');
        $loaiGiamGia = $request->input('loai_giam_gia');
        $apDungCho = $request->input('ap_dung_cho');
        $search = $request->input('search');

        // Query cơ bản
        $query = KhuyenMai::query();

        // Áp dụng các bộ lọc nếu có
        if ($trangThai) {
            $query->where('trang_thai', $trangThai);
        }

        if ($loaiGiamGia) {
            $query->where('loai_giam_gia', $loaiGiamGia);
        }

        if ($apDungCho) {
            $query->where('ap_dung_cho', $apDungCho);
        }

        // Tìm kiếm theo tên hoặc mã khuyến mãi
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                    ->orWhere('ma_khuyen_mai', 'like', "%{$search}%");
            });
        }

        // Sắp xếp và phân trang
        $khuyenMais = $query->orderBy('ngay_bat_dau', 'desc')->paginate(10);

        // Chuyển đổi các trường ngày tháng thành đối tượng Carbon
        foreach ($khuyenMais as $khuyenMai) {
            if (!($khuyenMai->ngay_bat_dau instanceof \DateTime)) {
                $khuyenMai->ngay_bat_dau = \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau);
            }
            if (!($khuyenMai->ngay_ket_thuc instanceof \DateTime)) {
                $khuyenMai->ngay_ket_thuc = \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc);
            }
        }

        return view('admin.khuyen-mai.index', compact('khuyenMais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy danh sách chi nhánh để hiển thị trong form
        $chiNhanhs = ChiNhanh::where('trang_thai', 'hoat_dong')->get();

        return view('admin.khuyen-mai.create', compact('chiNhanhs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'ma_khuyen_mai' => 'required|string|max:50|unique:khuyen_mais',
            'ten' => 'required|string|max:255',
            'mo_ta' => 'required|string',
            'loai_giam_gia' => 'required|in:phan_tram,tien',
            'gia_tri_giam' => 'required|numeric|min:0',
            'giam_toi_da' => 'nullable|numeric|min:0',
            'ap_dung_cho' => 'required|in:ve,do_an,tat_ca',
            'don_toi_thieu' => 'nullable|numeric|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'so_lan_su_dung_toi_da' => 'nullable|integer|min:1',
            'trang_thai' => 'required|in:hoat_dong,tam_dung',
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id'
        ]);

        try {
            DB::beginTransaction();

            // Tạo khuyến mãi mới
            $khuyenMai = KhuyenMai::create([
                'ma_khuyen_mai' => $validated['ma_khuyen_mai'],
                'ten' => $validated['ten'],
                'mo_ta' => $validated['mo_ta'],
                'loai_giam_gia' => $validated['loai_giam_gia'],
                'gia_tri_giam' => $validated['gia_tri_giam'],
                'giam_toi_da' => $validated['giam_toi_da'],
                'ap_dung_cho' => $validated['ap_dung_cho'],
                'don_toi_thieu' => $validated['don_toi_thieu'],
                'ngay_bat_dau' => $validated['ngay_bat_dau'],
                'ngay_ket_thuc' => $validated['ngay_ket_thuc'],
                'so_lan_su_dung_toi_da' => $validated['so_lan_su_dung_toi_da'],
                'so_lan_da_su_dung' => 0,
                'trang_thai' => $validated['trang_thai'],
            ]);

            // Gán khuyến mãi cho các chi nhánh đã chọn
            foreach ($validated['chi_nhanh_ids'] as $chiNhanhId) {
                KhuyenMaiChiNhanh::create([
                    'khuyen_mai_id' => $khuyenMai->id,
                    'chi_nhanh_id' => $chiNhanhId
                ]);
            }

            DB::commit();

            return redirect()->route('admin.khuyen-mai.index')
                ->with('success', 'Khuyến mãi đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $khuyenMai = KhuyenMai::with(['chiNhanhs', 'lichSuSuDung.nguoiDung'])->findOrFail($id);

        // Chuyển đổi các trường ngày tháng thành đối tượng Carbon
        if (!($khuyenMai->ngay_bat_dau instanceof \DateTime)) {
            $khuyenMai->ngay_bat_dau = \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau);
        }
        if (!($khuyenMai->ngay_ket_thuc instanceof \DateTime)) {
            $khuyenMai->ngay_ket_thuc = \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc);
        }

        // Thống kê sử dụng
        $thongKe = [
            'tong_luot_su_dung' => $khuyenMai->so_lan_da_su_dung,
            'con_lai' => $khuyenMai->so_lan_su_dung_toi_da ? ($khuyenMai->so_lan_su_dung_toi_da - $khuyenMai->so_lan_da_su_dung) : 'Không giới hạn',
            'trang_thai' => $khuyenMai->trang_thai,
            'thoi_gian_con_lai' => now()->diffInDays($khuyenMai->ngay_ket_thuc, false) > 0
                ? now()->diffInDays($khuyenMai->ngay_ket_thuc) . ' ngày'
                : 'Đã hết hạn'
        ];

        return view('admin.khuyen-mai.show', compact('khuyenMai', 'thongKe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        // Chuyển đổi các trường ngày tháng thành đối tượng Carbon
        if (!($khuyenMai->ngay_bat_dau instanceof \DateTime)) {
            $khuyenMai->ngay_bat_dau = \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau);
        }
        if (!($khuyenMai->ngay_ket_thuc instanceof \DateTime)) {
            $khuyenMai->ngay_ket_thuc = \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc);
        }

        $chiNhanhs = ChiNhanh::where('trang_thai', 'hoat_dong')->get();

        // Lấy danh sách ID chi nhánh đã được gán cho khuyến mãi này
        $selectedChiNhanhIds = $khuyenMai->chiNhanhs->pluck('id')->toArray();

        return view('admin.khuyen-mai.edit', compact('khuyenMai', 'chiNhanhs', 'selectedChiNhanhIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'ma_khuyen_mai' => 'required|string|max:50|unique:khuyen_mais,ma_khuyen_mai,' . $id,
            'ten' => 'required|string|max:255',
            'mo_ta' => 'required|string',
            'loai_giam_gia' => 'required|in:phan_tram,tien',
            'gia_tri_giam' => 'required|numeric|min:0',
            'giam_toi_da' => 'nullable|numeric|min:0',
            'ap_dung_cho' => 'required|in:ve,do_an,tat_ca',
            'don_toi_thieu' => 'nullable|numeric|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'so_lan_su_dung_toi_da' => 'nullable|integer|min:1',
            'trang_thai' => 'required|in:hoat_dong,tam_dung',
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id'
        ]);

        try {
            DB::beginTransaction();

            // Cập nhật thông tin khuyến mãi
            $khuyenMai->update([
                'ma_khuyen_mai' => $validated['ma_khuyen_mai'],
                'ten' => $validated['ten'],
                'mo_ta' => $validated['mo_ta'],
                'loai_giam_gia' => $validated['loai_giam_gia'],
                'gia_tri_giam' => $validated['gia_tri_giam'],
                'giam_toi_da' => $validated['giam_toi_da'],
                'ap_dung_cho' => $validated['ap_dung_cho'],
                'don_toi_thieu' => $validated['don_toi_thieu'],
                'ngay_bat_dau' => $validated['ngay_bat_dau'],
                'ngay_ket_thuc' => $validated['ngay_ket_thuc'],
                'so_lan_su_dung_toi_da' => $validated['so_lan_su_dung_toi_da'],
                'trang_thai' => $validated['trang_thai'],
            ]);

            // Xóa tất cả các liên kết chi nhánh hiện tại
            KhuyenMaiChiNhanh::where('khuyen_mai_id', $khuyenMai->id)->delete();

            // Tạo lại các liên kết chi nhánh mới
            foreach ($validated['chi_nhanh_ids'] as $chiNhanhId) {
                KhuyenMaiChiNhanh::create([
                    'khuyen_mai_id' => $khuyenMai->id,
                    'chi_nhanh_id' => $chiNhanhId
                ]);
            }

            DB::commit();

            return redirect()->route('admin.khuyen-mai.index')
                ->with('success', 'Khuyến mãi đã được cập nhật thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $khuyenMai = KhuyenMai::findOrFail($id);

            // Sử dụng soft delete
            $khuyenMai->delete();

            return redirect()->route('admin.khuyen-mai.index')
                ->with('success', 'Khuyến mãi đã được xóa thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Gán khuyến mãi cho chi nhánh/rạp
     */
    public function assignToChiNhanh(Request $request, string $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'chi_nhanh_ids' => 'required|array',
            'chi_nhanh_ids.*' => 'exists:chi_nhanhs,id'
        ]);

        try {
            DB::beginTransaction();

            // Xóa tất cả các liên kết chi nhánh hiện tại
            KhuyenMaiChiNhanh::where('khuyen_mai_id', $khuyenMai->id)->delete();

            // Tạo lại các liên kết chi nhánh mới
            foreach ($validated['chi_nhanh_ids'] as $chiNhanhId) {
                KhuyenMaiChiNhanh::create([
                    'khuyen_mai_id' => $khuyenMai->id,
                    'chi_nhanh_id' => $chiNhanhId
                ]);
            }

            DB::commit();

            return redirect()->route('admin.khuyen-mai.show', $khuyenMai->id)
                ->with('success', 'Đã gán khuyến mãi cho chi nhánh thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị thống kê sử dụng khuyến mãi
     */
    public function thongKeSuDung(Request $request)
    {
        // Lấy các tham số lọc từ request
        $khuyenMaiId = $request->input('khuyen_mai_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query cơ bản
        $query = LichSuSuDungKhuyenMai::with(['khuyenMai', 'nguoiDung']);

        // Áp dụng các bộ lọc nếu có
        if ($khuyenMaiId) {
            $query->where('khuyen_mai_id', $khuyenMaiId);
        }

        if ($startDate) {
            $query->whereDate('thoi_gian_su_dung', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('thoi_gian_su_dung', '<=', $endDate);
        }

        // Sắp xếp và phân trang
        $lichSuSuDung = $query->orderBy('thoi_gian_su_dung', 'desc')->paginate(10);

        // Chuyển đổi các trường ngày tháng thành đối tượng Carbon
        foreach ($lichSuSuDung as $lichSu) {
            if (!($lichSu->thoi_gian_su_dung instanceof \DateTime)) {
                $lichSu->thoi_gian_su_dung = \Carbon\Carbon::parse($lichSu->thoi_gian_su_dung);
            }
        }

        // Lấy danh sách khuyến mãi để hiển thị trong dropdown lọc
        $khuyenMais = KhuyenMai::all();

        // Thống kê tổng hợp
        $thongKeTongHop = [
            'tong_luot_su_dung' => $lichSuSuDung->total(),
            'so_nguoi_dung' => LichSuSuDungKhuyenMai::select('nguoi_dung_id')->distinct()->count('nguoi_dung_id'),
            'khuyenMaiPhoBien' => KhuyenMai::orderBy('so_lan_da_su_dung', 'desc')->take(5)->get()
        ];

        return view('admin.khuyen-mai.thong_ke', compact('lichSuSuDung', 'khuyenMais', 'thongKeTongHop'));
    }
}

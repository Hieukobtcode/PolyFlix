<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi
     */
    public function index(Request $request)
    {
        $query = KhuyenMai::conHieuLuc()
            ->with(['chiNhanhs'])
            ->orderBy('ngay_bat_dau', 'desc');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ten', 'like', '%' . $request->search . '%')
                    ->orWhere('mo_ta', 'like', '%' . $request->search . '%')
                    ->orWhere('ma_khuyen_mai', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo loại áp dụng
        if ($request->filled('ap_dung_cho')) {
            $query->where('ap_dung_cho', $request->ap_dung_cho);
        }

        $khuyenMais = $query->paginate(12);

        // Nếu là AJAX request, chỉ trả về dữ liệu JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'promotions' => $khuyenMais->items(),
                    'pagination' => [
                        'current_page' => $khuyenMais->currentPage(),
                        'last_page' => $khuyenMais->lastPage(),
                        'per_page' => $khuyenMais->perPage(),
                        'total' => $khuyenMais->total(),
                        'has_more_pages' => $khuyenMais->hasMorePages(),
                        'links' => $khuyenMais->links()->render()
                    ]
                ],
                'filters' => [
                    'search' => $request->get('search', ''),
                    'ap_dung_cho' => $request->get('ap_dung_cho', '')
                ]
            ]);
        }

        return view('client.khuyen-mai.index', compact('khuyenMais'));
    }

    /**
     * API endpoint để lấy danh sách khuyến mãi (AJAX)
     */
    public function apiIndex(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Hiển thị chi tiết khuyến mãi
     */
    public function show($id)
    {
        $khuyenMai = KhuyenMai::conHieuLuc()
            ->with(['chiNhanhs'])
            ->findOrFail($id);

        // Lấy khuyến mãi liên quan
        $khuyenMaisLienQuan = KhuyenMai::conHieuLuc()
            ->where('id', '!=', $id)
            ->where('ap_dung_cho', $khuyenMai->ap_dung_cho)
            ->limit(4)
            ->get();

        return view('client.khuyen-mai.show', compact('khuyenMai', 'khuyenMaisLienQuan'));
    }

    /**
     * Kiểm tra mã khuyến mãi (AJAX)
     */
    public function checkCode(Request $request)
    {
        $request->validate([
            'ma_khuyen_mai' => 'required|string',
            'tong_tien' => 'required|numeric|min:0',
            'loai_san_pham' => 'nullable|string|in:ve,do_an,tat_ca' // Thêm validation cho loại sản phẩm
        ]);

        $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $request->ma_khuyen_mai)
            ->conHieuLuc()
            ->first();

        if (!$khuyenMai) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn'
            ]);
        }

        // Kiểm tra loại áp dụng khuyến mãi
        $loaiSanPham = $request->get('loai_san_pham', 've'); // Mặc định là vé phim

        // Nếu khuyến mãi không áp dụng cho loại sản phẩm này
        if ($khuyenMai->ap_dung_cho !== 'tat_ca' && $khuyenMai->ap_dung_cho !== $loaiSanPham) {
            $tenLoai = $loaiSanPham === 've' ? 'vé phim' : 'đồ ăn/combo';
            $tenKhuyenMai = $khuyenMai->ap_dung_cho === 've' ? 'vé phim' : ($khuyenMai->ap_dung_cho === 'do_an' ? 'đồ ăn/combo' : 'tất cả sản phẩm');

            return response()->json([
                'success' => false,
                'message' => "Mã khuyến mãi này chỉ áp dụng cho {$tenKhuyenMai}, không áp dụng cho {$tenLoai}"
            ]);
        }

        // Kiểm tra đơn tối thiểu
        if ($request->tong_tien < $khuyenMai->don_toi_thieu) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($khuyenMai->don_toi_thieu) . 'đ'
            ]);
        }

        // Kiểm tra số lần sử dụng
        if ($khuyenMai->so_lan_su_dung_toi_da && $khuyenMai->so_lan_da_su_dung >= $khuyenMai->so_lan_su_dung_toi_da) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi đã hết lượt sử dụng'
            ]);
        }

        // Tính toán giảm giá
        $giam_gia = 0;
        if ($khuyenMai->loai_giam_gia === 'phan_tram') {
            $giam_gia = ($request->tong_tien * $khuyenMai->gia_tri_giam) / 100;
            if ($khuyenMai->giam_toi_da > 0 && $giam_gia > $khuyenMai->giam_toi_da) {
                $giam_gia = $khuyenMai->giam_toi_da;
            }
        } else {
            $giam_gia = $khuyenMai->gia_tri_giam;
        }

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã khuyến mãi thành công',
            'data' => [
                'id' => $khuyenMai->id,
                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                'ten' => $khuyenMai->ten,
                'giam_gia' => $giam_gia,
                'tong_sau_giam' => $request->tong_tien - $giam_gia
            ]
        ]);
    }

    /**
     * Lấy khuyến mãi nổi bật cho trang chủ
     */
    public function getFeatured()
    {
        $khuyenMais = KhuyenMai::conHieuLuc()
            ->orderBy('gia_tri_giam', 'desc')
            ->limit(4)
            ->get();

        return $khuyenMais;
    }

    /**
     * Lấy danh sách khuyến mãi theo loại (API)
     */
    public function getByType(Request $request)
    {
        $loai = $request->get('loai', 've'); // Mặc định là vé phim

        $khuyenMais = KhuyenMai::conHieuLuc()
            ->where(function ($query) use ($loai) {
                $query->where('ap_dung_cho', $loai)
                    ->orWhere('ap_dung_cho', 'tat_ca');
            })
            ->orderBy('gia_tri_giam', 'desc')
            ->get(['id', 'ma_khuyen_mai', 'ten', 'mo_ta', 'loai_giam_gia', 'gia_tri_giam', 'giam_toi_da', 'don_toi_thieu', 'ap_dung_cho']);

        return response()->json([
            'success' => true,
            'data' => $khuyenMais
        ]);
    }
}

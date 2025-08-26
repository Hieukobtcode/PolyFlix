<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            'loai_san_pham' => 'nullable|string|in:ve,do_an,tat_ca', // Thêm validation cho loại sản phẩm
            'dat_ve_id' => 'nullable|integer|exists:dat_ves,id'
        ]);

        Log::info('Check promotion code started', [
            'ma_khuyen_mai' => $request->ma_khuyen_mai,
            'tong_tien' => $request->tong_tien,
            'dat_ve_id' => $request->dat_ve_id,
            'user_id' => Auth::id(),
            'request_all' => $request->all()
        ]);

        $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $request->ma_khuyen_mai)
            ->conHieuLuc()
            ->with(['chiNhanhs', 'rapPhims']) // Load thông tin chi nhánh và rạp
            ->first();

        if (!$khuyenMai) {
            return response()->json([
                'success' => false,
                'message' => 'Mã khuyến mãi không tồn tại hoặc đã hết hạn'
            ]);
        }

        // Kiểm tra số lượt sử dụng toàn hệ thống (kiểm tra sớm để tránh tính toán không cần thiết)
        if ($khuyenMai->so_lan_su_dung_toi_da && $khuyenMai->so_lan_da_su_dung >= $khuyenMai->so_lan_su_dung_toi_da) {
            $conLai = max(0, $khuyenMai->so_lan_su_dung_toi_da - $khuyenMai->so_lan_da_su_dung);
            return response()->json([
                'success' => false,
                'message' => "Mã khuyến mãi đã hết lượt sử dụng (đã dùng {$khuyenMai->so_lan_da_su_dung}/{$khuyenMai->so_lan_su_dung_toi_da} lượt)"
            ]);
        }

        // Kiểm tra chi nhánh và rạp (nếu có dat_ve_id)
        if ($request->filled('dat_ve_id')) {
            Log::info('dat_ve_id is provided, checking branch/cinema validation');
            $datVe = \App\Models\DatVe::with(['suatChieu.phongChieu.rapPhim.chiNhanh'])
                ->find($request->dat_ve_id);

            if ($datVe && $datVe->suatChieu) {
                $chiNhanhHienTai = $datVe->suatChieu->phongChieu->rapPhim->chiNhanh ?? null;
                $rapPhimHienTai = $datVe->suatChieu->phongChieu->rapPhim ?? null;

                Log::info('Promotion validation - Current cinema info', [
                    'chi_nhanh_hien_tai' => $chiNhanhHienTai ? ['id' => $chiNhanhHienTai->id, 'ten' => $chiNhanhHienTai->ten_chi_nhanh] : null,
                    'rap_phim_hien_tai' => $rapPhimHienTai ? ['id' => $rapPhimHienTai->id, 'ten' => $rapPhimHienTai->ten] : null,
                    'chi_nhanhs_hop_le' => $khuyenMai->chiNhanhs->pluck('ten_chi_nhanh')->toArray(),
                    'rap_phims_hop_le' => $khuyenMai->rapPhims->pluck('ten')->toArray()
                ]);

                // Kiểm tra nếu khuyến mãi có giới hạn chi nhánh
                if ($khuyenMai->chiNhanhs->count() > 0) {
                    $coChiNhanhHopLe = $khuyenMai->chiNhanhs->contains('id', $chiNhanhHienTai->id);
                    if (!$coChiNhanhHopLe) {
                        $tenChiNhanhHopLe = $khuyenMai->chiNhanhs->pluck('ten_chi_nhanh')->join(', ');
                        Log::warning('Promotion validation failed - Wrong branch', [
                            'chi_nhanh_hien_tai' => $chiNhanhHienTai->ten_chi_nhanh,
                            'chi_nhanhs_hop_le' => $tenChiNhanhHopLe
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => "Mã khuyến mãi này chỉ áp dụng tại: {$tenChiNhanhHopLe}. Hiện tại bạn đang đặt vé tại {$chiNhanhHienTai->ten_chi_nhanh}."
                        ]);
                    }
                }

                // Kiểm tra nếu khuyến mãi có giới hạn rạp
                if ($khuyenMai->rapPhims->count() > 0) {
                    $coRapHopLe = $khuyenMai->rapPhims->contains('id', $rapPhimHienTai->id);
                    if (!$coRapHopLe) {
                        $tenRapHopLe = $khuyenMai->rapPhims->pluck('ten')->join(', ');
                        Log::warning('Promotion validation failed - Wrong cinema', [
                            'rap_phim_hien_tai' => $rapPhimHienTai->ten,
                            'rap_phims_hop_le' => $tenRapHopLe
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => "Mã khuyến mãi này chỉ áp dụng tại: {$tenRapHopLe}. Hiện tại bạn đang đặt vé tại {$rapPhimHienTai->ten}."
                        ]);
                    }
                }
            }
        } else {
            Log::warning('dat_ve_id not provided - skipping branch validation');
        }

        // Kiểm tra loại áp dụng khuyến mãi
        $loaiSanPham = $request->get('loai_san_pham', 've'); // Mặc định là vé phim

        // Nếu khuyến mãi không áp dụng cho loại sản phẩm này
        if ($khuyenMai->ap_dung_cho !== 'tat_ca' && $khuyenMai->ap_dung_cho !== $loaiSanPham) {
            $tenLoai = $loaiSanPham === 've' ? 'vé phim' : 'đồ ăn/combo';
            $tenKhuyenMai = $khuyenMai->ap_dung_cho === 've' ? 'vé phim' : ($khuyenMai->ap_dung_cho === 'do_an' ? 'đồ ăn/combo' : 'tất cả sản phẩm');

            Log::warning('Promotion validation failed - Wrong product type', [
                'khuyen_mai_ap_dung_cho' => $khuyenMai->ap_dung_cho,
                'loai_san_pham_yeu_cau' => $loaiSanPham
            ]);

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

        $response = [
            'success' => true,
            'message' => 'Áp dụng mã khuyến mãi thành công',
            'khuyen_mai_id' => $khuyenMai->id,
            'discount' => $giam_gia,
            'data' => [
                'id' => $khuyenMai->id,
                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                'ten' => $khuyenMai->ten,
                'giam_gia' => $giam_gia,
                'tong_sau_giam' => $request->tong_tien - $giam_gia
            ]
        ];

        // Nếu có dat_ve_id, cập nhật trực tiếp đơn chờ thanh toán của user hiện tại
        try {
            Log::info('Apply promo attempt', [
                'route' => 'khuyen-mai.check-code',
                'dat_ve_id' => $request->input('dat_ve_id'),
                'user_id' => Auth::id(),
                'success' => true,
                'promo_code' => $khuyenMai->ma_khuyen_mai,
                'discount' => $giam_gia,
                'original_total' => $request->tong_tien
            ]);

            if ($request->filled('dat_ve_id') && Auth::check()) {
                $datVe = \App\Models\DatVe::where('id', $request->dat_ve_id)
                    ->where('user_id', Auth::id())
                    ->where('trang_thai', 'Chờ thanh toán')
                    ->first();

                if ($datVe) {
                    $oldTotal = (int) $datVe->tong_tien;
                    $datVe->khuyen_mai_id = $khuyenMai->id;
                    $datVe->tong_tien = max(0, (int) ($request->tong_tien - $giam_gia));
                    $datVe->save();

                    $response['order_updated'] = true;
                    $response['order_total'] = $datVe->tong_tien;

                    Log::info('Apply promo updated order', [
                        'dat_ve_id' => $datVe->id,
                        'old_total' => $oldTotal,
                        'new_total' => $datVe->tong_tien,
                        'khuyen_mai_id' => $datVe->khuyen_mai_id
                    ]);
                } else {
                    Log::warning('Apply promo: order not found or not in pending state', [
                        'dat_ve_id' => $request->dat_ve_id,
                        'user_id' => Auth::id()
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Apply promo update error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return response()->json($response);
    }

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

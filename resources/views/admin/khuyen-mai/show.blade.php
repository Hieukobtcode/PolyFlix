@extends('layouts.admin')

@section('title', 'Chi Tiết Khuyến Mãi')
@section('page-title', 'Quản lý Khuyến Mãi')
@section('breadcrumb', 'Chi tiết khuyến mãi')

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
    }
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.7em;
    }
    .stats-card {
        border-radius: 10px;
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .stats-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    .chi-nhanh-item {
        border-radius: 8px;
        transition: all 0.2s;
    }
    .chi-nhanh-item:hover {
        background-color: #f8f9fa;
    }
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 8px;
    }
</style>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Thông tin khuyến mãi -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Thông tin khuyến mãi</h5>
                    <div>
                        <a href="{{ route('admin.khuyen-mai.edit', $khuyenMai->id) }}" class="btn btn-light btn-sm me-1">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.khuyen-mai.destroy', $khuyenMai->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                <i class="fas fa-trash me-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Mã khuyến mãi</div>
                            <div class="fw-bold">{{ $khuyenMai->ma_khuyen_mai }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Tên khuyến mãi</div>
                            <div class="fw-bold">{{ $khuyenMai->ten }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-label">Mô tả</div>
                            <div>{{ $khuyenMai->mo_ta }}</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-label">Loại giảm giá</div>
                            <div>
                                @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                    <span class="badge bg-info">Phần trăm</span>
                                @else
                                    <span class="badge bg-success">Tiền</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-label">Giá trị giảm</div>
                            <div class="fw-bold">
                                @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                    {{ $khuyenMai->gia_tri_giam }}%
                                @else
                                    {{ number_format($khuyenMai->gia_tri_giam) }}đ
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-label">Giảm tối đa</div>
                            <div>
                                @if($khuyenMai->giam_toi_da)
                                    {{ number_format($khuyenMai->giam_toi_da) }}đ
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-label">Áp dụng cho</div>
                            <div>
                                @if($khuyenMai->ap_dung_cho == 've')
                                    <span class="badge bg-primary">Vé xem phim</span>
                                @elseif($khuyenMai->ap_dung_cho == 'do_an')
                                    <span class="badge bg-warning">Đồ ăn</span>
                                @else
                                    <span class="badge bg-dark">Tất cả</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-label">Đơn tối thiểu</div>
                            <div>
                                @if($khuyenMai->don_toi_thieu)
                                    {{ number_format($khuyenMai->don_toi_thieu) }}đ
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-label">Số lần sử dụng tối đa</div>
                            <div>
                                @if($khuyenMai->so_lan_su_dung_toi_da)
                                    {{ number_format($khuyenMai->so_lan_su_dung_toi_da) }} lần
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Ngày bắt đầu</div>
                            <div>{{ $khuyenMai->ngay_bat_dau->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Ngày kết thúc</div>
                            <div>{{ $khuyenMai->ngay_ket_thuc->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Trạng thái</div>
                            <div>
                                @if($khuyenMai->trang_thai == 'hoat_dong')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Tạm dừng</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-label">Ngày tạo</div>
                            <div>{{ $khuyenMai->create_at instanceof \DateTime ? $khuyenMai->create_at->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($khuyenMai->create_at)) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi nhánh áp dụng -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Chi nhánh áp dụng</h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#assignChiNhanhModal">
                        <i class="fas fa-edit me-1"></i> Cập nhật
                    </button>
                </div>
                <div class="card-body p-4">
                    @if($khuyenMai->chiNhanhs->count() > 0)
                        <div class="row">
                            @foreach($khuyenMai->chiNhanhs as $chiNhanh)
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border chi-nhanh-item">
                                        <div class="fw-bold">{{ $chiNhanh->ten_chi_nhanh }}</div>
                                        <div class="small text-muted">{{ $chiNhanh->dia_chi }}</div>
                                        @if($chiNhanh->trang_thai == 'hoat_dong')
                                            <span class="badge bg-success mt-2">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary mt-2">{{ $chiNhanh->trang_thai }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-muted">Chưa có chi nhánh nào được áp dụng</div>
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#assignChiNhanhModal">
                                <i class="fas fa-plus me-1"></i> Thêm chi nhánh
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thống kê sử dụng -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">Thống kê sử dụng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="stats-card bg-primary bg-opacity-10 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-primary text-white me-3">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Tổng lượt sử dụng</div>
                                        <div class="h4 mb-0">{{ number_format($thongKe['tong_luot_su_dung']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="stats-card bg-success bg-opacity-10 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-success text-white me-3">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Còn lại</div>
                                        <div class="h4 mb-0">{{ $thongKe['con_lai'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="stats-card bg-info bg-opacity-10 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-info text-white me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Thời gian còn lại</div>
                                        <div class="h4 mb-0">{{ $thongKe['thoi_gian_con_lai'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="stats-card bg-warning bg-opacity-10 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon bg-warning text-white me-3">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Trạng thái</div>
                                        <div class="h4 mb-0">
                                            @if($thongKe['trang_thai'] == 'hoat_dong')
                                                <span class="text-success">Hoạt động</span>
                                            @else
                                                <span class="text-secondary">Tạm dừng</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.khuyen-mai.thong-ke-su-dung', ['khuyen_mai_id' => $khuyenMai->id]) }}" class="btn btn-primary w-100">
                            <i class="fas fa-chart-bar me-1"></i> Xem chi tiết thống kê
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lịch sử sử dụng gần đây -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0 fw-bold">Lịch sử sử dụng gần đây</h5>
                </div>
                <div class="card-body p-4">
                    @if($khuyenMai->lichSuSuDung->count() > 0)
                        <div class="list-group">
                            @foreach($khuyenMai->lichSuSuDung->take(5) as $lichSu)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">{{ $lichSu->nguoiDung->name ?? 'Người dùng không xác định' }}</div>
                                            <small class="text-muted">{{ $lichSu->thoi_gian_su_dung instanceof \DateTime ? $lichSu->thoi_gian_su_dung->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($lichSu->thoi_gian_su_dung)) }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">Sử dụng</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($khuyenMai->lichSuSuDung->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.khuyen-mai.thong-ke-su-dung', ['khuyen_mai_id' => $khuyenMai->id]) }}" class="btn btn-sm btn-outline-primary">
                                    Xem tất cả
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="text-muted">Chưa có lịch sử sử dụng</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal gán chi nhánh -->
<div class="modal fade" id="assignChiNhanhModal" tabindex="-1" aria-labelledby="assignChiNhanhModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.khuyen-mai.assign-chi-nhanh', $khuyenMai->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="assignChiNhanhModalLabel">Gán khuyến mãi cho chi nhánh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="chi_nhanh_ids" class="form-label">Chọn chi nhánh áp dụng</label>
                        <select class="form-select select2-modal" id="chi_nhanh_ids" name="chi_nhanh_ids[]" multiple required>
                            @foreach($khuyenMai->chiNhanhs->pluck('id')->toArray() as $selectedId)
                                <option value="{{ $selectedId }}" selected>
                                    {{ App\Models\ChiNhanh::find($selectedId)->ten_chi_nhanh }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Chọn một hoặc nhiều chi nhánh</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-modal').select2({
            theme: 'bootstrap-5',
            placeholder: 'Chọn chi nhánh áp dụng',
            allowClear: true,
            dropdownParent: $('#assignChiNhanhModal'),
            ajax: {
                url: '{{ route("admin.chi-nhanh.index") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.ten_chi_nhanh,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endsection

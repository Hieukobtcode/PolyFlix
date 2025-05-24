@extends('layouts.admin')

@section('title', 'Quản lý Khuyến Mãi')
@section('page-title', 'Quản lý Khuyến Mãi')
@section('breadcrumb', 'Danh sách khuyến mãi')

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .table th {
        background-color: #212529;
        color: white;
    }
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.7em;
    }
    .search-box {
        position: relative;
    }
    .search-box i {
        position: absolute;
        top: 10px;
        left: 10px;
        color: #6c757d;
    }
    .search-input {
        padding-left: 35px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Danh sách khuyến mãi</h5>
            <a href="{{ route('admin.khuyen-mai.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus me-1"></i> Thêm khuyến mãi
            </a>
        </div>
        <div class="card-body p-4">
            <!-- Bộ lọc và tìm kiếm -->
            <form method="GET" action="{{ route('admin.khuyen-mai.index') }}" class="row mb-4">
                <div class="col-md-3 mb-2">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control search-input"
                            value="{{ request('search') }}" placeholder="Tìm theo tên hoặc mã...">
                    </div>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="tam_dung" {{ request('trang_thai') == 'tam_dung' ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="loai_giam_gia" class="form-select">
                        <option value="">Tất cả loại giảm giá</option>
                        <option value="phan_tram" {{ request('loai_giam_gia') == 'phan_tram' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="tien" {{ request('loai_giam_gia') == 'tien' ? 'selected' : '' }}>Tiền</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="ap_dung_cho" class="form-select">
                        <option value="">Tất cả loại áp dụng</option>
                        <option value="ve" {{ request('ap_dung_cho') == 've' ? 'selected' : '' }}>Vé</option>
                        <option value="do_an" {{ request('ap_dung_cho') == 'do_an' ? 'selected' : '' }}>Đồ ăn</option>
                        <option value="tat_ca" {{ request('ap_dung_cho') == 'tat_ca' ? 'selected' : '' }}>Tất cả</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 d-flex">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.khuyen-mai.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Đặt lại
                    </a>
                </div>
            </form>

            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center" style="width: 5%">#</th>
                            <th scope="col" style="width: 10%">Mã KM</th>
                            <th scope="col" style="width: 20%">Tên khuyến mãi</th>
                            <th scope="col" style="width: 15%">Giá trị</th>
                            <th scope="col" style="width: 10%">Áp dụng cho</th>
                            <th scope="col" style="width: 15%">Thời gian</th>
                            <th scope="col" class="text-center" style="width: 10%">Trạng thái</th>
                            <th scope="col" class="text-center" style="width: 15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($khuyenMais as $index => $khuyenMai)
                        <tr>
                            <td class="text-center">{{ $index + $khuyenMais->firstItem() }}</td>
                            <td>{{ $khuyenMai->ma_khuyen_mai }}</td>
                            <td>
                                <div class="fw-semibold">{{ $khuyenMai->ten }}</div>
                                <small class="text-muted">{{ Str::limit($khuyenMai->mo_ta, 50) }}</small>
                            </td>
                            <td>
                                @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                    <span class="badge bg-info">{{ $khuyenMai->gia_tri_giam }}%</span>
                                    @if($khuyenMai->giam_toi_da)
                                        <small class="d-block text-muted">Tối đa: {{ number_format($khuyenMai->giam_toi_da) }}đ</small>
                                    @endif
                                @else
                                    <span class="badge bg-success">{{ number_format($khuyenMai->gia_tri_giam) }}đ</span>
                                @endif
                                @if($khuyenMai->don_toi_thieu)
                                    <small class="d-block text-muted">Đơn tối thiểu: {{ number_format($khuyenMai->don_toi_thieu) }}đ</small>
                                @endif
                            </td>
                            <td>
                                @if($khuyenMai->ap_dung_cho == 've')
                                    <span class="badge bg-primary">Vé</span>
                                @elseif($khuyenMai->ap_dung_cho == 'do_an')
                                    <span class="badge bg-warning">Đồ ăn</span>
                                @else
                                    <span class="badge bg-dark">Tất cả</span>
                                @endif
                            </td>
                            <td>
                                <small class="d-block">Bắt đầu: {{ $khuyenMai->ngay_bat_dau instanceof \DateTime ? $khuyenMai->ngay_bat_dau->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($khuyenMai->ngay_bat_dau)) }}</small>
                                <small class="d-block">Kết thúc: {{ $khuyenMai->ngay_ket_thuc instanceof \DateTime ? $khuyenMai->ngay_ket_thuc->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($khuyenMai->ngay_ket_thuc)) }}</small>
                                @php
                                    $now = now();
                                    $ngayBatDau = $khuyenMai->ngay_bat_dau instanceof \DateTime ? $khuyenMai->ngay_bat_dau : \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau);
                                    $ngayKetThuc = $khuyenMai->ngay_ket_thuc instanceof \DateTime ? $khuyenMai->ngay_ket_thuc : \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc);
                                    $isActive = $now->between($ngayBatDau, $ngayKetThuc);
                                    $isExpired = $now->greaterThan($ngayKetThuc);
                                    $isUpcoming = $now->lessThan($ngayBatDau);
                                @endphp
                                @if($isActive)
                                    <span class="badge bg-success">Đang diễn ra</span>
                                @elseif($isExpired)
                                    <span class="badge bg-danger">Đã kết thúc</span>
                                @elseif($isUpcoming)
                                    <span class="badge bg-info">Sắp diễn ra</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($khuyenMai->trang_thai == 'hoat_dong')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Tạm dừng</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.khuyen-mai.show', $khuyenMai->id) }}" class="btn btn-sm btn-info mb-1" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.khuyen-mai.edit', $khuyenMai->id) }}" class="btn btn-sm btn-primary mb-1" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.khuyen-mai.destroy', $khuyenMai->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" title="Xóa"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa khuyến mãi này?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">Không có dữ liệu khuyến mãi</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small class="text-muted">Hiển thị {{ $khuyenMais->count() }} trong tổng số {{ $khuyenMais->total() }} khuyến mãi</small>
                </div>
                <div>
                    {{ $khuyenMais->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Khuyến mãi chi nhánh')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ti ti-gift me-2"></i>
                            Khuyến mãi - {{ $chiNhanh->ten_chi_nhanh }}
                        </h4>
                        <a href="{{ route('admin.chi-nhanh-khuyen-mai.bao-cao') }}" class="btn btn-light btn-sm">
                            <i class="ti ti-chart-bar me-1"></i>
                            Báo cáo
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Bộ lọc -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" class="d-flex flex-wrap gap-3 align-items-end">
                                <div class="flex-grow-1">
                                    <label class="form-label">Tìm kiếm</label>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Tên hoặc mã khuyến mãi..." 
                                           value="{{ request('search') }}">
                                </div>
                                
                                <div>
                                    <label class="form-label">Trạng thái</label>
                                    <select name="trang_thai" class="form-select">
                                        <option value="">Tất cả</option>
                                        <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>
                                            Hoạt động
                                        </option>
                                        <option value="tam_dung" {{ request('trang_thai') == 'tam_dung' ? 'selected' : '' }}>
                                            Tạm dừng
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Áp dụng cho</label>
                                    <select name="ap_dung_cho" class="form-select">
                                        <option value="">Tất cả</option>
                                        <option value="ve" {{ request('ap_dung_cho') == 've' ? 'selected' : '' }}>
                                            Vé xem phim
                                        </option>
                                        <option value="do_an" {{ request('ap_dung_cho') == 'do_an' ? 'selected' : '' }}>
                                            Đồ ăn
                                        </option>
                                        <option value="tat_ca" {{ request('ap_dung_cho') == 'tat_ca' ? 'selected' : '' }}>
                                            Tất cả
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Thời gian</label>
                                    <select name="thoi_gian" class="form-select">
                                        <option value="">Tất cả</option>
                                        <option value="con_hieu_luc" {{ request('thoi_gian') == 'con_hieu_luc' ? 'selected' : '' }}>
                                            Còn hiệu lực
                                        </option>
                                        <option value="sap_het_han" {{ request('thoi_gian') == 'sap_het_han' ? 'selected' : '' }}>
                                            Sắp hết hạn (7 ngày)
                                        </option>
                                        <option value="da_het_han" {{ request('thoi_gian') == 'da_het_han' ? 'selected' : '' }}>
                                            Đã hết hạn
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search me-1"></i>
                                        Lọc
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="ti ti-gift fs-3 mb-2"></i>
                                    <h4 class="mb-1">{{ $khuyenMais->total() }}</h4>
                                    <p class="mb-0">Tổng khuyến mãi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="ti ti-check-circle fs-3 mb-2"></i>
                                    <h4 class="mb-1">
                                        {{ $khuyenMais->where('trang_thai', 'hoat_dong')
                                            ->where('ngay_bat_dau', '<=', now())
                                            ->where('ngay_ket_thuc', '>=', now())
                                            ->count() }}
                                    </h4>
                                    <p class="mb-0">Đang hoạt động</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <i class="ti ti-clock fs-3 mb-2"></i>
                                    <h4 class="mb-1">
                                        {{ $khuyenMais->where('ngay_ket_thuc', '>=', now())
                                            ->where('ngay_ket_thuc', '<=', now()->addDays(7))
                                            ->count() }}
                                    </h4>
                                    <p class="mb-0">Sắp hết hạn</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <i class="ti ti-x-circle fs-3 mb-2"></i>
                                    <h4 class="mb-1">
                                        {{ $khuyenMais->where('ngay_ket_thuc', '<', now())->count() }}
                                    </h4>
                                    <p class="mb-0">Đã hết hạn</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách khuyến mãi -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Mã KM</th>
                                    <th>Tên khuyến mãi</th>
                                    <th>Loại giảm giá</th>
                                    <th>Giá trị</th>
                                    <th>Áp dụng cho</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($khuyenMais as $index => $khuyenMai)
                                    <tr>
                                        <td>{{ $khuyenMais->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $khuyenMai->ma_khuyen_mai }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $khuyenMai->ten }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($khuyenMai->mo_ta, 50) }}</small>
                                        </td>
                                        <td>
                                            @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                                <span class="badge bg-info">Phần trăm</span>
                                            @else
                                                <span class="badge bg-warning">Tiền mặt</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                                <strong class="text-primary">{{ $khuyenMai->gia_tri_giam }}%</strong>
                                                @if($khuyenMai->giam_toi_da)
                                                    <br><small class="text-muted">Tối đa: {{ number_format($khuyenMai->giam_toi_da) }}đ</small>
                                                @endif
                                            @else
                                                <strong class="text-primary">{{ number_format($khuyenMai->gia_tri_giam) }}đ</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($khuyenMai->ap_dung_cho)
                                                @case('ve')
                                                    <span class="badge bg-primary">Vé xem phim</span>
                                                    @break
                                                @case('do_an')
                                                    <span class="badge bg-success">Đồ ăn</span>
                                                    @break
                                                @case('tat_ca')
                                                    <span class="badge bg-purple">Tất cả</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="text-nowrap">
                                                <small>
                                                    <i class="ti ti-calendar me-1"></i>
                                                    {{ $khuyenMai->ngay_bat_dau->format('d/m/Y') }}
                                                </small>
                                                <br>
                                                <small>
                                                    <i class="ti ti-calendar-x me-1"></i>
                                                    {{ $khuyenMai->ngay_ket_thuc->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $now = now();
                                                $isActive = $khuyenMai->trang_thai == 'hoat_dong' 
                                                    && $khuyenMai->ngay_bat_dau <= $now 
                                                    && $khuyenMai->ngay_ket_thuc >= $now;
                                                $isExpired = $khuyenMai->ngay_ket_thuc < $now;
                                                $isUpcoming = $khuyenMai->ngay_bat_dau > $now;
                                            @endphp

                                            @if($isExpired)
                                                <span class="badge bg-danger">Đã hết hạn</span>
                                            @elseif($khuyenMai->trang_thai == 'tam_dung')
                                                <span class="badge bg-warning">Tạm dừng</span>
                                            @elseif($isUpcoming)
                                                <span class="badge bg-info">Sắp bắt đầu</span>
                                            @elseif($isActive)
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Không xác định</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.chi-nhanh-khuyen-mai.show', $khuyenMai->id) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Xem chi tiết">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-gift fs-3 mb-2"></i>
                                                <p class="mb-0">Không có khuyến mãi nào áp dụng cho chi nhánh này</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    @if($khuyenMais->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $khuyenMais->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.text-nowrap {
    white-space: nowrap;
}
</style>
@endpush

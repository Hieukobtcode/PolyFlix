@extends('layouts.admin')

@section('title', 'Chi tiết khuyến mãi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb và nút quay lại -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.chi-nhanh-khuyen-mai.index') }}">Khuyến mãi chi nhánh</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $khuyenMai->ten }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.chi-nhanh-khuyen-mai.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Quay lại
                </a>
            </div>

            <div class="row">
                <!-- Thông tin khuyến mãi -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="ti ti-gift me-2"></i>
                                Thông tin khuyến mãi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mã khuyến mãi</label>
                                    <div class="p-2 bg-light rounded">
                                        <span class="badge bg-secondary fs-6">{{ $khuyenMai->ma_khuyen_mai }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tên khuyến mãi</label>
                                    <div class="p-2 bg-light rounded">{{ $khuyenMai->ten }}</div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Mô tả</label>
                                    <div class="p-2 bg-light rounded">{{ $khuyenMai->mo_ta }}</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Loại giảm giá</label>
                                    <div class="p-2 bg-light rounded">
                                        @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                            <span class="badge bg-info">Phần trăm</span>
                                        @else
                                            <span class="badge bg-warning">Tiền mặt</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Giá trị giảm</label>
                                    <div class="p-2 bg-light rounded">
                                        @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                            <strong class="text-primary">{{ $khuyenMai->gia_tri_giam }}%</strong>
                                        @else
                                            <strong class="text-primary">{{ number_format($khuyenMai->gia_tri_giam) }}đ</strong>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Giảm tối đa</label>
                                    <div class="p-2 bg-light rounded">
                                        @if($khuyenMai->giam_toi_da)
                                            {{ number_format($khuyenMai->giam_toi_da) }}đ
                                        @else
                                            Không giới hạn
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Áp dụng cho</label>
                                    <div class="p-2 bg-light rounded">
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
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Đơn tối thiểu</label>
                                    <div class="p-2 bg-light rounded">
                                        @if($khuyenMai->don_toi_thieu)
                                            {{ number_format($khuyenMai->don_toi_thieu) }}đ
                                        @else
                                            Không yêu cầu
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Ngày bắt đầu</label>
                                    <div class="p-2 bg-light rounded">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $khuyenMai->ngay_bat_dau->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Ngày kết thúc</label>
                                    <div class="p-2 bg-light rounded">
                                        <i class="ti ti-calendar-x me-1"></i>
                                        {{ $khuyenMai->ngay_ket_thuc->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Số lần sử dụng tối đa</label>
                                    <div class="p-2 bg-light rounded">
                                        @if($khuyenMai->so_lan_su_dung_toi_da)
                                            {{ number_format($khuyenMai->so_lan_su_dung_toi_da) }} lần
                                        @else
                                            Không giới hạn
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Trạng thái</label>
                                    <div class="p-2 bg-light rounded">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chi nhánh áp dụng -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="ti ti-building me-2"></i>
                                Chi nhánh áp dụng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($khuyenMai->chiNhanhs as $cn)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3 {{ $cn->id == $chiNhanh->id ? 'bg-primary text-white' : 'bg-light' }}">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-building-store fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ $cn->ten_chi_nhanh }}</h6>
                                                    <small class="{{ $cn->id == $chiNhanh->id ? 'text-white-50' : 'text-muted' }}">
                                                        {{ $cn->dia_chi }}
                                                    </small>
                                                    @if($cn->id == $chiNhanh->id)
                                                        <div class="mt-1">
                                                            <span class="badge bg-warning">Chi nhánh của bạn</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê sử dụng -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="ti ti-chart-bar me-2"></i>
                                Thống kê sử dụng tại {{ $chiNhanh->ten_chi_nhanh }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="bg-primary text-white rounded p-3">
                                            <i class="ti ti-tickets fs-3 mb-2"></i>
                                            <h4 class="mb-1">{{ number_format($thongKeSuDung['so_ve_da_su_dung']) }}</h4>
                                            <p class="mb-0">Vé đã sử dụng</p>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="bg-success text-white rounded p-3">
                                            <i class="ti ti-coin fs-3 mb-2"></i>
                                            <h4 class="mb-1">{{ number_format($thongKeSuDung['tong_tien_giam']) }}đ</h4>
                                            <p class="mb-0">Tổng tiền giảm</p>
                                        </div>
                                    </div>
                                    @if($khuyenMai->so_lan_su_dung_toi_da)
                                        <div class="col-12 mb-3">
                                            <div class="bg-info text-white rounded p-3">
                                                <i class="ti ti-percentage fs-3 mb-2"></i>
                                                <h4 class="mb-1">{{ $thongKeSuDung['ty_le_su_dung'] }}%</h4>
                                                <p class="mb-0">Tỷ lệ sử dụng</p>
                                            </div>
                                        </div>

                                        <!-- Progress bar -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Tiến độ sử dụng</label>
                                            <div class="progress mb-2" style="height: 10px;">
                                                <div class="progress-bar bg-primary" 
                                                     style="width: {{ $thongKeSuDung['ty_le_su_dung'] }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ number_format($thongKeSuDung['so_ve_da_su_dung']) }} / 
                                                {{ number_format($khuyenMai->so_lan_su_dung_toi_da) }} lần
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin thời gian -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">
                                <i class="ti ti-clock me-2"></i>
                                Thông tin thời gian
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $now = now();
                                $daysDiff = $now->diffInDays($khuyenMai->ngay_ket_thuc, false);
                                $hoursLeft = $now->diffInHours($khuyenMai->ngay_ket_thuc, false);
                            @endphp

                            @if($khuyenMai->ngay_bat_dau > $now)
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Chưa bắt đầu</strong><br>
                                    Khuyến mãi sẽ bắt đầu vào {{ $khuyenMai->ngay_bat_dau->format('d/m/Y H:i') }}
                                </div>
                            @elseif($khuyenMai->ngay_ket_thuc < $now)
                                <div class="alert alert-danger">
                                    <i class="ti ti-x-circle me-2"></i>
                                    <strong>Đã hết hạn</strong><br>
                                    Khuyến mãi đã kết thúc vào {{ $khuyenMai->ngay_ket_thuc->format('d/m/Y H:i') }}
                                </div>
                            @elseif($daysDiff <= 7)
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <strong>Sắp hết hạn</strong><br>
                                    @if($daysDiff > 0)
                                        Còn {{ $daysDiff }} ngày
                                    @elseif($hoursLeft > 0)
                                        Còn {{ $hoursLeft }} giờ
                                    @else
                                        Còn ít hơn 1 giờ
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <strong>Đang hoạt động</strong><br>
                                    Còn {{ $daysDiff }} ngày
                                </div>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted">
                                    <strong>Thời gian tạo:</strong> 
                                    {{ $khuyenMai->create_at ? \Carbon\Carbon::parse($khuyenMai->create_at)->format('d/m/Y H:i') : 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>
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

.alert {
    border: none;
    border-radius: 8px;
}

.progress {
    border-radius: 5px;
}

.form-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush

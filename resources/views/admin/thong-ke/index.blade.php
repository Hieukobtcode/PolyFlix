@extends('layouts.admin')

@section('title', 'Thống kê tổng quan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Thống kê tổng quan
                </h2>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i>Xuất báo cáo
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="xuatBaoCao('tong-quan')">Báo cáo tổng quan</a></li>
                        <li><a class="dropdown-item" href="#" onclick="xuatBaoCao('phim')">Báo cáo phim</a></li>
                        <li><a class="dropdown-item" href="#" onclick="xuatBaoCao('lien-he')">Báo cáo liên hệ</a></li>
                        <li><a class="dropdown-item" href="#" onclick="xuatBaoCao('khuyen-mai')">Báo cáo khuyến mãi</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc theo ngày -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.thong-ke.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="tu_ngay" class="form-label fw-semibold">Từ ngày</label>
                            <input type="date" class="form-control" id="tu_ngay" name="tu_ngay" value="{{ $tuNgay }}">
                        </div>
                        <div class="col-md-4">
                            <label for="den_ngay" class="form-label fw-semibold">Đến ngày</label>
                            <input type="date" class="form-control" id="den_ngay" name="den_ngay" value="{{ $denNgay }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Lọc dữ liệu
                            </button>
                            <a href="{{ route('admin.thong-ke.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-refresh me-1"></i>Đặt lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient rounded-circle p-3">
                                <i class="fas fa-film text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Tổng số phim</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_phim']) }}</div>
                            <div class="small text-success">
                                <i class="fas fa-play me-1"></i>{{ $tongQuan['phim_dang_chieu'] }} đang chiếu
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient rounded-circle p-3">
                                <i class="fas fa-building text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Chi nhánh & Rạp</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_chi_nhanh']) }} / {{ number_format($tongQuan['tong_rap']) }}</div>
                            <div class="small text-success">
                                <i class="fas fa-check me-1"></i>{{ $tongQuan['rap_hoat_dong'] }} rạp hoạt động
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient rounded-circle p-3">
                                <i class="fas fa-envelope text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Liên hệ</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_lien_he']) }}</div>
                            <div class="small text-warning">
                                <i class="fas fa-clock me-1"></i>{{ $tongQuan['lien_he_chua_xu_ly'] }} chưa xử lý
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-gradient rounded-circle p-3">
                                <i class="fas fa-tags text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Khuyến mãi</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_khuyen_mai']) }}</div>
                            <div class="small text-info">
                                <i class="fas fa-check me-1"></i>{{ $tongQuan['khuyen_mai_hoat_dong'] }} hoạt động
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê doanh thu -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="fas fa-ticket-alt text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small opacity-75">Doanh thu vé</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['doanh_thu_ve']) }}đ</div>
                            <div class="small opacity-75">
                                <i class="fas fa-chart-line me-1"></i>Từ {{ $tuNgay }} đến {{ $denNgay }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="fas fa-utensils text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small opacity-75">Doanh thu combo</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['doanh_thu_combo']) }}đ</div>
                            <div class="small opacity-75">
                                <i class="fas fa-chart-line me-1"></i>Từ {{ $tuNgay }} đến {{ $denNgay }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-gradient" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-20 rounded-circle p-3">
                                <i class="fas fa-coins text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small opacity-75">Tổng doanh thu</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_doanh_thu']) }}đ</div>
                            <div class="small opacity-75">
                                <i class="fas fa-chart-line me-1"></i>Từ {{ $tuNgay }} đến {{ $denNgay }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê doanh thu chi tiết -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Thống kê doanh thu chi tiết ({{ $tuNgay }} - {{ $denNgay }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <div class="h4 mb-0 text-primary">{{ number_format($tongQuan['doanh_thu_ve']) }}đ</div>
                                <div class="small text-muted">Doanh thu vé</div>
                                <div class="small text-success">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    {{ round(($tongQuan['doanh_thu_ve'] / ($tongQuan['doanh_thu_ve'] + $tongQuan['doanh_thu_combo'])) * 100, 1) }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <div class="h4 mb-0 text-success">{{ number_format($tongQuan['doanh_thu_combo']) }}đ</div>
                                <div class="small text-muted">Doanh thu combo</div>
                                <div class="small text-info">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    {{ round(($tongQuan['doanh_thu_combo'] / ($tongQuan['doanh_thu_ve'] + $tongQuan['doanh_thu_combo'])) * 100, 1) }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <div class="h4 mb-0 text-warning">{{ number_format($tongQuan['tong_doanh_thu'] / count($thongKeTheoNgay)) }}đ</div>
                                <div class="small text-muted">Doanh thu TB/ngày</div>
                                <div class="small text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ count($thongKeTheoNgay) }} ngày
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="h4 mb-0 text-danger">{{ number_format(max(array_column($thongKeTheoNgay, 'doanh_thu'))) }}đ</div>
                            <div class="small text-muted">Doanh thu cao nhất</div>
                            <div class="small text-success">
                                <i class="fas fa-star me-1"></i>Ngày {{ $thongKeTheoNgay[array_search(max(array_column($thongKeTheoNgay, 'doanh_thu')), array_column($thongKeTheoNgay, 'doanh_thu'))]['ngay'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ và thống kê chi tiết -->
    <div class="row">
        <!-- Biểu đồ theo ngày -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Biểu đồ thống kê theo thời gian ({{ $tuNgay }} - {{ $denNgay }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTheoNgay" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Bảng doanh thu theo ngày -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Doanh thu theo ngày</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_reverse($thongKeTheoNgay) as $item)
                                <tr>
                                    <td>{{ $item['ngay'] }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $item['doanh_thu'] > 5000000 ? 'success' : ($item['doanh_thu'] > 2000000 ? 'warning' : 'secondary') }}">
                                            {{ number_format($item['doanh_thu']) }}đ
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê liên hệ và phân tích -->
    <div class="row mb-4">
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Trạng thái liên hệ</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartLienHe" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Phân tích doanh thu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Xu hướng doanh thu</h6>
                            @php
                                $doanhThuTrungBinh = array_sum(array_column($thongKeTheoNgay, 'doanh_thu')) / count($thongKeTheoNgay);
                                $ngayTotNhat = 0;
                                $ngayXauNhat = 0;
                                foreach($thongKeTheoNgay as $index => $item) {
                                    if($item['doanh_thu'] > $thongKeTheoNgay[$ngayTotNhat]['doanh_thu']) $ngayTotNhat = $index;
                                    if($item['doanh_thu'] < $thongKeTheoNgay[$ngayXauNhat]['doanh_thu']) $ngayXauNhat = $index;
                                }
                            @endphp
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-chart-line text-success me-2"></i>
                                    <strong>Trung bình:</strong> {{ number_format($doanhThuTrungBinh) }}đ/ngày
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-arrow-up text-success me-2"></i>
                                    <strong>Cao nhất:</strong> {{ $thongKeTheoNgay[$ngayTotNhat]['ngay'] }} - {{ number_format($thongKeTheoNgay[$ngayTotNhat]['doanh_thu']) }}đ
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-arrow-down text-danger me-2"></i>
                                    <strong>Thấp nhất:</strong> {{ $thongKeTheoNgay[$ngayXauNhat]['ngay'] }} - {{ number_format($thongKeTheoNgay[$ngayXauNhat]['doanh_thu']) }}đ
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Thống kê tổng hợp</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Tổng ngày:</strong> {{ count($thongKeTheoNgay) }} ngày
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-coins text-warning me-2"></i>
                                    <strong>Tổng doanh thu:</strong> {{ number_format($tongQuan['tong_doanh_thu']) }}đ
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-percentage text-info me-2"></i>
                                    <strong>Tỷ lệ vé/combo:</strong>
                                    {{ round(($tongQuan['doanh_thu_ve'] / $tongQuan['tong_doanh_thu']) * 100, 1) }}% /
                                    {{ round(($tongQuan['doanh_thu_combo'] / $tongQuan['tong_doanh_thu']) * 100, 1) }}%
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top phim -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Top phim có nhiều suất chiếu</h5>
                    <a href="{{ route('admin.thong-ke.phim') }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
                <div class="card-body">
                    @forelse($topPhim as $index => $phim)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-semibold">{{ $phim->tieu_de }}</div>
                            <div class="small text-muted">{{ $phim->suat_chieus_count }} suất chiếu</div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $phim->trang_thai == 'dang_chieu' ? 'success' : 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $phim->trang_thai)) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Chưa có dữ liệu</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top khuyến mãi -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Top khuyến mãi được sử dụng</h5>
                    <a href="{{ route('admin.khuyen-mai.thong-ke-su-dung') }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
                <div class="card-body">
                    @forelse($topKhuyenMai as $index => $km)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-semibold">{{ $km->ten }}</div>
                            <div class="small text-muted">{{ $km->so_lan_da_su_dung }} lượt sử dụng</div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $km->trang_thai == 'hoat_dong' ? 'success' : 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $km->trang_thai)) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Chưa có dữ liệu</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê phim theo thể loại -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Phim theo thể loại</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTheLoai" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Top combo -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Top combo giá cao</h5>
                    <a href="{{ route('admin.combos.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @forelse($thongKeCombo as $index => $combo)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $combo->tieu_de }}</div>
                            <div class="small text-muted">Giá gốc: {{ number_format($combo->gia) }}đ</div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-warning text-dark">{{ number_format($combo->gia_combo) }}đ</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Chưa có dữ liệu</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ theo ngày
const ctxNgay = document.getElementById('chartTheoNgay').getContext('2d');
new Chart(ctxNgay, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($thongKeTheoNgay, 'ngay')) !!},
        datasets: [{
            label: 'Liên hệ mới',
            data: {!! json_encode(array_column($thongKeTheoNgay, 'lien_he_moi')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            yAxisID: 'y'
        }, {
            label: 'Khuyến mãi sử dụng',
            data: {!! json_encode(array_column($thongKeTheoNgay, 'khuyen_mai_su_dung')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1,
            yAxisID: 'y'
        }, {
            label: 'Doanh thu (triệu đồng)',
            data: {!! json_encode(array_column($thongKeTheoNgay, 'doanh_thu_trieu')) !!},
            borderColor: 'rgb(255, 206, 86)',
            backgroundColor: 'rgba(255, 206, 86, 0.1)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Số lượng'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Doanh thu (triệu đồng)'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});

// Biểu đồ liên hệ
const ctxLienHe = document.getElementById('chartLienHe').getContext('2d');
new Chart(ctxLienHe, {
    type: 'doughnut',
    data: {
        labels: ['Chưa xử lý', 'Đã xử lý'],
        datasets: [{
            data: [{{ $thongKeLienHe['chua_xu_ly'] }}, {{ $thongKeLienHe['da_xu_ly'] }}],
            backgroundColor: ['#ffc107', '#28a745']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Biểu đồ thể loại
const ctxTheLoai = document.getElementById('chartTheLoai').getContext('2d');
new Chart(ctxTheLoai, {
    type: 'bar',
    data: {
        labels: {!! json_encode($thongKePhimTheoTheLoai->pluck('ten')) !!},
        datasets: [{
            label: 'Số lượng phim',
            data: {!! json_encode($thongKePhimTheoTheLoai->pluck('so_luong')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Hàm xuất báo cáo
function xuatBaoCao(loai) {
    fetch(`{{ route('admin.thong-ke.xuat-bao-cao') }}?loai=${loai}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tạo file download hoặc hiển thị thông báo
                alert(data.message);
                console.log(data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xuất báo cáo');
        });
}
</script>
@endpush
@endsection

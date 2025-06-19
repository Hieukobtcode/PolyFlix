@extends('layouts.admin')

@section('page-title', 'Thống kê doanh thu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Thống kê doanh thu
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="tu_ngay" class="form-control" value="{{ $tuNgay }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="den_ngay" class="form-control" value="{{ $denNgay }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Loại thống kê</label>
                            <select name="loai_thong_ke" class="form-select">
                                <option value="ngay" {{ $loaiThongKe == 'ngay' ? 'selected' : '' }}>Theo ngày</option>
                                <option value="tuan" {{ $loaiThongKe == 'tuan' ? 'selected' : '' }}>Theo tuần</option>
                                <option value="thang" {{ $loaiThongKe == 'thang' ? 'selected' : '' }}>Theo tháng</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Chi nhánh</label>
                            <select name="chi_nhanh_id" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach($chiNhanhs as $chiNhanh)
                                    <option value="{{ $chiNhanh->id }}" {{ $chiNhanhId == $chiNhanh->id ? 'selected' : '' }}>
                                        {{ $chiNhanh->ten_chi_nhanh }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                        </div>
                    </form>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-primary">
                                        {{ number_format($doanhThuTongQuan['tong_doanh_thu']) }}đ
                                    </div>
                                    <div class="text-muted">Tổng doanh thu</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-success">
                                        {{ number_format($doanhThuTongQuan['doanh_thu_ve']) }}đ
                                    </div>
                                    <div class="text-muted">Doanh thu vé</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-warning">
                                        {{ number_format($doanhThuTongQuan['doanh_thu_combo']) }}đ
                                    </div>
                                    <div class="text-muted">Doanh thu combo</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-info bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-info">
                                        {{ number_format($doanhThuTongQuan['so_ve_ban']) }}
                                    </div>
                                    <div class="text-muted">Số vé đã bán</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ doanh thu theo thời gian -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Biểu đồ doanh thu theo {{ $loaiThongKe }}</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="doanhThuChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doanh thu theo chi nhánh -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Doanh thu theo chi nhánh</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Chi nhánh</th>
                                                    <th class="text-end">Doanh thu</th>
                                                    <th class="text-end">Số vé</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($doanhThuTheoChiNhanh as $item)
                                                <tr>
                                                    <td>{{ $item['ten_chi_nhanh'] }}</td>
                                                    <td class="text-end">{{ number_format($item['doanh_thu']) }}đ</td>
                                                    <td class="text-end">{{ number_format($item['so_ve_ban']) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doanh thu theo phim -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Top 10 phim có doanh thu cao nhất</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phim</th>
                                                    <th class="text-end">Doanh thu</th>
                                                    <th class="text-end">Suất chiếu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($doanhThuTheoPhim as $item)
                                                <tr>
                                                    <td>{{ Str::limit($item['ten_phim'], 30) }}</td>
                                                    <td class="text-end">{{ number_format($item['doanh_thu']) }}đ</td>
                                                    <td class="text-end">{{ number_format($item['so_suat_chieu']) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ doanh thu theo thời gian
    const ctx = document.getElementById('doanhThuChart').getContext('2d');
    const doanhThuData = @json($doanhThuTheoThoiGian);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: doanhThuData.map(item => item.label),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: doanhThuData.map(item => item.doanh_thu),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection

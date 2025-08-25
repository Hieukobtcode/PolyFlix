@extends('layouts.admin')

@section('page-title', 'Thống kê vé')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Thống kê vé
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
                            <button type="submit" class="btn btn-success d-block w-100">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                        </div>
                    </form>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-success">
                                        {{ number_format($veTongQuan['tong_ve_ban']) }}
                                    </div>
                                    <div class="text-muted">Tổng vé đã bán</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-info bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-info">
                                        {{ number_format($veTongQuan['tong_ve_co_the_ban']) }}
                                    </div>
                                    <div class="text-muted">Tổng vé có thể bán</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-warning">
                                        {{ $veTongQuan['ty_le_ban_ve'] }}%
                                    </div>
                                    <div class="text-muted">Tỷ lệ bán vé</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-primary">
                                        {{ number_format($veTongQuan['so_suat_chieu']) }}
                                    </div>
                                    <div class="text-muted">Số suất chiếu</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ vé theo thời gian -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Biểu đồ số vé bán theo {{ $loaiThongKe }}</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="veChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vé theo chi nhánh -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Số vé bán theo chi nhánh</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Chi nhánh</th>
                                                    <th class="text-end">Số vé bán</th>
                                                    <th class="text-end">Suất chiếu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($veTheoChiNhanh as $item)
                                                <tr>
                                                    <td>{{ $item['ten_chi_nhanh'] }}</td>
                                                    <td class="text-end">{{ number_format($item['so_ve_ban']) }}</td>
                                                    <td class="text-end">{{ number_format($item['so_suat_chieu']) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vé theo phim -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Top 10 phim bán vé nhiều nhất</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phim</th>
                                                    <th class="text-end">Số vé bán</th>
                                                    <th class="text-end">Suất chiếu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($veTheoPhim as $item)
                                                <tr>
                                                    <td>{{ Str::limit($item['ten_phim'], 30) }}</td>
                                                    <td class="text-end">{{ number_format($item['so_ve_ban']) }}</td>
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

                    <!-- Biểu đồ tròn tỷ lệ bán vé -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Tỷ lệ bán vé</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="tyLeBanVeChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Thống kê chi tiết</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h4 class="text-success">{{ number_format($veTongQuan['tong_ve_ban']) }}</h4>
                                                <small class="text-muted">Vé đã bán</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-danger">{{ number_format($veTongQuan['tong_ve_co_the_ban'] - $veTongQuan['tong_ve_ban']) }}</h4>
                                            <small class="text-muted">Vé chưa bán</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Tỷ lệ lấp đầy:</span>
                                        <strong class="text-primary">{{ $veTongQuan['ty_le_ban_ve'] }}%</strong>
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
        // Biểu đồ vé theo thời gian
        const ctx1 = document.getElementById('veChart').getContext('2d');
        const veData = @json($veTheoThoiGian);

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: veData.map(item => item.label),
                datasets: [{
                    label: 'Số vé bán',
                    data: veData.map(item => item.so_ve_ban),
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
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

        // Biểu đồ tròn tỷ lệ bán vé
        const ctx2 = document.getElementById('tyLeBanVeChart').getContext('2d');
        const veBan = {
            {
                $veTongQuan['tong_ve_ban']
            }
        };
        const veChuaBan = {
            {
                $veTongQuan['tong_ve_co_the_ban'] - $veTongQuan['tong_ve_ban']
            }
        };

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Vé đã bán', 'Vé chưa bán'],
                datasets: [{
                    data: [veBan, veChuaBan],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endsection
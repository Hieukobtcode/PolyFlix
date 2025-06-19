@extends('layouts.admin')

@section('page-title', 'Thống kê suất chiếu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-film me-2"></i>
                        Thống kê suất chiếu
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
                            <label class="form-label">Rạp</label>
                            <select name="rap_phim_id" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach($rapPhims as $rap)
                                    <option value="{{ $rap->id }}" {{ $rapPhimId == $rap->id ? 'selected' : '' }}>
                                        {{ $rap->ten_rap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-info d-block w-100">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                        </div>
                    </form>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-info bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-info">
                                        {{ number_format($suatChieuTongQuan['tong_suat_chieu']) }}
                                    </div>
                                    <div class="text-muted">Tổng suất chiếu</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-primary">
                                        {{ $suatChieuTongQuan['suat_chieu_trung_binh_ngay'] }}
                                    </div>
                                    <div class="text-muted">Suất chiếu TB/ngày</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-success">
                                        {{ number_format($suatChieuTongQuan['so_ngay']) }}
                                    </div>
                                    <div class="text-muted">Số ngày thống kê</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ suất chiếu theo ngày -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Biểu đồ suất chiếu theo ngày</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="suatChieuChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suất chiếu có lượng khách cao/thấp nhất -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        Top 5 suất chiếu có lượng khách cao nhất
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phim</th>
                                                    <th>Rạp</th>
                                                    <th>Ngày</th>
                                                    <th class="text-end">Lượng khách</th>
                                                    <th class="text-end">Tỷ lệ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($suatChieuTopBottom['cao_nhat'] as $item)
                                                <tr>
                                                    <td>{{ Str::limit($item['phim'], 20) }}</td>
                                                    <td>{{ Str::limit($item['rap'], 15) }}</td>
                                                    <td>{{ date('d/m', strtotime($item['ngay_chieu'])) }}</td>
                                                    <td class="text-end">
                                                        <span class="badge bg-success">{{ $item['luong_khach'] }}</span>
                                                    </td>
                                                    <td class="text-end">{{ $item['ty_le_lap_day'] }}%</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-arrow-down me-1"></i>
                                        Top 5 suất chiếu có lượng khách thấp nhất
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phim</th>
                                                    <th>Rạp</th>
                                                    <th>Ngày</th>
                                                    <th class="text-end">Lượng khách</th>
                                                    <th class="text-end">Tỷ lệ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($suatChieuTopBottom['thap_nhat'] as $item)
                                                <tr>
                                                    <td>{{ Str::limit($item['phim'], 20) }}</td>
                                                    <td>{{ Str::limit($item['rap'], 15) }}</td>
                                                    <td>{{ date('d/m', strtotime($item['ngay_chieu'])) }}</td>
                                                    <td class="text-end">
                                                        <span class="badge bg-warning">{{ $item['luong_khach'] }}</span>
                                                    </td>
                                                    <td class="text-end">{{ $item['ty_le_lap_day'] }}%</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng chi tiết suất chiếu theo ngày -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Chi tiết suất chiếu theo ngày</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Ngày</th>
                                                    <th class="text-end">Số suất chiếu</th>
                                                    <th class="text-end">Lượng khách ước tính</th>
                                                    <th class="text-end">Tỷ lệ lấp đầy</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($suatChieuTheoNgay as $item)
                                                <tr>
                                                    <td>{{ date('d/m/Y', strtotime($item['ngay'])) }}</td>
                                                    <td class="text-end">{{ number_format($item['so_suat_chieu']) }}</td>
                                                    <td class="text-end">{{ number_format($item['luong_khach_uoc_tinh']) }}</td>
                                                    <td class="text-end">
                                                        @php
                                                            $tyLe = $item['so_suat_chieu'] > 0 ? round(($item['luong_khach_uoc_tinh'] / ($item['so_suat_chieu'] * 50)) * 100, 1) : 0;
                                                        @endphp
                                                        <span class="badge {{ $tyLe >= 70 ? 'bg-success' : ($tyLe >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ $tyLe }}%
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
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Biểu đồ suất chiếu theo ngày
    const ctx = document.getElementById('suatChieuChart').getContext('2d');
    const suatChieuData = @json($suatChieuTheoNgay);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: suatChieuData.map(item => item.label),
            datasets: [
                {
                    label: 'Số suất chiếu',
                    data: suatChieuData.map(item => item.so_suat_chieu),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.1,
                    yAxisID: 'y'
                },
                {
                    label: 'Lượng khách ước tính',
                    data: suatChieuData.map(item => item.luong_khach_uoc_tinh),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Ngày'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Số suất chiếu'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Lượng khách'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
});
</script>
@endsection

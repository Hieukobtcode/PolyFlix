@extends('layouts.admin')

@section('page-title', 'Thống kê đồ ăn & combo')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-utensils me-2"></i>
                        Thống kê đồ ăn & combo
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-warning d-block w-100">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                        </div>
                    </form>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-warning">
                                        {{ number_format($doAnComboTongQuan['tong_doanh_thu']) }}đ
                                    </div>
                                    <div class="text-muted">Tổng doanh thu</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-success">
                                        {{ number_format($doAnComboTongQuan['doanh_thu_combo']) }}đ
                                    </div>
                                    <div class="text-muted">Doanh thu combo</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-info bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-info">
                                        {{ number_format($doAnComboTongQuan['doanh_thu_do_an']) }}đ
                                    </div>
                                    <div class="text-muted">Doanh thu đồ ăn</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-primary">
                                        {{ number_format($doAnComboTongQuan['so_combo_ban'] + $doAnComboTongQuan['so_do_an_ban']) }}
                                    </div>
                                    <div class="text-muted">Tổng sản phẩm bán</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ tròn tỷ lệ doanh thu -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Tỷ lệ doanh thu combo vs đồ ăn</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="doanhThuPieChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Tỷ lệ số lượng bán</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="soLuongPieChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sản phẩm bán chạy nhất -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-trophy me-1"></i>
                                        Top 10 sản phẩm bán chạy nhất
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Loại</th>
                                                    <th class="text-end">Giá</th>
                                                    <th class="text-end">Số lượng bán</th>
                                                    <th class="text-end">Doanh thu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sanPhamBanChay as $index => $item)
                                                <tr>
                                                    <td>
                                                        @if($index < 3)
                                                            <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }}">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        @else
                                                            {{ $index + 1 }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $item['ten'] }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $item['loai'] == 'Combo' ? 'success' : 'info' }}">
                                                            {{ $item['loai'] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">{{ number_format($item['gia']) }}đ</td>
                                                    <td class="text-end">
                                                        <strong>{{ number_format($item['so_luong_ban']) }}</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong class="text-success">{{ number_format($item['doanh_thu']) }}đ</strong>
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

                    <!-- Combo bán ra theo phim -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-film me-1"></i>
                                        Top 10 phim có doanh thu combo cao nhất
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Tên phim</th>
                                                    <th class="text-end">Số combo bán</th>
                                                    <th class="text-end">Số suất chiếu</th>
                                                    <th class="text-end">Doanh thu combo</th>
                                                    <th class="text-end">TB combo/suất</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($comboTheoPhim as $item)
                                                <tr>
                                                    <td>{{ Str::limit($item['ten_phim'], 40) }}</td>
                                                    <td class="text-end">
                                                        <strong>{{ number_format($item['so_combo_ban']) }}</strong>
                                                    </td>
                                                    <td class="text-end">{{ number_format($item['so_suat_chieu']) }}</td>
                                                    <td class="text-end">
                                                        <strong class="text-success">{{ number_format($item['doanh_thu_combo']) }}đ</strong>
                                                    </td>
                                                    <td class="text-end">
                                                        @php
                                                            $tbCombo = $item['so_suat_chieu'] > 0 ? round($item['so_combo_ban'] / $item['so_suat_chieu'], 1) : 0;
                                                        @endphp
                                                        <span class="badge bg-{{ $tbCombo >= 20 ? 'success' : ($tbCombo >= 10 ? 'warning' : 'secondary') }}">
                                                            {{ $tbCombo }}
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
    // Biểu đồ tròn doanh thu
    const ctx1 = document.getElementById('doanhThuPieChart').getContext('2d');
    const doanhThuCombo = {{ $doAnComboTongQuan['doanh_thu_combo'] }};
    const doanhThuDoAn = {{ $doAnComboTongQuan['doanh_thu_do_an'] }};
    
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Combo', 'Đồ ăn'],
            datasets: [{
                data: [doanhThuCombo, doanhThuDoAn],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)'
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = doanhThuCombo + doanhThuDoAn;
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + 'đ (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ tròn số lượng
    const ctx2 = document.getElementById('soLuongPieChart').getContext('2d');
    const soCombo = {{ $doAnComboTongQuan['so_combo_ban'] }};
    const soDoAn = {{ $doAnComboTongQuan['so_do_an_ban'] }};
    
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Combo', 'Đồ ăn'],
            datasets: [{
                data: [soCombo, soDoAn],
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(108, 117, 125, 1)'
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = soCombo + soDoAn;
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection

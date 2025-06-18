@extends('layouts.admin')

@section('page-title', 'Thống kê phim')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-film me-2"></i>
                        Thống kê phim
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
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="đang chiếu" {{ $trangThai == 'đang chiếu' ? 'selected' : '' }}>Đang chiếu</option>
                                <option value="sắp chiếu" {{ $trangThai == 'sắp chiếu' ? 'selected' : '' }}>Sắp chiếu</option>
                                <option value="đã kết thúc" {{ $trangThai == 'đã kết thúc' ? 'selected' : '' }}>Đã kết thúc</option>
                                <option value="bị hủy" {{ $trangThai == 'bị hủy' ? 'selected' : '' }}>Bị hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">
                                <i class="fas fa-search me-1"></i>Lọc
                            </button>
                        </div>
                    </form>

                    <!-- Thống kê tổng hợp -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-primary bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-primary">{{ number_format($thongKePhimTongQuan['tong_phim']) }}</div>
                                    <div class="text-muted">Tổng số phim</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-success">{{ number_format($thongKePhimTongQuan['dang_chieu']) }}</div>
                                    <div class="text-muted">Đang chiếu</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-warning bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-warning">{{ number_format($thongKePhimTongQuan['sap_chieu']) }}</div>
                                    <div class="text-muted">Sắp chiếu</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm text-center bg-danger bg-opacity-10">
                                <div class="card-body">
                                    <div class="h2 mb-0 fw-bold text-danger">{{ number_format($thongKePhimTongQuan['da_ket_thuc']) }}</div>
                                    <div class="text-muted">Đã kết thúc</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Biểu đồ tròn phim theo trạng thái -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Phim theo trạng thái</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="trangThaiChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">Phim theo thể loại</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="theLoaiChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phim có lượt xem cao/thấp nhất -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-trophy me-1"></i>
                                        Top 5 phim có lượt xem cao nhất
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phim</th>
                                                    <th>Trạng thái</th>
                                                    <th class="text-end">Lượt xem</th>
                                                    <th class="text-end">Suất chiếu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($phimTopBottom['cao_nhat'] as $index => $item)
                                                <tr>
                                                    <td>
                                                        @if($index < 3)
                                                            <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }} me-1">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        @endif
                                                        {{ Str::limit($item['ten_phim'], 25) }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $item['trang_thai'] == 'đang chiếu' ? 'success' : ($item['trang_thai'] == 'sắp chiếu' ? 'warning' : 'secondary') }}">
                                                            {{ $item['trang_thai'] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong class="text-success">{{ number_format($item['luot_xem']) }}</strong>
                                                    </td>
                                                    <td class="text-end">{{ number_format($item['so_suat_chieu']) }}</td>
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
                                        <i class="fas fa-chart-line-down me-1"></i>
                                        Top 5 phim có lượt xem thấp nhất
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phim</th>
                                                    <th>Trạng thái</th>
                                                    <th class="text-end">Lượt xem</th>
                                                    <th class="text-end">Suất chiếu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($phimTopBottom['thap_nhat'] as $item)
                                                <tr>
                                                    <td>{{ Str::limit($item['ten_phim'], 25) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $item['trang_thai'] == 'đang chiếu' ? 'success' : ($item['trang_thai'] == 'sắp chiếu' ? 'warning' : 'secondary') }}">
                                                            {{ $item['trang_thai'] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="text-warning">{{ number_format($item['luot_xem']) }}</span>
                                                    </td>
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

                    <!-- Thống kê theo thể loại chi tiết -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-tags me-1"></i>
                                        Thống kê phim theo thể loại
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Thể loại</th>
                                                    <th class="text-end">Số phim</th>
                                                    <th class="text-end">Tỷ lệ</th>
                                                    <th class="text-end">Biểu đồ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($thongKeTheoTheLoai as $item)
                                                <tr>
                                                    <td>{{ $item['ten_the_loai'] }}</td>
                                                    <td class="text-end">
                                                        <strong>{{ number_format($item['so_phim']) }}</strong>
                                                    </td>
                                                    <td class="text-end">{{ $item['ty_le'] }}%</td>
                                                    <td class="text-end">
                                                        <div class="progress" style="width: 100px; height: 8px;">
                                                            <div class="progress-bar bg-primary" style="width: {{ $item['ty_le'] }}%"></div>
                                                        </div>
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
    // Biểu đồ tròn trạng thái phim
    const ctx1 = document.getElementById('trangThaiChart').getContext('2d');
    const trangThaiData = @json($phimTheoTrangThai);
    
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: trangThaiData.map(item => item.trang_thai),
            datasets: [{
                data: trangThaiData.map(item => item.so_phim),
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',   // đang chiếu
                    'rgba(255, 193, 7, 0.8)',   // sắp chiếu  
                    'rgba(220, 53, 69, 0.8)',   // đã kết thúc
                    'rgba(108, 117, 125, 0.8)'  // bị hủy
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

    // Biểu đồ tròn thể loại phim
    const ctx2 = document.getElementById('theLoaiChart').getContext('2d');
    const theLoaiData = @json($thongKeTheoTheLoai->take(6));
    
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: theLoaiData.map(item => item.ten_the_loai),
            datasets: [{
                data: theLoaiData.map(item => item.so_phim),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)'
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

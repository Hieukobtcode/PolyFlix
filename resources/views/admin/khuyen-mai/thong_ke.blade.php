@extends('layouts.admin')

@section('title', 'Thống Kê Sử Dụng Khuyến Mãi')
@section('page-title', 'Quản lý Khuyến Mãi')
@section('breadcrumb', 'Thống kê sử dụng khuyến mãi')

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .table th {
        background-color: #f8f9fa;
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
    .chart-container {
        height: 300px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Thống kê tổng hợp -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stats-card bg-primary bg-opacity-10 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary text-white me-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Tổng lượt sử dụng</div>
                        <div class="h3 mb-0">{{ number_format($thongKeTongHop['tong_luot_su_dung']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card bg-success bg-opacity-10 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success text-white me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Số người dùng đã sử dụng</div>
                        <div class="h3 mb-0">{{ number_format($thongKeTongHop['so_nguoi_dung']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stats-card bg-info bg-opacity-10 p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info text-white me-3">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Khuyến mãi phổ biến nhất</div>
                        <div class="h5 mb-0">
                            @if($thongKeTongHop['khuyenMaiPhoBien']->count() > 0)
                                {{ $thongKeTongHop['khuyenMaiPhoBien'][0]->ten }}
                            @else
                                Chưa có dữ liệu
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Bộ lọc và danh sách -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">Lịch sử sử dụng khuyến mãi</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Bộ lọc -->
                    <form method="GET" action="{{ route('admin.khuyen-mai.thong-ke-su-dung') }}" class="row mb-4">
                        <div class="col-md-4 mb-2">
                            <label for="khuyen_mai_id" class="form-label">Khuyến mãi</label>
                            <select name="khuyen_mai_id" id="khuyen_mai_id" class="form-select">
                                <option value="">Tất cả khuyến mãi</option>
                                @foreach($khuyenMais as $km)
                                    <option value="{{ $km->id }}" {{ request('khuyen_mai_id') == $km->id ? 'selected' : '' }}>
                                        {{ $km->ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="start_date" class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="end_date" class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Lọc
                            </button>
                        </div>
                    </form>

                    <!-- Bảng dữ liệu -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 5%">#</th>
                                    <th scope="col" style="width: 30%">Khuyến mãi</th>
                                    <th scope="col" style="width: 25%">Người dùng</th>
                                    <th scope="col" style="width: 20%">Thời gian sử dụng</th>
                                    <th scope="col" class="text-center" style="width: 20%">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lichSuSuDung as $index => $lichSu)
                                <tr>
                                    <td class="text-center">{{ $index + $lichSuSuDung->firstItem() }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $lichSu->khuyenMai->ten ?? 'Không xác định' }}</div>
                                        <small class="text-muted">{{ $lichSu->khuyenMai->ma_khuyen_mai ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $lichSu->nguoiDung->name ?? 'Không xác định' }}</div>
                                        <small class="text-muted">{{ $lichSu->nguoiDung->email ?? '' }}</small>
                                    </td>
                                    <td>{{ $lichSu->thoi_gian_su_dung instanceof \DateTime ? $lichSu->thoi_gian_su_dung->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($lichSu->thoi_gian_su_dung)) }}</td>
                                    <td class="text-center">
                                        @if($lichSu->khuyenMai)
                                            <a href="{{ route('admin.khuyen-mai.show', $lichSu->khuyenMai->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i> Xem
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">Không có dữ liệu</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">Không có dữ liệu lịch sử sử dụng</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">Hiển thị {{ $lichSuSuDung->count() }} trong tổng số {{ $lichSuSuDung->total() }} lịch sử</small>
                        </div>
                        <div>
                            {{ $lichSuSuDung->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Khuyến mãi phổ biến -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">Khuyến mãi phổ biến nhất</h5>
                </div>
                <div class="card-body p-4">
                    @if($thongKeTongHop['khuyenMaiPhoBien']->count() > 0)
                        <div class="list-group">
                            @foreach($thongKeTongHop['khuyenMaiPhoBien'] as $khuyenMai)
                                <a href="{{ route('admin.khuyen-mai.show', $khuyenMai->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">{{ $khuyenMai->ten }}</div>
                                            <small class="text-muted">{{ $khuyenMai->ma_khuyen_mai }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $khuyenMai->so_lan_da_su_dung }} lượt</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-muted">Chưa có dữ liệu khuyến mãi</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Biểu đồ thống kê -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 fw-bold">Biểu đồ thống kê</h5>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dữ liệu mẫu cho biểu đồ - trong thực tế, bạn sẽ lấy dữ liệu từ controller
        const ctx = document.getElementById('usageChart').getContext('2d');
        const usageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($thongKeTongHop['khuyenMaiPhoBien']->take(5) as $khuyenMai)
                        '{{ Str::limit($khuyenMai->ten, 15) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Số lần sử dụng',
                    data: [
                        @foreach($thongKeTongHop['khuyenMaiPhoBien']->take(5) as $khuyenMai)
                            {{ $khuyenMai->so_lan_da_su_dung }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'Thống kê liên hệ')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-envelope me-2"></i>Thống kê liên hệ
                </h2>
                <a href="{{ route('admin.thong-ke.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại tổng quan
                </a>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.thong-ke.lien-he') }}" class="row">
                        <div class="col-md-3 mb-2">
                            <label for="start_date" class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="end_date" class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Lọc
                                </button>
                                <a href="{{ route('admin.thong-ke.lien-he') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng hợp -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-primary">{{ number_format($thongKeTheoTrangThai['chua_xu_ly'] + $thongKeTheoTrangThai['da_xu_ly']) }}</div>
                    <div class="text-muted">Tổng liên hệ</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-warning">{{ number_format($thongKeTheoTrangThai['chua_xu_ly']) }}</div>
                    <div class="text-muted">Chưa xử lý</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="h2 mb-0 fw-bold text-success">{{ number_format($thongKeTheoTrangThai['da_xu_ly']) }}</div>
                    <div class="text-muted">Đã xử lý</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ thống kê theo tháng -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Thống kê liên hệ theo tháng (6 tháng gần đây)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTheoThang" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách liên hệ -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Danh sách liên hệ chi tiết</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Thông tin liên hệ</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày gửi</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lienHes as $index => $lienHe)
                                <tr>
                                    <td>{{ $lienHes->firstItem() + $index }}</td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $lienHe->ho_ten }}</div>
                                            <div class="small text-muted">
                                                <i class="fas fa-envelope me-1"></i>{{ $lienHe->email }}
                                            </div>
                                            @if($lienHe->so_dien_thoai)
                                            <div class="small text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $lienHe->so_dien_thoai }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $lienHe->chu_de }}</div>
                                        <div class="small text-muted">{{ Str::limit($lienHe->noi_dung, 80) }}</div>
                                    </td>
                                    <td>
                                        @if($lienHe->trang_thai == 'chua_xu_ly')
                                            <span class="badge bg-warning">Chưa xử lý</span>
                                        @else
                                            <span class="badge bg-success">Đã xử lý</span>
                                        @endif
                                    </td>
                                    <td>{{ $lienHe->created_at ? $lienHe->created_at->format('d/m/Y H:i') : '---' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.lien-he.show', $lienHe->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($lienHe->trang_thai == 'chua_xu_ly')
                                            <form action="{{ route('admin.lien-he.update-status', $lienHe->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="trang_thai" value="da_xu_ly">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Đánh dấu đã xử lý">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">Không có dữ liệu liên hệ</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($lienHes->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $lienHes->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ thống kê theo tháng
const ctxThang = document.getElementById('chartTheoThang').getContext('2d');
new Chart(ctxThang, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($thongKeTheoThang, 'thang')) !!},
        datasets: [{
            label: 'Số lượng liên hệ',
            data: {!! json_encode(array_column($thongKeTheoThang, 'so_luong')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
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
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
@endsection

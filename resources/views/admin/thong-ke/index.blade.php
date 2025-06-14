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
                                <i class="fas fa-utensils text-white fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small text-muted">Combo & Đồ ăn</div>
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_combo'] + $tongQuan['tong_do_an']) }}</div>
                            <div class="small text-success">
                                <i class="fas fa-check me-1"></i>{{ $tongQuan['combo_hoat_dong'] + $tongQuan['do_an_hoat_dong'] }} hoạt động
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

    <!-- Biểu đồ và thống kê chi tiết -->
    <div class="row">
        <!-- Biểu đồ theo ngày -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Thống kê 7 ngày gần đây</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTheoNgay" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Thống kê liên hệ -->
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
            tension: 0.1
        }, {
            label: 'Khuyến mãi sử dụng',
            data: {!! json_encode(array_column($thongKeTheoNgay, 'khuyen_mai_su_dung')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1
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

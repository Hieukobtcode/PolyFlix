@extends('layouts.admin')

@section('title', 'Dashboard Thống kê')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-chart-bar me-2"></i>Dashboard Thống kê
            </h2>
            <p class="text-muted">Tổng quan về hoạt động của hệ thống</p>
        </div>
    </div>

    <!-- Bộ lọc theo ngày -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.thong-ke.dashboard') }}" class="row g-3 align-items-end">
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
                            <a href="{{ route('admin.thong-ke.dashboard') }}" class="btn btn-outline-secondary ms-2">
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
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-film fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small">Tổng số phim</div>
                            <div class="h3 mb-0 fw-bold">{{ $tongQuan['tong_phim'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small">Chi nhánh & Rạp</div>
                            <div class="h3 mb-0 fw-bold">{{ $tongQuan['tong_chi_nhanh'] ?? 0 }} / {{ $tongQuan['tong_rap'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small">Liên hệ</div>
                            <div class="h3 mb-0 fw-bold">{{ $tongQuan['tong_lien_he'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tags fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="small">Khuyến mãi</div>
                            <div class="h3 mb-0 fw-bold">{{ $tongQuan['tong_khuyen_mai'] ?? 0 }}</div>
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
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['doanh_thu_ve'] ?? 0) }}đ</div>
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
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['doanh_thu_combo'] ?? 0) }}đ</div>
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
                            <div class="h4 mb-0 fw-bold">{{ number_format($tongQuan['tong_doanh_thu'] ?? 0) }}đ</div>
                            <div class="small opacity-75">
                                <i class="fas fa-chart-line me-1"></i>Từ {{ $tuNgay }} đến {{ $denNgay }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê chi tiết -->
    <div class="row">
        <!-- Phim -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Thống kê phim</h5>
                    <a href="{{ route('admin.thong-ke.phim') }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-0 text-success">{{ $tongQuan['phim_dang_chieu'] ?? 0 }}</div>
                            <div class="small text-muted">Đang chiếu</div>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-warning">{{ $tongQuan['phim_sap_chieu'] ?? 0 }}</div>
                            <div class="small text-muted">Sắp chiếu</div>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-secondary">{{ ($tongQuan['tong_phim'] ?? 0) - ($tongQuan['phim_dang_chieu'] ?? 0) - ($tongQuan['phim_sap_chieu'] ?? 0) }}</div>
                            <div class="small text-muted">Ngừng chiếu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liên hệ -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Thống kê liên hệ</h5>
                    <a href="{{ route('admin.thong-ke.lien-he') }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-0 text-warning">{{ $thongKeLienHe['chua_xu_ly'] ?? 0 }}</div>
                            <div class="small text-muted">Chưa xử lý</div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-success">{{ $thongKeLienHe['da_xu_ly'] ?? 0 }}</div>
                            <div class="small text-muted">Đã xử lý</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách phim mới nhất -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Phim mới nhất</h5>
                </div>
                <div class="card-body">
                    @forelse($topPhim as $phim)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $phim->tieu_de ?? $phim->ten_phim }}</div>
                            <div class="small text-muted">{{ $phim->suat_chieus_count ?? 0 }} suất chiếu</div>
                            <div class="small text-muted">{{ $phim->create_at ? $phim->create_at->format('d/m/Y') : '---' }}</div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $phim->trang_thai == 'dang_chieu' ? 'success' : 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $phim->trang_thai)) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Chưa có phim nào</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Liên hệ mới nhất -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Liên hệ mới nhất</h5>
                </div>
                <div class="card-body">
                    @php
                        $lienHeMoiNhat = \App\Models\LienHe::orderBy('create_at', 'desc')->take(5)->get();
                    @endphp
                    @forelse($lienHeMoiNhat as $lienHe)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $lienHe->ho_ten }}</div>
                            <div class="small text-muted">{{ $lienHe->chu_de }}</div>
                            <div class="small text-muted">{{ $lienHe->create_at ? $lienHe->create_at->format('d/m/Y H:i') : '---' }}</div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $lienHe->trang_thai == 'chua_xu_ly' ? 'warning' : 'success' }}">
                                {{ $lienHe->trang_thai == 'chua_xu_ly' ? 'Chưa xử lý' : 'Đã xử lý' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Chưa có liên hệ nào</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê khuyến mãi -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Khuyến mãi hoạt động</h5>
                    <a href="{{ route('admin.khuyen-mai.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="h4 mb-0 text-success">{{ \App\Models\KhuyenMai::where('trang_thai', 'hoat_dong')->count() }}</div>
                            <div class="small text-muted">Đang hoạt động</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h4 mb-0 text-secondary">{{ \App\Models\KhuyenMai::where('trang_thai', 'tam_dung')->count() }}</div>
                            <div class="small text-muted">Tạm dừng</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h4 mb-0 text-info">{{ \App\Models\KhuyenMai::where('loai_giam_gia', 'phan_tram')->count() }}</div>
                            <div class="small text-muted">Giảm theo %</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h4 mb-0 text-warning">{{ \App\Models\KhuyenMai::where('loai_giam_gia', 'so_tien')->count() }}</div>
                            <div class="small text-muted">Giảm số tiền</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.phim.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-1"></i>Thêm phim mới
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.combos.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-plus me-1"></i>Thêm combo mới
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.khuyen-mai.create') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-plus me-1"></i>Thêm khuyến mãi
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.lien-he.index') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-envelope me-1"></i>Xem liên hệ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

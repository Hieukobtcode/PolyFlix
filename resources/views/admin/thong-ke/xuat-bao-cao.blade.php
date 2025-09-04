@extends('layouts.admin')

@section('title', 'Xuất báo cáo thống kê')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-file-export me-2"></i>Xuất báo cáo thống kê
            </h2>
            <p class="text-muted">Xuất báo cáo thống kê {{ $baoCao['ten_chi_nhanh'] }}</p>
        </div>
    </div>

    <!-- Form xuất báo cáo -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Cấu hình báo cáo
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.thong-ke.xuat-bao-cao') }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="tu_ngay" class="form-label fw-semibold">Từ ngày</label>
                                <input type="date" class="form-control" id="tu_ngay" name="tu_ngay" 
                                       value="{{ $baoCao['tu_ngay'] }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="den_ngay" class="form-label fw-semibold">Đến ngày</label>
                                <input type="date" class="form-control" id="den_ngay" name="den_ngay" 
                                       value="{{ $baoCao['den_ngay'] }}" required>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Chọn loại báo cáo:</h6>
                                <div class="row">
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                                <h6>Báo cáo doanh thu</h6>
                                                <p class="text-muted small">Thống kê doanh thu vé và combo</p>
                                                <button type="submit" name="report_type" value="doanh_thu" 
                                                        class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download me-1"></i>Xuất Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-ticket-alt fa-2x text-success mb-2"></i>
                                                <h6>Báo cáo bán vé</h6>
                                                <p class="text-muted small">Thống kê số lượng vé bán</p>
                                                <button type="submit" name="report_type" value="ban_ve" 
                                                        class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-download me-1"></i>Xuất Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-film fa-2x text-warning mb-2"></i>
                                                <h6>Báo cáo suất chiếu</h6>
                                                <p class="text-muted small">Thống kê hiệu quả suất chiếu</p>
                                                <button type="submit" name="report_type" value="suat_chieu" 
                                                        class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-download me-1"></i>Xuất Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(Auth::user()->vaiTro->ten_vai_tro === 'Admin Chi Nhánh')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Bạn chỉ có thể xuất báo cáo cho chi nhánh: <strong>{{ $baoCao['ten_chi_nhanh'] }}</strong>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

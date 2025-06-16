@extends('layouts.admin')

@section('title', 'Danh sách Suất chiếu')
@section('page-title', 'Chi tiết Suất chiếu')
@section('breadcrumb', 'Chi tiết Suất chiếu')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .img-thumbnail {
            border-radius: 8px;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .btn {
            border-radius: 8px;
        }

        .info-label {
            font-weight: 500;
            color: #495057;
        }

        .info-value {
            color: #212529;
        }

        .text-muted {
            font-style: italic;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chi tiết suất chiếu</h5>
                <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-light btn-sm" title="Quay lại danh sách">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Thông tin phim -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">Thông tin phim</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Poster:</span>
                                <div class="info-value col-8">
                                    @if ($suatChieu->phim->poster)
                                        <img src="{{ asset('storage/' . $suatChieu->phim->poster) }}"
                                            class="img-thumbnail rounded" style="object-fit: cover;"
                                            alt="{{ $suatChieu->phim->ten_phim }}">
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Không có ảnh</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Tên phim:</span>
                                <span class="info-value col-8">{{ $suatChieu->phim->ten_phim }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Thời lượng:</span>
                                <span class="info-value col-8">
                                    {{ $suatChieu->phim->thoi_luong ? $suatChieu->phim->thoi_luong . ' phút' : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Thể loại:</span>
                                <div class="info-value col-8">
                                    @forelse ($suatChieu->phim->theLoais as $tl)
                                        <span class="badge bg-info rounded-pill me-1">{{ $tl->ten_the_loai }}</span>
                                    @empty
                                        <span class="text-muted">Chưa có thể loại</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Phiên bản:</span>
                                <span class="info-value col-8">
                                    {{ $suatChieu->formatted_version }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin suất chiếu -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">Thông tin suất chiếu</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Ngày chiếu:</span>
                                <span class="info-value col-8">
                                    {{ \Carbon\Carbon::parse($suatChieu->ngay_chieu)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Thời gian:</span>
                                <span class="info-value col-8">
                                    {{ \Carbon\Carbon::parse($suatChieu->bat_dau)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($suatChieu->ket_thuc)->format('H:i') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Trạng thái:</span>
                                <span class="info-value col-8">
                                    <span
                                        class="badge bg-{{ $suatChieu->trang_thai == 'hoat_dong' ? 'success' : 'secondary' }} rounded-pill">
                                        {{ $suatChieu->trang_thai == 'hoat_dong' ? 'Hoạt động' : 'Tạm dừng' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin địa điểm -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-3">Thông tin địa điểm</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Phòng chiếu:</span>
                                <span class="info-value col-8">{{ $suatChieu->phongChieu->ten_phong ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Rạp:</span>
                                <span class="info-value col-8">
                                    {{ $suatChieu->phongChieu->rapPhim->ten_rap ?? 'Chưa có rạp' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <span class="info-label col-4">Chi nhánh:</span>
                                <span class="info-value col-8">
                                    {{ $suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh ?? 'Chưa có chi nhánh' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-outline-secondary"
                        title="Quay lại danh sách">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
@endsection

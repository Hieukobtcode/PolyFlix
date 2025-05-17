@extends('layouts.admin')

@section('title', 'Quản lý Loại phòng')
@section('page-title', 'Chi tiết Loại phòng')
@section('breadcrumb', 'Chi tiết Loại phòng')

@section('styles')
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.25rem 1.5rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
            border-radius: 6px;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .info-label {
            color: #6c757d;
            font-weight: 600;
            width: 150px;
        }

        .info-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.2s;
        }

        .info-item:hover {
            background-color: #e9ecef;
        }

        .info-value {
            font-weight: 500;
            color: #212529;
        }

        .info-icon {
            width: 20px;
            margin-right: 10px;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin Loại phòng
                </h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.loai-phong.edit', $loaiPhong->id) }}" class="btn btn-light btn-sm"
                        title="Chỉnh sửa">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.loai-phong.index') }}" class="btn bytn-outline-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Thông tin chi tiết -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-item d-flex">
                            <i class="fas fa-hashtag info-icon mt-1"></i>
                            <div class="info-label">ID:</div>
                            <div class="info-value">{{ $loaiPhong->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item d-flex">
                            <i class="fas fa-tag info-icon mt-1"></i>
                            <div class="info-label">Tên loại phòng:</div>
                            <div class="info-value">{{ $loaiPhong->ten_loai_phong }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item d-flex">
                            <i class="fas fa-calendar-plus info-icon mt-1"></i>
                            <div class="info-label">Ngày tạo:</div>
                            <div class="info-value">{{ $loaiPhong->create_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item d-flex">
                            <i class="fas fa-calendar-check info-icon mt-1"></i>
                            <div class="info-label">Cập nhật lần cuối:</div>
                            <div class="info-value">
                                {{ $loaiPhong->update_at ? $loaiPhong->update_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="info-item d-flex">
                            <i class="fas fa-align-left info-icon mt-1"></i>
                            <div class="info-label">Mô tả:</div>
                            <div class="info-value">{{ $loaiPhong->mo_ta ?? 'Không có mô tả' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

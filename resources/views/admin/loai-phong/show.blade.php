@extends('layouts.admin')

@section('title', 'Quản lý Loại phòng')
@section('page-title', 'Chi tiết Loại phòng')
@section('breadcrumb', 'Chi tiết Loại phòng')

@section('styles')
<style>
    .card {
        border-radius: 10px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 1em;
    }
    .btn {
        border-radius: 8px;
    }
    .table-dark {
        background-color: #343a40;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Thông tin Loại phòng</h5>
            <div class="btn-group gap-2">
                <a href="{{ route('admin.loai-phong.edit', $loaiPhong->id) }}" class="btn btn-light btn-sm" title="Chỉnh sửa">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.loai-phong.index') }}" class="btn btn-outline-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Thông tin chi tiết -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="fw-semibold text-muted" style="width: 150px;">ID:</div>
                        <div>{{ $loaiPhong->id }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="fw-semibold text-muted" style="width: 150px;">Tên loại phòng:</div>
                        <div>{{ $loaiPhong->ten_loai_phong }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="fw-semibold text-muted" style="width: 150px;">Ngày tạo:</div>
                        <div>{{ $loaiPhong->create_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <div class="fw-semibold text-muted" style="width: 150px;">Cập nhật lần cuối:</div>
                        <div>{{ $loaiPhong->update_at ? $loaiPhong->update_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="d-flex">
                        <div class="fw-semibold text-muted" style="width: 150px;">Mô tả:</div>
                        <div>{{ $loaiPhong->mo_ta ?? 'Không có mô tả' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

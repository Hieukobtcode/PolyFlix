@extends('layouts.admin')

@section('title', 'Danh sách banner')
@section('page-title', 'Chi tiết banner')
@section('breadcrumb', 'Chi tiết banner')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .btn {
            border-radius: 8px;
        }

        img.feature-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            margin-right: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chi tiết banner</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                @if($banner->hinh_anh)
                    <img src="{{ asset('storage/' . $banner->hinh_anh) }}" alt="Hình banner" class="feature-image"
                        width="300px">
                @endif



                <p class="mb-2">
                    <span class="info-label">Trạng thái:</span>
                    <span class="badge {{ $banner->trang_thai == 1 ? 'bg-success' : 'bg-secondary' }}">
                        {{ $banner->trang_thai == 1 ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                </p>

                <p class="text-muted mb-0">
                    <i class="far fa-calendar-alt me-1"></i> Ngày tạo:
                    {{ $banner->created_at ? $banner->created_at->format('d/m/Y H:i') : 'Không rõ' }}
                </p>
            </div>
        </div>
    </div>
@endsection
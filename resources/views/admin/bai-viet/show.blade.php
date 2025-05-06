@extends('layouts.admin')

@section('title', 'Chi tiết bài viết')
@section('page-title', 'Chi tiết bài viết')
@section('breadcrumb', 'Chi tiết bài viết')
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

        .article-content {
            white-space: pre-line;
        }

        img.feature-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chi tiết bài viết</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.bai-viet.edit', $baiViet->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.bai-viet.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <h4 class="fw-bold">{{ $baiViet->tieu_de }}</h4>

                <p class="text-muted mb-2">Trạng thái:
                    <span class="badge {{ $baiViet->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $baiViet->status === 'published' ? 'Xuất bản' : 'Bản nháp' }}
                    </span>
                    
                </p>

                @if($baiViet->hinh_anh)
                    <img width="200px" src="{{ asset('storage/' . $baiViet->hinh_anh) }}" alt="Hình ảnh bài viết" class="feature-image">
                @endif

                <p class="text-muted"><i class="far fa-calendar-alt me-1"></i> Ngày tạo:
                    {{ $baiViet->ngay_tao ? \Carbon\Carbon::parse($baiViet->ngay_tao)->format('d/m/Y H:i') : 'Không rõ' }}
                </p>

                <hr>

                <div class="article-content">
                    {!! nl2br(e($baiViet->noi_dung)) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

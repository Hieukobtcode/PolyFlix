@extends('layouts.admin')

@section('title', 'Chi tiết Món Ăn')
@section('page-title', 'Chi tiết Món Ăn')
@section('breadcrumb', 'Xem chi tiết')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0 fw-bold">Thông tin chi tiết món ăn</h5>
        </div>

        <div class="card-body p-4">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Tiêu đề:</strong></div>
                <div class="col-md-8">{{ $doAn->tieu_de }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Danh mục:</strong></div>
                <div class="col-md-8">{{ $doAn->danhMuc->ten ?? '---' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Giá:</strong></div>
                <div class="col-md-8">{{ number_format($doAn->gia) }} đ</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Trạng thái:</strong></div>
                <div class="col-md-8">
                    <span class="badge bg-{{ $doAn->trang_thai == 'hien' ? 'success' : 'secondary' }}">
                        {{ ucfirst($doAn->trang_thai) }}
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Mô tả / Nội dung:</strong></div>
                <div class="col-md-8">{{ $doAn->noi_dung }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4"><strong>Hình ảnh:</strong></div>
                <div class="col-md-8">
                    @if ($doAn->hinh_anh)
                        <img src="{{ asset('storage/' . $doAn->hinh_anh) }}" alt="Hình món ăn" style="max-height: 150px;">
                    @else
                        <em>Không có hình</em>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.do-an.edit', $doAn->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.do-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

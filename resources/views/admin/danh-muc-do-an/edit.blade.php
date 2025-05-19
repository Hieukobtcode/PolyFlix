@extends('layouts.admin')

@section('title', 'Cập Nhật Danh Mục Món Ăn')
@section('page-title', 'Cập Nhật Danh Mục Món Ăn')
@section('breadcrumb', 'Chỉnh sửa')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0 fw-bold">Chỉnh sửa danh mục</h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.danh-muc-do-an.update', $danhMucDoAn->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="ten" class="form-label">Tên danh mục</label>
                    <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror"
                           value="{{ old('ten', $danhMucDoAn->ten) }}" required>
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Cập nhật
                </button>
                <a href="{{ route('admin.danh-muc-do-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

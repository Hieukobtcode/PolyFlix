@extends('layouts.admin')

@section('title', 'Thêm Danh Mục Món Ăn')
@section('page-title', 'Thêm Danh Mục Món Ăn')
@section('breadcrumb', 'Thêm mới')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-bold">Thêm danh mục món ăn</h5>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.danh-muc-do-an.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="ten" class="form-label">Tên danh mục</label>
                    <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror"
                           value="{{ old('ten') }}" placeholder="Nhập tên danh mục..." required>
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Lưu danh mục
                </button>
                <a href="{{ route('admin.danh-muc-do-an.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

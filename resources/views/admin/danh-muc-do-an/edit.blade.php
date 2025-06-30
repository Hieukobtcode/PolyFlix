@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
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

                    <a href="{{ route('admin.danh-muc-do-an.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Cập nhật
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

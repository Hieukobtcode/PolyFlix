@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.loai-phong.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="ten_loai_phong" class="form-label">Tên loại phòng <span
                                class="text-danger">*</span></label>
                        <input type="text" name="ten_loai_phong"
                            class="form-control @error('ten_loai_phong') is-invalid @enderror"
                            value="{{ old('ten_loai_phong') }}" required>
                        @error('ten_loai_phong')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mo_ta" class="form-label">Mô tả</label>
                        <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" rows="4">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.loai-phong.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-success">
                            Lưu loại phòng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

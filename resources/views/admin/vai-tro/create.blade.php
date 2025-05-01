@extends('layouts.admin')

@section('title', 'Thêm vai trò')
@section('page-title', 'Thêm vai trò')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .btn {
            border-radius: 8px;
        }

        .invalid-feedback {
            font-size: 0.9em;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm vai trò mới</h5>
                <a href="{{ route('admin.vai-tro.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.vai-tro.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="ten" class="form-label fw-semibold">Tên vai trò <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded @error('ten') is-invalid @enderror"
                                    id="ten" name="ten" value="{{ old('ten') }}"
                                    placeholder="Nhập tên vai trò">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="mo_ta" class="form-label fw-semibold">Mô tả</label>
                        <textarea class="form-control rounded @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta"
                            rows="4" placeholder="Nhập mô tả vai trò">{{ old('mo_ta') }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.vai-tro.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Tự động focus vào trường tên vai trò khi tải trang
        document.getElementById('ten').focus();

        // Xác nhận trước khi hủy
        document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
            if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection

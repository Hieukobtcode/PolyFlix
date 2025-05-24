@extends('layouts.admin')

@section('title', 'Quản lý Phân quyền')
@section('page-title', 'Chỉnh sửa phân quyền')
@section('breadcrumb', 'Chỉnh sửa phân quyền')

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
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa phân quyền</h5>
                <a href="{{ route('admin.phan-quyen.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.phan-quyen.update', $phanQuyen->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="ten" class="form-label fw-semibold">Tên quyền <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten') is-invalid @enderror" id="ten"
                                    name="ten" value="{{ old('ten', $phanQuyen->ten) }}" placeholder="Nhập tên quyền">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="slug" class="form-label fw-semibold">Slug <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug', $phanQuyen->slug) }}"
                                    placeholder="vd: user.create, post.edit...">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.phan-quyen.index') }}" class="btn btn-outline-secondary"
                            title="Hủy">Hủy</a>
                        <button type="submit" class="btn btn-success" title="Cập nhật">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Tự động focus vào trường tên quyền khi tải trang
        document.getElementById('ten').focus();

        // Xác nhận trước khi hủy
        document.querySelector('.btn-outline-secondary').addEventListener('click', function(e) {
            if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection

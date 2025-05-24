@extends('layouts.admin')

@section('title', 'Quản lý Vai trò')
@section('page-title', 'Chỉnh sửa vai trò')
@section('breadcrumb', 'Chỉnh sửa vai trò')

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

        .permissions-box {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .permissions-box label {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .permissions-box label input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .select-all-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa vai trò</h5>
                <a href="{{ route('admin.vai-tro.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.vai-tro.update', $vaiTro->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="ten" class="form-label fw-semibold">Tên vai trò <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten') is-invalid @enderror" id="ten"
                                    name="ten" value="{{ old('ten', $vaiTro->ten) }}" placeholder="Nhập tên vai trò">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="mo_ta" class="form-label fw-semibold">Mô tả</label>
                        <textarea class="form-control @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4"
                            placeholder="Nhập mô tả vai trò">{{ old('mo_ta', $vaiTro->mo_ta) }}</textarea>
                        @error('mo_ta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Phân quyền đã gán</label>
                        <div class="select-all-wrapper">
                            <small class="text-muted">Tích chọn các quyền muốn gán cho vai trò</small>
                            <div>
                                <input type="checkbox" id="checkAll" class="form-check-input me-1">
                                <label for="checkAll" class="form-check-label">Chọn tất cả</label>
                            </div>
                        </div>
                        <div class="permissions-box">
                            @forelse($phanQuyens as $phanQuyen)
                                <label>
                                    <input type="checkbox" name="phan_quyen_ids[]" value="{{ $phanQuyen->id }}"
                                        {{ in_array($phanQuyen->id, $phanQuyenDaGan) ? 'checked' : '' }}>
                                    {{ $phanQuyen->ten }} <span class="text-muted ms-1">({{ $phanQuyen->slug }})</span>
                                </label>
                            @empty
                                <p class="text-muted">Không có phân quyền nào để chọn.</p>
                            @endforelse
                        </div>
                        @error('phan_quyen_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.vai-tro.index') }}" class="btn btn-outline-secondary"
                            title="Hủy">Hủy</a>
                        <button type="submit" class="btn btn-primary" title="Cập nhật">
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
        // Tự động focus vào trường tên vai trò khi tải trang
        document.getElementById('ten').focus();

        // Xác nhận trước khi hủy
        document.querySelector('.btn-outline-secondary').addEventListener('click', function(e) {
            if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                e.preventDefault();
            }
        });

        // Xử lý chọn tất cả checkbox
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="phan_quyen_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Đồng bộ trạng thái "Chọn tất cả" khi load trang
        window.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="phan_quyen_ids[]"]');
            const checkAll = document.getElementById('checkAll');
            checkAll.checked = Array.from(checkboxes).every(cb => cb.checked);
        });
    </script>
@endsection

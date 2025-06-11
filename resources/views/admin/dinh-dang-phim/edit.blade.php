    @extends('layouts.admin')

    @section('title', 'Quản lý Định dạng phim')
    @section('page-title', 'Chỉnh sửa định dạng phim')
    @section('breadcrumb', 'Chỉnh sửa định dạng phim')
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
                    <h5 class="mb-0 fw-bold">Chỉnh sửa định dạng phim</h5>
                    <a href="{{ route('admin.dinh-dang-phim.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.dinh-dang-phim.update', $dinhDangPhim->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="ten_dinh_dang" class="form-label fw-semibold">Tên định dạng <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control rounded @error('ten_dinh_dang') is-invalid @enderror"
                                        id="ten_dinh_dang" name="ten_dinh_dang"
                                        value="{{ old('ten_dinh_dang', $dinhDangPhim->ten_dinh_dang) }}"
                                        placeholder="Nhập tên định dạng">
                                    @error('ten_dinh_dang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="trang_thai" class="form-label fw-semibold">Trạng thái <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select rounded @error('trang_thai') is-invalid @enderror"
                                        id="trang_thai" name="trang_thai">
                                        <option value="hoạt động"
                                            {{ old('trang_thai', $dinhDangPhim->trang_thai) === 'hoạt động' ? 'selected' : '' }}>
                                            Hoạt động
                                        </option>
                                        <option value="không hoạt động"
                                            {{ old('trang_thai', $dinhDangPhim->trang_thai) === 'không hoạt động' ? 'selected' : '' }}>
                                            Không hoạt động
                                        </option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="mo_ta" class="form-label fw-semibold">Mô tả</label>
                            <textarea class="form-control rounded @error('mo_ta') is-invalid @enderror" id="mo_ta" name="mo_ta" rows="4"
                                placeholder="Nhập mô tả định dạng">{{ old('mo_ta', $dinhDangPhim->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.dinh-dang-phim.index') }}" class="btn btn-outline-secondary"
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
            // Tự động focus vào trường tên định dạng khi tải trang
            document.getElementById('ten_dinh_dang').focus();

            // Xác nhận trước khi hủy
            document.querySelector('.btn-outline-secondary').addEventListener('click', function(e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        </script>
    @endsection

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
                        <div class="select-all-wrapper mb-2">
                            <small class="text-muted">Tích chọn các quyền muốn gán cho vai trò</small>
                            <div>
                                <input type="checkbox" id="checkAll" class="form-check-input me-1">
                                <label for="checkAll" class="form-check-label">Chọn tất cả</label>
                            </div>
                        </div>

                        @php
                            $prefixMap = [
                                'admin.phim' => 'Quản lý phim',
                                'admin.users' => 'Quản lý người dùng',
                                'admin.dat-ve' => 'Quản lý vé',
                                'admin.vai-tro' => 'Quản lý vai trò',
                                'admin.rap' => 'Quản lý rạp',
                                'admin.chi-nhanh' => 'Quản lý chi nhánh',
                            ];

                            $prefixOrder = [
                                'admin.phim',
                                'admin.users',
                                'admin.dat-ve',
                                'admin.rap',
                                'admin.chi-nhanh',
                                'admin.vai-tro',
                            ];

                            $groupedPermissions = $phanQuyens
                                ->sortBy('slug')
                                ->groupBy(function ($item) {
                                    return explode('.', $item->slug)[0] . '.' . explode('.', $item->slug)[1];
                                })
                                ->sortBy(function ($_, $key) use ($prefixOrder) {
                                    $index = array_search($key, $prefixOrder);
                                    return $index !== false ? $index : 999;
                                });
                        @endphp

                        @forelse ($groupedPermissions as $prefix => $permissions)
                            <div class="mb-3 p-3 border rounded bg-light">
                                <h6 class="fw-bold text-primary mb-2">
                                    {{ $prefixMap[$prefix] ?? ucfirst(str_replace('.', ' ', $prefix)) }}
                                </h6>
                                <div class="row">
                                    @foreach ($permissions as $phanQuyen)
                                        <div class="col-md-6">
                                            <div class="form-check mb-1">
                                                <input class="form-check-input permission-checkbox" type="checkbox"
                                                    name="phan_quyen_ids[]" value="{{ $phanQuyen->id }}"
                                                    id="permission_{{ $phanQuyen->id }}"
                                                    {{ in_array($phanQuyen->id, old('phan_quyen_ids', $phanQuyenDaGan ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $phanQuyen->id }}">
                                                    {{ $phanQuyen->ten }} <small
                                                        class="text-muted">({{ $phanQuyen->slug }})</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Không có phân quyền nào để chọn.</p>
                        @endforelse

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

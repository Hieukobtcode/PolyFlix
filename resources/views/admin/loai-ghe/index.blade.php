@extends('layouts.admin')

@section('title', 'Quản lý Loại ghế')
@section('page-title', 'Danh sách loại ghế')
@section('breadcrumb', 'Danh sách loại ghế')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                {{-- Nút Thêm --}}
                <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 py-2 px-3 mb-3"
                    data-bs-toggle="modal" data-bs-target="#createLoaiGheModal">
                    <i class="ti ti-plus fs-5"></i> Thêm loại ghế
                </button>

                {{-- Bảng danh sách --}}
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên Loại Ghế</th>
                                <th>Màu Ghế</th>
                                <th>Mô Tả</th>
                                <th class="text-center">Phụ Thu</th>
                                <th class="text-center">Ngày Tạo</th>
                                <th class="text-center" style="width: 15%">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="loaiGheTable">
                            @forelse($loaiGhes as $index => $ghe)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $ghe->ten_loai_ghe }}</td>
                                    <td>
                                        <div
                                            style="width: 28px; height: 28px; background-color: {{ $ghe->chu_thich_mau_ghe }}; border: 1px solid #ccc; border-radius: 6px;">
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($ghe->mo_ta, 50) ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($ghe->phu_thu > 0)
                                            <span class="badge bg-info-subtle text-info fw-semibold">
                                                {{ number_format($ghe->phu_thu, 0, '', '.') }}đ
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-muted">Miễn phí</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $ghe->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button"
                                                        class="dropdown-item d-flex align-items-center gap-2 btn-edit"
                                                        data-id="{{ $ghe->id }}">
                                                        <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $loaiGhes->count() }} trong tổng số {{ $loaiGhes->total() }}
                            loại ghế</small>
                    </div>
                    <div>
                        {{ $loaiGhes->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Thêm --}}
    <div class="modal fade" id="createLoaiGheModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.loai-ghe.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Loại Ghế</label>
                        <input type="text" name="ten_loai_ghe" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Màu Ghế</label>
                        <input type="color" name="chu_thich_mau_ghe" class="form-control form-control-color"
                            value="#000000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phụ Thu</label>
                        <input type="number" name="phu_thu" class="form-control" min="0" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="mo_ta" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Chỉnh sửa --}}
    <div class="modal fade" id="editLoaiGheModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content" id="editLoaiGheForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Loại Ghế</label>
                        <input type="text" name="ten_loai_ghe" id="edit_ten_loai_ghe" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Màu Ghế</label>
                        <input type="color" name="chu_thich_mau_ghe" id="chu_thich_mau_ghe"
                            class="form-control form-control-color">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phụ Thu</label>
                        <input type="number" name="phu_thu" id="edit_phu_thu" class="form-control" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="mo_ta" id="edit_mo_ta" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = new bootstrap.Modal(document.getElementById('editLoaiGheModal'));
            const form = document.getElementById('editLoaiGheForm');

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const gheId = this.getAttribute('data-id');
                    fetch(`/admin/loai-ghe/${gheId}`)
                        .then(res => res.json())
                        .then(data => {
                            form.action = `/admin/loai-ghe/${gheId}`;
                            document.getElementById('edit_ten_loai_ghe').value = data
                                .ten_loai_ghe;
                            document.getElementById('chu_thich_mau_ghe').value = data
                                .chu_thich_mau_ghe;
                            document.getElementById('edit_phu_thu').value = data.phu_thu ?? 0;
                            document.getElementById('edit_mo_ta').value = data.mo_ta ?? '';
                            editModal.show();
                        });
                });
            });
        });
    </script>
@endsection

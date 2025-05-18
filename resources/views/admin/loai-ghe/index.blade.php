@extends('layouts.admin')

@section('title', 'Quản lý Loại ghế')
@section('page-title', 'Danh sách loại ghế')
@section('breadcrumb', 'Danh sách loại ghế')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .pagination {
            justify-content: end;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Form thêm mới -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0 fw-bold">Thêm loại ghế</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.loai-ghe.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="ten_loai_ghe" class="form-label">Tên loại ghế</label>
                                <input type="text" name="ten_loai_ghe" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="mo_ta" class="form-label">Mô tả</label>
                                <textarea name="mo_ta" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phu_thu" class="form-label">Phụ thu</label>
                                <input type="number" name="phu_thu" step="0.01" class="form-control">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus me-1"></i> Thêm
                                    mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách loại ghế -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Danh sách loại ghế</h5>
                        <div class="d-flex gap-2">
                            <input type="text" id="searchInput" class="form-control form-control-sm"
                                placeholder="Tìm kiếm...">
                            <button id="resetFilter" class="btn btn-light btn-sm"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th>Tên loại ghế</th>
                                        <th>Mô tả</th>
                                        <th>Phụ thu</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-center" style="width: 15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="loaiGheTable">
                                    @forelse($loaiGhes as $index => $ghe)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $ghe->ten_loai_ghe }}</td>
                                            <td>{{ $ghe->mo_ta ?? '-' }}</td>
                                            <td>
                                                @if ($ghe->phu_thu > 0)
                                                    <span class="badge bg-info">{{ number_format($ghe->phu_thu, 0, '', '.') }}đ</span>
                                                @else
                                                    <span class="badge bg-secondary">Miễn phí</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $ghe->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit"
                                                        data-id="{{ $ghe->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.loai-ghe.destroy', $ghe->id) }}"
                                                        method="POST" onsubmit="return confirm('Xác nhận xóa?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <small class="text-muted">Hiển thị {{ $loaiGhes->count() }} trong tổng số
                                    {{ $loaiGhes->total() }} loại ghế</small>
                            </div>
                            <div>
                                {{ $loaiGhes->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal sửa loại ghế -->
    <div class="modal fade" id="editLoaiGheModal" tabindex="-1" aria-labelledby="editLoaiGheLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editLoaiGheForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="editLoaiGheLabel">Sửa loại ghế</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên loại ghế</label>
                            <input type="text" name="ten_loai_ghe" class="form-control" id="edit_ten_loai_ghe" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" class="form-control" id="edit_mo_ta" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phụ thu</label>
                            <input type="number" name="phu_thu" class="form-control" id="edit_phu_thu" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Cập nhật</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#loaiGheTable tr');
            const editModal = new bootstrap.Modal(document.getElementById('editLoaiGheModal'));
            const form = document.getElementById('editLoaiGheForm');

            // Tìm kiếm
            document.getElementById('searchInput').addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const nameCell = row.querySelector('td:nth-child(2)');
                    if (!nameCell) return;

                    const match = nameCell.textContent.toLowerCase().includes(searchText);
                    row.style.display = match ? '' : 'none';
                    if (match) {
                        visibleCount++;
                        row.querySelector('td:first-child').textContent = visibleCount;
                    }
                });
            });

            document.getElementById('resetFilter').addEventListener('click', function() {
                document.getElementById('searchInput').value = '';
                document.getElementById('searchInput').dispatchEvent(new Event('input'));
            });

            // Mở modal sửa
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const gheId = this.getAttribute('data-id');
                    fetch(`/admin/loai-ghe/${gheId}`)
                        .then(res => res.json())
                        .then(data => {
                            form.action = `/admin/loai-ghe/${gheId}`;
                            document.getElementById('edit_ten_loai_ghe').value = data
                                .ten_loai_ghe;
                            document.getElementById('edit_mo_ta').value = data.mo_ta ?? '';
                            document.getElementById('edit_phu_thu').value = data.phu_thu ?? 0;
                            editModal.show();
                        });
                });
            });
        });
    </script>
@endsection

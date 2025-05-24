@extends('layouts.admin')

@section('title', 'Quản lý Phân quyền')
@section('page-title', 'Danh sách phân quyền')
@section('breadcrumb', 'Danh sách phân quyền')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 5px;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .pagination {
            justify-content: end;
        }

        .table-dark {
            background-color: #343a40;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách phân quyền</h5>
                <a href="{{ route('admin.phan-quyen.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm phân quyền
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Bộ lọc tìm kiếm -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Tìm kiếm theo tên quyền...">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt me-1"></i> Đặt lại
                        </button>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%">#</th>
                                <th scope="col">Tên quyền</th>
                                <th scope="col">Slug</th>
                                <th scope="col" class="text-center" style="width: 15%">Ngày tạo</th>
                                <th scope="col" class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="permissionTable">
                            @forelse($phanQuyens as $index => $phanQuyen)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $phanQuyen->ten }}</td>
                                    <td>{{ $phanQuyen->slug }}</td>
                                    <td class="text-center">{{ $phanQuyen->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.phan-quyen.show', $phanQuyen->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.phan-quyen.edit', $phanQuyen->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.phan-quyen.destroy', $phanQuyen->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa phân quyền này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $phanQuyens->count() }} trong tổng số
                            {{ $phanQuyens->total() }} phân quyền</small>
                    </div>
                    <div>
                        {{ $phanQuyens->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const resetBtn = document.getElementById('resetFilter');
            const rows = document.querySelectorAll('#permissionTable tr');

            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach((row, index) => {
                    const nameCell = row.querySelector('td:nth-child(2)');
                    if (!nameCell) return;

                    const name = nameCell.textContent.toLowerCase();

                    if (name.includes(searchText)) {
                        row.style.display = '';
                        visibleCount++;
                        row.querySelector('td:first-child').textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const emptyRow = document.getElementById('emptyRow');
                if (visibleCount === 0) {
                    if (!emptyRow) {
                        const newEmptyRow = document.createElement('tr');
                        newEmptyRow.id = 'emptyRow';
                        newEmptyRow.innerHTML = `
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp
                                </td>
                            `;
                        document.getElementById('permissionTable').appendChild(newEmptyRow);
                    }
                } else if (emptyRow) {
                    emptyRow.remove();
                }

                updateDisplayInfo(visibleCount);
            }

            function updateDisplayInfo(visibleCount) {
                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    infoText.textContent = `Hiển thị ${visibleCount} phân quyền`;
                }
            }

            searchInput.addEventListener('input', filterTable);
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterTable();
            });
        });
    </script>
@endsection

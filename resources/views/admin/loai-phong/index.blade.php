@extends('layouts.admin')

@section('title', 'Quản lý Loại phòng')
@section('page-title', 'Danh sách loại phòng')
@section('breadcrumb', 'Danh sách loại phòng')

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
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách loại phòng</h5>
                <a href="{{ route('admin.loai-phong.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm loại phòng
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên loại phòng</th>
                                <th>Mô tả</th>
                                <th class="text-center" style="width: 15%">Ngày tạo</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="roomTypeTable">
                            @forelse($loaiPhongs as $index => $loaiPhong)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $loaiPhong->ten_loai_phong }}</td>
                                    <td>{{ Str::limit($loaiPhong->mo_ta, 50) }}</td>
                                    <td class="text-center">{{ $loaiPhong->create_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.loai-phong.show', $loaiPhong->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.loai-phong.edit', $loaiPhong->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.loai-phong.destroy', $loaiPhong->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại phòng này?')">
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
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $loaiPhongs->count() }} trong tổng số
                            {{ $loaiPhongs->total() }} loại phòng</small>
                    </div>
                    <div>
                        {{ $loaiPhongs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableContainer = document.querySelector('.table-responsive');
            const filterForm = document.createElement('div');
            filterForm.className = 'row mb-4';
            filterForm.innerHTML = `
                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên loại phòng...">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <button id="resetFilter" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Đặt lại
                    </button>
                </div>
            `;
            tableContainer.parentNode.insertBefore(filterForm, tableContainer);

            const rows = document.querySelectorAll('#roomTypeTable tr');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('td')) {
                        const nameCell = row.querySelector('td:nth-child(2)');
                        if (!nameCell) return;
                        const name = nameCell.textContent.toLowerCase();
                        const nameMatch = name.includes(searchText);

                        if (nameMatch) {
                            row.style.display = '';
                            visibleCount++;
                            const indexCell = row.querySelector('td:first-child');
                            if (indexCell) indexCell.textContent = visibleCount;
                        } else {
                            row.style.display = 'none';
                        }
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
                            </td>`;
                        document.getElementById('roomTypeTable').appendChild(newEmptyRow);
                    }
                } else if (emptyRow) {
                    emptyRow.remove();
                }

                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    const totalCount = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} loại phòng`;
                }
            }

            function resetFilters() {
                document.getElementById('searchInput').value = '';
                filterTable();
            }

            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('resetFilter').addEventListener('click', resetFilters);
        });
    </script>
@endsection

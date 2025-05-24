@extends('layouts.admin')

@section('title', 'Quản lý Phòng chiếu')
@section('page-title', 'Danh sách phòng chiếu')
@section('breadcrumb', 'Danh sách phòng chiếu')

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
                <h5 class="mb-0 fw-bold">Danh sách phòng chiếu</h5>
                <a href="{{ route('admin.phong-chieu.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm phòng chiếu
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên phòng</th>
                                <th>Rạp phim</th>
                                <th>Loại phòng</th>
                                <th class="text-center" style="width: 15%">Trạng thái</th>
                                <th class="text-center" style="width: 15%">Ngày tạo</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTable">
                            @forelse($phongChieus as $index => $phong)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $phong->ten_phong }}</td>
                                    <td>{{ $phong->rapPhim->ten_rap ?? 'Không rõ' }}</td>
                                    <td>{{ $phong->loaiPhong->ten_loai ?? 'Không rõ' }}</td>
                                    <td class="text-center">
                                        @if ($phong->status === 'sẵn sàng')
                                            <span class="badge bg-success rounded-pill">Sẵn sàng</span>
                                        @elseif ($phong->status === 'không khả dụng')
                                            <span class="badge bg-danger rounded-pill">Không khả dụng</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill">Bảo trì</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $phong->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('phong-chieus.show', $phong->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('phong-chieus.edit', $phong->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('phong-chieus.destroy', $phong->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phòng chiếu này?')">
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
                                    <td colspan="7" class="text-center text-muted py-3">
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
                        <small class="text-muted">Hiển thị {{ $phongChieus->count() }} trong tổng số {{ $phongChieus->total() }} phòng chiếu</small>
                    </div>
                    <div>
                        {{ $phongChieus->links('pagination::bootstrap-5') }}
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
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm theo tên phòng...">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <select id="statusFilter" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="sẵn sàng">Sẵn sàng</option>
                        <option value="không khả dụng">Không khả dụng</option>
                        <option value="bảo trì">Bảo trì</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button id="resetFilter" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Đặt lại
                    </button>
                </div>
            `;
            tableContainer.parentNode.insertBefore(filterForm, tableContainer);

            const rows = document.querySelectorAll('#categoryTable tr');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();

                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('td')) {
                        const nameCell = row.querySelector('td:nth-child(2)');
                        const statusCell = row.querySelector('td:nth-child(5) .badge');

                        if (!nameCell || !statusCell) return;

                        const name = nameCell.textContent.toLowerCase();
                        const status = statusCell.textContent.toLowerCase().trim();

                        const nameMatch = name.includes(searchText);
                        let statusMatch = true;
                        if (statusFilter !== '') {
                            statusMatch = status === statusFilter;
                        }

                        if (nameMatch && statusMatch) {
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
                            <td colspan="7" class="text-center text-muted py-3">
                                <i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp
                            </td>`;
                        document.getElementById('categoryTable').appendChild(newEmptyRow);
                    }
                } else if (emptyRow) {
                    emptyRow.remove();
                }

                updateDisplayInfo(visibleCount);
            }

            function updateDisplayInfo(visibleCount) {
                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    const totalCount = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} phòng chiếu`;
                }
            }

            function resetFilters() {
                document.getElementById('searchInput').value = '';
                document.getElementById('statusFilter').value = '';
                filterTable();
            }

            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
            document.getElementById('resetFilter').addEventListener('click', resetFilters);
        });
    </script>
@endsection

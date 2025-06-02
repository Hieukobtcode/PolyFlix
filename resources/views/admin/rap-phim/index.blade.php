@extends('layouts.admin')

@section('title', 'Quản lý Rạp Phim')
@section('page-title', 'Danh sách Rạp Phim')
@section('breadcrumb', 'Danh sách Rạp Phim')

@section('styles')
    <style>
        .card { border-radius: 10px; }
        .table th, .table td { vertical-align: middle; }
        .badge { font-size: 0.9em; padding: 0.5em 1em; }
        .btn-group .btn { border-radius: 5px; }
        .pagination { justify-content: end; }
        .table-dark { background-color: #343a40; }
        .form-control, .form-select { border-radius: 8px; }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách rạp phim</h5>
                <div class="btn-group gap-2">
                    <a href="" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-trash me-1"></i> Thùng rác
                    </a>
                    <a href="{{ route('admin.rap-phim.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i> Thêm rạp
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Bộ lọc -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control rounded" placeholder="Tìm kiếm theo tên...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="statusFilter" class="form-select rounded">
                            <option value="">Tất cả trạng thái</option>
                            <option value="đang hoạt động">Đang hoạt động</option>
                            <option value="tạm dừng">Tạm dừng</option>
                            <option value="ngừng hoạt động">Ngừng hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100 rounded">
                            <i class="fas fa-sync-alt me-1"></i> Đặt lại
                        </button>
                    </div>
                </div>

                <!-- Bảng -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên rạp</th>
                                <th>Tên chi nhánh</th>
                                <th class="text-center" style="width: 20%">Trạng thái</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="cinemaTable">
                            @forelse($rapPhims as $index => $rap)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $rap->ten_rap }}</td>
                                    <td>{{ $rap->chiNhanh->ten_chi_nhanh ?? 'Không xác định' }}</td>
                                    <td class="text-center" data-status="{{ strtolower($rap->trang_thai) }}">
                                        <span class="badge rounded-pill {{
                                            $rap->trang_thai === 'đang hoạt động' ? 'bg-success' :
                                            ($rap->trang_thai === 'tạm dừng' ? 'bg-warning' :
                                            ($rap->trang_thai === 'ngừng hoạt động' ? 'bg-danger' : 'bg-dark'))
                                        }}">
                                            {{ ucfirst($rap->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.rap-phim.edit', $rap->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.rap-phim.destroy', $rap->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mềm rạp phim này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $rapPhims->count() }} trong tổng số {{ $rapPhims->total() }} rạp phim</small>
                    </div>
                    <div>
                        {{ $rapPhims->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('#cinemaTable tr:not(#emptyRow)');
            const tableBody = document.getElementById('cinemaTable');
            const infoText = document.querySelector('.text-muted');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const nameCell = row.querySelector('td:nth-child(2)');
                    const statusCell = row.querySelector('td:nth-child(4)');
                    if (!nameCell || !statusCell) return;

                    const name = nameCell.textContent.toLowerCase();
                    const status = statusCell.getAttribute('data-status') || '';

                    const nameMatch = name.includes(searchText);
                    const statusMatch = statusFilter === '' || status.includes(statusFilter);

                    if (nameMatch && statusMatch) {
                        row.style.display = '';
                        visibleCount++;
                        row.querySelector('td:first-child').textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const existingEmptyRow = document.getElementById('emptyFilterRow');
                if (visibleCount === 0 && !existingEmptyRow) {
                    const newEmptyRow = document.createElement('tr');
                    newEmptyRow.id = 'emptyFilterRow';
                    newEmptyRow.innerHTML = `<td colspan="5" class="text-center text-muted py-3"><i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp</td>`;
                    tableBody.appendChild(newEmptyRow);
                } else if (visibleCount > 0 && existingEmptyRow) {
                    existingEmptyRow.remove();
                }

                if (infoText) {
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số {{ $rapPhims->total() }} rạp phim`;
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

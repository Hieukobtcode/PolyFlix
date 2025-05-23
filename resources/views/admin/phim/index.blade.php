@extends('layouts.admin')

@section('title', 'Quản lý Phim')
@section('page-title', 'Danh sách Phim')
@section('breadcrumb', 'Danh sách Phim')

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
        .btn-group .btn {
            border-radius: 5px;
        }
        .pagination {
            justify-content: end;
        }
        .table-dark {
            background-color: #343a40;
        }
        .img-thumbnail {
            border-radius: 8px;
        }
        .form-control,
        .form-select {
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách phim</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.phim.trash') }}" class="btn btn-outline-light btn-sm" title="Thùng rác">
                        <i class="fas fa-trash me-1"></i> Thùng rác
                    </a>
                    <a href="{{ route('admin.phim.create') }}" class="btn btn-light btn-sm" title="Thêm phim mới">
                        <i class="fas fa-plus me-1"></i> Thêm phim
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Bộ lọc tìm kiếm -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control rounded"
                                placeholder="Tìm kiếm theo tên...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="statusFilter" class="form-select rounded">
                            <option value="">Tất cả trạng thái</option>
                            <option value="đang chiếu">Đang chiếu</option>
                            <option value="sắp chiếu">Sắp chiếu</option>
                            <option value="đã kết thúc">Đã kết thúc</option>
                            <option value="bị hủy">Bị hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100 rounded">
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
                                <th scope="col" class="text-center" style="width: 10%">Poster</th>
                                <th scope="col">Tên phim</th>
                                <th scope="col" style="width: 20%">Thể loại</th>
                                <th scope="col" class="text-center" style="width: 10%">Thời lượng</th>
                                <th scope="col" class="text-center" style="width: 15%">Ngày phát hành</th>
                                <th scope="col" class="text-center" style="width: 15%">Trạng thái</th>
                                <th scope="col" class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="movieTable">
                            @forelse($phims as $index => $phim)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        @if($phim->poster)
                                            <img src="{{ asset('storage/' . $phim->poster) }}"
                                                alt="{{ $phim->ten_phim }}" class="img-thumbnail rounded"
                                                style="width: 60px; height: 80px; object-fit: cover;">
                                        @else
                                            <span class="badge bg-secondary rounded-pill">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>{{ $phim->ten_phim }}</td>
                                    <td>
                                        @foreach($phim->theLoais as $theLoai)
                                            <span class="badge bg-info rounded-pill me-1">{{ $theLoai->ten_the_loai }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $phim->thoi_luong ? $phim->thoi_luong . ' phút' : 'N/A' }}</td>
                                    <td class="text-center">{{ $phim->ngay_phat_hanh ? $phim->ngay_phat_hanh->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="text-center" data-status="{{ strtolower($phim->trang_thai) }}">
                                        <span class="badge rounded-pill {{
                                            $phim->trang_thai === 'đang chiếu' ? 'bg-success' :
                                            ($phim->trang_thai === 'sắp chiếu' ? 'bg-warning' :
                                                ($phim->trang_thai === 'đã kết thúc' ? 'bg-secondary' :
                                                    ($phim->trang_thai === 'bị hủy' ? 'bg-danger' : 'bg-dark')))
                                        }}">
                                            {{ ucfirst($phim->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.phim.show', $phim->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.phim.edit', $phim->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.phim.destroy', $phim->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa mềm phim này? Phim sẽ được chuyển vào thùng rác.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa mềm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="8" class="text-center text-muted py-3">
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
                        <small class="text-muted">Hiển thị {{ $phims->count() }} trong tổng số {{ $phims->total() }} phim</small>
                    </div>
                    <div>
                        {{ $phims->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lấy tất cả các hàng trong bảng
            const rows = document.querySelectorAll('#movieTable tr:not(#emptyRow)');
            const tableBody = document.getElementById('movieTable');
            const infoText = document.querySelector('.text-muted');

            // Hàm lọc bảng
            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('td')) { // Kiểm tra xem có phải là hàng dữ liệu không
                        const nameCell = row.querySelector('td:nth-child(3)');
                        const statusCell = row.querySelector('td:nth-child(7)');

                        if (!nameCell || !statusCell) return;

                        const name = nameCell.textContent.toLowerCase();
                        const status = statusCell.getAttribute('data-status') || '';

                        const nameMatch = name.includes(searchText);
                        const statusMatch = statusFilter === '' || status.includes(statusFilter);

                        if (nameMatch && statusMatch) {
                            row.style.display = '';
                            visibleCount++;
                            // Cập nhật số thứ tự
                            const indexCell = row.querySelector('td:first-child');
                            if (indexCell) {
                                indexCell.textContent = visibleCount;
                            }
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });

                // Xử lý thông báo không có kết quả
                const existingEmptyRow = document.getElementById('emptyFilterRow');
                if (visibleCount === 0 && !existingEmptyRow) {
                    const newEmptyRow = document.createElement('tr');
                    newEmptyRow.id = 'emptyFilterRow';
                    newEmptyRow.innerHTML = `
                        <td colspan="8" class="text-center text-muted py-3">
                            <i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp
                        </td>
                    `;
                    tableBody.appendChild(newEmptyRow);
                } else if (visibleCount > 0 && existingEmptyRow) {
                    existingEmptyRow.remove();
                }

                // Cập nhật thông tin hiển thị
                updateDisplayInfo(visibleCount);
            }

            // Cập nhật thông tin hiển thị
            function updateDisplayInfo(visibleCount) {
                if (infoText) {
                    const totalCount = {{ $phims->total() }};
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} phim`;
                }
            }

            // Đặt lại bộ lọc
            function resetFilters() {
                document.getElementById('searchInput').value = '';
                document.getElementById('statusFilter').value = '';
                filterTable();
            }

            // Gắn sự kiện
            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
            document.getElementById('resetFilter').addEventListener('click', resetFilters);
        });
    </script>
@endsection
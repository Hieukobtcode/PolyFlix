@extends('layouts.admin')

@section('title', 'Quản lý Thể loại phim')
@section('page-title', 'Quản lý Thể loại phim')

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
                <h5 class="mb-0 fw-bold">Danh sách thể loại phim</h5>
                <a href="{{ route('admin.the-loai-phim.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm thể loại
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%">#</th>
                                <th scope="col">Tên thể loại</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col" class="text-center" style="width: 15%">Trạng thái</th>
                                <th scope="col" class="text-center" style="width: 15%">Ngày tạo</th>
                                <th scope="col" class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTable">
                            @forelse($theLoaiPhims as $index => $theLoai)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $theLoai->ten_the_loai }}</td>
                                    <td>{{ Str::limit($theLoai->mo_ta, 50) }}</td>
                                    <td class="text-center">
                                        @if ($theLoai->trang_thai === 'hoạt động')
                                            <span class="badge bg-success rounded-pill">
                                                {{ ucfirst($theLoai->trang_thai) }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">
                                                {{ ucfirst($theLoai->trang_thai) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $theLoai->create_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.the-loai-phim.show', $theLoai->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.the-loai-phim.edit', $theLoai->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.the-loai-phim.destroy', $theLoai->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa thể loại này?')">
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
                                    <td colspan="6" class="text-center text-muted py-3">
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
                        <small class="text-muted">Hiển thị {{ $theLoaiPhims->count() }} trong tổng số
                            {{ $theLoaiPhims->total() }} thể loại</small>
                    </div>
                    <div>
                        {{ $theLoaiPhims->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Thêm form tìm kiếm vào trước bảng
            const tableContainer = document.querySelector('.table-responsive');
            const filterForm = document.createElement('div');
            filterForm.className = 'row mb-4';
            filterForm.innerHTML = `
                  <div class="col-md-4 mb-2">
                      <div class="input-group">
                          <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                          <input type="text" id="searchInput" class="form-control" placeholder="Tìm kiếm theo tên...">
                      </div>
                  </div>
                  <div class="col-md-3 mb-2">
                      <select id="statusFilter" class="form-select">
                          <option value="">Tất cả trạng thái</option>
                          <option value="hoạt động">Hoạt động</option>
                          <option value="không hoạt động">Không hoạt động</option>
                      </select>
                  </div>
                  <div class="col-md-2 mb-2">
                      <button id="resetFilter" class="btn btn-outline-secondary w-100">
                          <i class="fas fa-sync-alt me-1"></i> Đặt lại
                      </button>
                  </div>
              `;
            tableContainer.parentNode.insertBefore(filterForm, tableContainer);

            // Lấy tất cả các hàng trong bảng
            const rows = document.querySelectorAll('#categoryTable tr');

            // Hàm lọc bảng
            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();

                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('td')) { // Kiểm tra xem có phải là hàng dữ liệu không
                        const nameCell = row.querySelector('td:nth-child(2)');
                        const statusCell = row.querySelector('td:nth-child(4) .badge');

                        if (!nameCell || !statusCell) return;

                        const name = nameCell.textContent.toLowerCase();
                        const status = statusCell.textContent.toLowerCase().trim();

                        const nameMatch = name.includes(searchText);

                        // Kiểm tra trạng thái chính xác
                        let statusMatch = true;
                        if (statusFilter !== '') {
                            // So sánh chính xác trạng thái
                            statusMatch = status === statusFilter;
                        }

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

                // Hiển thị thông báo nếu không có kết quả
                const emptyRow = document.getElementById('emptyRow');
                if (visibleCount === 0) {
                    if (!emptyRow) {
                        const newEmptyRow = document.createElement('tr');
                        newEmptyRow.id = 'emptyRow';
                        newEmptyRow.innerHTML = `
                                                                                                                                                <td colspan="6" class="text-center text-muted py-3">
                                                                                                                                                    <i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp
                                                                                                                                                </td>
                                                                                                                                            `;
                        document.getElementById('categoryTable').appendChild(newEmptyRow);
                    }
                } else if (emptyRow) {
                    emptyRow.remove();
                }

                // Cập nhật thông tin hiển thị
                updateDisplayInfo(visibleCount);
            }

            // Cập nhật thông tin hiển thị
            function updateDisplayInfo(visibleCount) {
                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    const totalCount = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} thể loại`;
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
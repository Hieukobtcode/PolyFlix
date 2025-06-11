@extends('layouts.admin')

@section('title', 'Quản lý Loại phòng')
@section('page-title', 'Danh sách loại phòng')
@section('breadcrumb', 'Danh sách loại phòng')

@section('styles')
    <style>
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-3px);
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
            border-bottom: none;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 12px 18px;
            transition: all 0.2s;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }

        .btn-group .btn {
            border-radius: 6px;
            margin: 0 2px;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s;
        }

        .btn-group .btn:hover {
            transform: translateY(-2px);
        }

        .badge {
            font-size: 0.85em;
            padding: 0.55em 1.2em;
            font-weight: 500;
            border-radius: 30px;
        }

        .pagination {
            justify-content: end;
        }

        .pagination .page-item .page-link {
            border-radius: 8px;
            margin: 0 3px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            box-shadow: 0 5px 12px rgba(var(--bs-primary-rgb), 0.3);
        }

        .table-dark {
            background-color: #212529;
        }

        .table-dark th {
            border-bottom: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .input-group {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            overflow: hidden;
        }

        .input-group-text {
            border: none;
            background-color: #f8f9fa;
            padding-left: 15px;
        }

        .form-control {
            border: none;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .btn {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            box-shadow: 0 5px 15px rgba(var(--bs-primary-rgb), 0.3);
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        .btn i {
            transition: all 0.3s;
        }

        .btn:hover i {
            transform: rotate(15deg);
        }

        #emptyRow td {
            padding: 30px;
            font-size: 1.1rem;
            color: #6c757d;
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
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.table-responsive');
            const filterForm = document.createElement('div');
            filterForm.className = 'row mb-4';
            filterForm.innerHTML = `
                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên loại phòng...">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <button id="resetFilter" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt me-2"></i> Đặt lại
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
                            <td colspan="5" class="text-center text-muted py-4">
                                <div class="py-3">
                                    <i class="fas fa-search fa-2x mb-3"></i>
                                    <p class="mb-0">Không tìm thấy kết quả phù hợp</p>
                                </div>
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
            document.getElementById('resetFilter').addEventListener('click', function() {
                resetFilters();
                this.classList.add('animate-spin');
                setTimeout(() => this.classList.remove('animate-spin'), 300);
            });

            // Add animation classes
            document.head.insertAdjacentHTML('beforeend', `
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    .animate-spin i {
                        animation: spin 0.5s linear;
                    }
                </style>
            `);
        });
    </script>
@endsection

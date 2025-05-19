@extends('layouts.admin')

@section('title', 'Quản lý cấp bậc thẻ')
@section('page-title', 'Danh sách cấp bậc thẻ')
@section('breadcrumb', 'Danh sách cấp bậc thẻ')

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

        .table-dark {
            background-color: #343a40;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách cấp bậc thẻ</h5>
                <a href="{{ route('admin.cap-bac-the.create') }}" class="btn btn-light btn-sm" title="Thêm cấp bậc thẻ">
                    <i class="fas fa-plus me-1"></i> Thêm cấp bậc
                </a>
            </div>
            <div class="card-body p-4">
                <!-- Thanh tìm kiếm -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control rounded"
                                placeholder="Tìm kiếm theo tên cấp bậc...">
                        </div>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%">#</th>
                                {{-- <th scope="col" class="text-center" style="width: 10%">ID</th> --}}
                                <th scope="col">Tên cấp bậc</th>
                                <th scope="col" class="text-center" style="width: 15%">Tổng chi tiêu</th>
                                <th scope="col" class="text-center" style="width: 10%">% Hoàn tiền</th>
                                <th scope="col" class="text-center" style="width: 15%">% Ưu đãi DV</th>
                                <th scope="col" class="text-center" style="width: 15%">Trạng thái</th>
                                <th scope="col" class="text-center" style="width: 20%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="membershipTable">
                            @forelse($capBacThes as $index => $capBacThe)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    {{-- <td class="text-center">{{ $capBacThe->id }}</td> --}}
                                    <td>{{ $capBacThe->ten }}</td>
                                    <td class="text-center">{{ number_format($capBacThe->tong_chi_tieu) }} đ</td>
                                    <td class="text-center">{{ $capBacThe->phan_tram_ve }}%</td>
                                    <td class="text-center">{{ $capBacThe->phan_tram_dich_vu }}%</td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $capBacThe->is_default ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $capBacThe->is_default ? 'Mặc định' : 'Thường' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.cap-bac-the.show', $capBacThe->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.cap-bac-the.edit', $capBacThe->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!$capBacThe->is_default)
                                                <form action="{{ route('admin.cap-bac-the.destroy', $capBacThe->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấp bậc thẻ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
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
                        <small class="text-muted">Hiển thị {{ $capBacThes->count() }} trong tổng số
                            {{ $capBacThes->total() }} cấp bậc thẻ</small>
                    </div>
                    <div>
                        {{ $capBacThes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tìm kiếm phía client
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#membershipTable tr:not(#emptyRow)');

            searchInput.addEventListener('input', function () {
                const searchValue = this.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    if (name.includes(searchValue)) {
                        row.style.display = '';
                        visibleCount++;
                        // Cập nhật số thứ tự
                        row.cells[0].textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Xử lý thông báo không có kết quả
                const tableBody = document.getElementById('membershipTable');
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
            });
        });
    </script>
@endsection
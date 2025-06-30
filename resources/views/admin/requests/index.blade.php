@extends('layouts.admin')

@section('title', 'Quản lý Yêu cầu quản lý')
@section('page-title', 'Danh sách Yêu cầu')
@section('breadcrumb', 'Yêu cầu quản lý')

@section('styles')
    <style>
        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .rounded-circle {
            object-fit: cover;
        }

        .dropdown .dropdown-menu {
            min-width: 120px;
        }

        .dropdown .dropdown-item {
            cursor: pointer;
        }

        .pagination {
            justify-content: end;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control rounded"
                                placeholder="Tìm theo email, chi nhánh hoặc rạp...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Email gốc</th>
                                <th>Chi nhánh / Rạp phim</th>
                                <th>Loại</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="requestTable">
                            @forelse($requests as $index => $request)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $request->original_email }}</td>
                                    <td>
                                        {{ $request->chiNhanh->ten_chi_nhanh ?? ($request->rapPhim->ten_rap ?? 'N/A') }}
                                    </td>
                                    <td>
                                        @if ($request->chi_nhanh_id)
                                            <span class="badge bg-info">Chi nhánh</span>
                                        @elseif ($request->rap_phim_id)
                                            <span class="badge bg-secondary">Rạp phim</span>
                                        @else
                                            <span class="badge bg-light text-dark">Không xác định</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if ($request->approved == 0)
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                        @elseif($request->approved == 1)
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-danger">Từ chối</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($request->approved == 0)
                                            <div class="dropdown dropstart">
                                                <a href="#" class="text-muted" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="ti ti-dots-vertical fs-6"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('admin.requests.approve', $request->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button
                                                                class="dropdown-item d-flex align-items-center gap-2 text-success">
                                                                <i class="ti ti-check fs-5"></i> Phê duyệt
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.requests.reject', $request->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                                <i class="ti ti-x fs-5"></i> Từ chối
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <div class="py-3">
                                            <i class="ti ti-folder-open fs-3 mb-3"></i>
                                            <p class="mb-0">Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $requests->count() }} trong tổng số {{ $requests->total() }}
                            yêu cầu</small>
                    </div>
                    <div>
                        {{ $requests->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#requestTable tr:not(#emptyRow)');
            const tableBody = document.getElementById('requestTable');
            const infoText = document.querySelector('.text-muted');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();

                let visibleCount = 0;

                rows.forEach((row, i) => {
                    const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const label = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const match = email.includes(searchText) || label.includes(searchText);

                    if (match) {
                        row.style.display = '';
                        visibleCount++;
                        row.querySelector('td:first-child').textContent = visibleCount;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const emptyRow = document.getElementById('emptyFilterRow');
                if (visibleCount === 0 && !emptyRow) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'emptyFilterRow';
                    newRow.innerHTML = `
                        <td colspan="7" class="text-center text-muted py-4">
                            <div class="py-3">
                                <i class="ti ti-search fs-3 mb-3"></i>
                                <p class="mb-0">Không tìm thấy kết quả phù hợp</p>
                            </div>
                        </td>`;
                    tableBody.appendChild(newRow);
                } else if (visibleCount > 0 && emptyRow) {
                    emptyRow.remove();
                }

                infoText.textContent = `Hiển thị ${visibleCount} trong tổng số {{ $requests->total() }} yêu cầu`;
            }

            document.getElementById('searchInput').addEventListener('input', filterTable);
        });
    </script>
@endsection

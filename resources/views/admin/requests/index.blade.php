@extends('layouts.admin')

@section('title', 'Quản lý Yêu cầu quản lý')
@section('page-title', 'Danh sách Yêu cầu')
@section('breadcrumb', 'Yêu cầu quản lý')

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
                <h5 class="mb-0 fw-bold">Danh sách yêu cầu quản lý</h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control rounded"
                                placeholder="Tìm theo email, tên chi nhánh hoặc rạp phim...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Email gốc</th>
                                <th>Chi nhánh / Rạp phim</th>
                                <th>Loại</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
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
                                            <div class="btn-group">
                                                <form action="{{ route('admin.requests.approve', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf

                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        title="Phê duyệt">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.requests.reject', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Từ chối">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            ---
                                        @endif

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
                        <td colspan="7" class="text-center text-muted py-3">
                            <i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp
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

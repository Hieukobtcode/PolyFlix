@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')
@section('page-title', 'Danh sách Người dùng')
@section('breadcrumb', 'Danh sách Người dùng')

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
                <h5 class="mb-0 fw-bold">Danh sách người dùng</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-sm" title="Thêm người dùng">
                        <i class="fas fa-plus me-1"></i> Thêm người dùng
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control rounded"
                                placeholder="Tìm theo tên, email hoặc SĐT...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select id="roleFilter" class="form-select rounded">
                            <option value="">Tất cả vai trò</option>
                            @foreach ($vaiTros as $vaiTro)
                                <option value="{{ strtolower($vaiTro->ten) }}">{{ $vaiTro->ten }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="statusFilter" class="form-select rounded">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Active</option>
                            <option value="block">Block</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select id="activeFilter" class="form-select rounded">
                            <option value="">Hoạt động?</option>
                            <option value="1">Online</option>
                            <option value="0">Offline</option>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100 rounded" title="Đặt lại lọc">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>



                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Vai trò</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Hoạt động</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="userTable">
                            @forelse($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->so_dien_thoai }}</td>
                                    <td>{{ optional($user->vaiTro)->ten }}</td>
                                    <td class="text-center" data-status="{{ strtolower($user->trang_thai) }}">
                                        <span
                                            class="badge rounded-pill {{ $user->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($user->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $user->hoat_dong ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->hoat_dong ? 'Online' : 'Offline' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                            {{-- <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </form> --}}
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

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $users->count() }} trong tổng số {{ $users->total() }} người
                            dùng</small>
                    </div>
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#userTable tr:not(#emptyRow)');
            const tableBody = document.getElementById('userTable');
            const infoText = document.querySelector('.text-muted');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                const activeFilter = document.getElementById('activeFilter').value;

                let visibleCount = 0;

                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const phone = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const role = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(6)').getAttribute('data-status') || '';
                    const active = row.querySelector('td:nth-child(7)').textContent.trim().toLowerCase();

                    const match =
                        (name.includes(searchText) || email.includes(searchText) || phone.includes(
                            searchText)) &&
                        (roleFilter === '' || role === roleFilter) &&
                        (statusFilter === '' || status === statusFilter) &&
                        (activeFilter === '' ||
                            (activeFilter === '1' && active === 'có') ||
                            (activeFilter === '0' && active === 'không'));

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
                <td colspan="8" class="text-center text-muted py-3">
                    <i class="fas fa-search me-1"></i> Không tìm thấy kết quả phù hợp
                </td>`;
                    tableBody.appendChild(newRow);
                } else if (visibleCount > 0 && emptyRow) {
                    emptyRow.remove();
                }

                infoText.textContent = `Hiển thị ${visibleCount} trong tổng số {{ $users->total() }} người dùng`;
            }

            function resetFilters() {
                document.getElementById('searchInput').value = '';
                document.getElementById('roleFilter').value = '';
                document.getElementById('statusFilter').value = '';
                document.getElementById('activeFilter').value = '';
                filterTable();
            }

            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('roleFilter').addEventListener('change', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
            document.getElementById('activeFilter').addEventListener('change', filterTable);
            document.getElementById('resetFilter').addEventListener('click', resetFilters);
        });
    </script>
@endsection

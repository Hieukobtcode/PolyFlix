@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Danh sách nhân viên</h5>
                    <a href="{{ route('admin.rap-phim.add-staff', $rapPhim->id) }}" class="btn btn-success">
                        <i class="ti ti-user-plus me-1"></i> Thêm nhân viên
                    </a>
                </div>

                <div class="row mb-3">
                    {{-- Search --}}
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control rounded"
                                placeholder="Tìm theo tên, email...">
                        </div>
                    </div>

                    {{-- Rạp phim
                    <div class="col-md-3 mb-2">
                        <select id="rapFilter" class="form-select rounded">
                            <option value="">Tất cả rạp phim</option>
                            @foreach ($raps as $rap)
                                <option value="{{ strtolower($rap->ten_rap) }}">{{ $rap->ten_rap }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    {{-- Trạng thái --}}
                    <div class="col-md-2 mb-2">
                        <select id="statusFilter" class="form-select rounded">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Active</option>
                            <option value="block">Block</option>
                        </select>
                    </div>

                    {{-- Hoạt động --}}
                    {{-- <div class="col-md-2 mb-2">
                        <select id="activeFilter" class="form-select rounded">
                            <option value="">Hoạt động?</option>
                            <option value="1">Online</option>
                            <option value="0">Offline</option>
                        </select>
                    </div> --}}
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-success text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Rạp phim</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody id="staffTable">
                            @forelse($staffs as $index => $staff)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $staff->avatar ? asset('storage/' . $staff->avatar) : 'https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-1.jpg' }}"
                                            alt="{{ $staff->name }}" class="rounded-circle" width="50" height="50">
                                    </td>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ optional($staff->rapPhim)->ten_rap }}</td>
                                    <td class="text-center" data-status="{{ strtolower($staff->trang_thai) }}">
                                        <span
                                            class="badge rounded-pill {{ $staff->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($staff->trang_thai) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.staff.updateStatus', $staff->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            <input type="hidden" name="trang_thai"
                                                value="{{ $staff->trang_thai === 'Active' ? 'Block' : 'Active' }}">
                                            <button type="submit"
                                                class="btn btn-sm {{ $staff->trang_thai === 'Active' ? 'btn-danger' : 'btn-success' }}">
                                                {{ $staff->trang_thai === 'Active' ? 'Khóa' : 'Mở khóa' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="8" class="text-center text-muted py-4">
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

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $staffs->count() }} trong tổng số
                            {{ $staffs->total() }} nhân viên</small>
                    </div>
                    <div>
                        {{ $staffs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#staffTable tr:not(#emptyRow)');
            const tableBody = document.getElementById('staffTable');
            const infoText = document.querySelector('.text-muted');

            function filterTable() {
                const searchText = document.getElementById('searchInput').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(6)').getAttribute('data-status') || '';

                    const match =
                        (name.includes(searchText) || email.includes(searchText)) &&
                        (statusFilter === '' || status === statusFilter);

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
                        <td colspan="8" class="text-center text-muted py-4">
                            <div class="py-3">
                                <i class="ti ti-search fs-3 mb-3"></i>
                                <p class="mb-0">Không tìm thấy kết quả phù hợp</p>
                            </div>
                        </td>`;
                    tableBody.appendChild(newRow);
                } else if (visibleCount > 0 && emptyRow) {
                    emptyRow.remove();
                }

                infoText.textContent = `Hiển thị ${visibleCount} trong tổng số {{ $staffs->total() }} nhân viên`;
            }

            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
        });
    </script>
@endsection

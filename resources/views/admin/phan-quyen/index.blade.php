@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <!-- Tìm kiếm -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên quyền...">
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th style="width: 70%">Tên quyền</th>
                                <th class="text-center" style="width: 20%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="permissionTable">
                            @forelse($phanQuyens as $index => $phanQuyen)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $phanQuyen->ten }}</div>
                                        <div class="text-muted small">({{ $phanQuyen->slug }})</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.phan-quyen.show', $phanQuyen->id) }}">
                                                        <i class="ti ti-eye fs-5"></i> Xem chi tiết
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="3" class="text-center text-muted py-4">
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

                <!-- Phân trang -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $phanQuyens->count() }} trong tổng số
                            {{ $phanQuyens->total() }} quyền</small>
                    </div>
                    <div>
                        {{ $phanQuyens->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#permissionTable tr');

            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                let visibleCount = 0;

                rows.forEach((row) => {
                    if (row.querySelector('td')) {
                        const nameCell = row.querySelector('td:nth-child(2)');
                        if (!nameCell) return;

                        const name = nameCell.textContent.toLowerCase();
                        const match = name.includes(searchText);

                        if (match) {
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
                            <td colspan="3" class="text-center text-muted py-4">
                                <div class="py-3">
                                    <i class="ti ti-search fs-3 mb-3"></i>
                                    <p class="mb-0">Không tìm thấy kết quả phù hợp</p>
                                </div>
                            </td>`;
                        document.getElementById('permissionTable').appendChild(newEmptyRow);
                    }
                } else if (emptyRow) {
                    emptyRow.remove();
                }

                const infoText = document.querySelector('.text-muted');
                if (infoText) {
                    const totalCount = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    infoText.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} quyền`;
                }
            }

            searchInput.addEventListener('input', filterTable);
        });
    </script>
@endsection

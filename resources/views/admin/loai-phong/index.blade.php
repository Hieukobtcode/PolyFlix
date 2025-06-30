@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên loại phòng...">
                    </div>
                </div>

                <a href="{{ route('admin.loai-phong.create') }}"
                    class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 py-2 px-3 mb-3">
                    <i class="ti ti-plus fs-5"></i> Thêm loại phòng
                </a>


                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">
                                    <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Tên loại phòng</h6>
                                </th>
                                <th>
                                    <h6 class="fs-4 fw-semibold mb-0">Mô tả</h6>
                                </th>
                                <th class="text-center" style="width: 15%">
                                    <h6 class="fs-4 fw-semibold mb-0">Ngày tạo</h6>
                                </th>
                                <th class="text-center" style="width: 20%">
                                    <h6 class="fs-4 fw-semibold mb-0">Thao tác</h6>
                                </th>
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
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.loai-phong.edit', $loaiPhong->id) }}">
                                                        <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.loai-phong.destroy', $loaiPhong->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại phòng này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center gap-2">
                                                            <i class="ti ti-trash fs-5 text-danger"></i> Xóa
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
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
                                    <i class="ti ti-search fs-3 mb-3"></i>
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

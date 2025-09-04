@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Auth::user()->vai_tro_id == 1)
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.the-loai-phim.create') }}"
                                class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2">
                                <i class="ti ti-plus fs-5"></i> Thêm thể loại phim
                            </a>
                        </div>
                    @endif
                </div>
                {{-- Bảng --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên thể loại</th>
                                <th>Mô tả</th>
                                <th class="text-center" style="width: 15%">Ngày tạo</th>
                                <th class="text-center" style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTable">
                            @forelse($theLoaiPhims as $index => $theLoai)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $theLoai->ten_the_loai }}</td>
                                    <td>{{ Str::limit($theLoai->mo_ta, 50) }}</td>
                                    <td class="text-center">{{ $theLoai->create_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.the-loai-phim.destroy', $theLoai->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa thể loại này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                                            <i class="ti ti-trash fs-5"></i> Xóa
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

                {{-- Phân trang --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Hiển thị {{ $theLoaiPhims->count() }} trong tổng số {{ $theLoaiPhims->total() }} thể loại
                        </small>
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
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#categoryTable tr');

            function filterTable() {
                const searchText = document.getElementById('searchInput')?.value.toLowerCase() || '';
                let visibleCount = 0;

                rows.forEach(row => {
                    if (row.querySelector('td')) {
                        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const nameMatch = name.includes(searchText);

                        if (nameMatch) {
                            row.style.display = '';
                            row.querySelector('td:first-child').textContent = ++visibleCount;
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });

                const emptyRow = document.getElementById('emptyRow');
                if (visibleCount === 0 && !emptyRow) {
                    const newEmptyRow = document.createElement('tr');
                    newEmptyRow.id = 'emptyRow';
                    newEmptyRow.innerHTML = `
                        <td colspan="5" class="text-center text-muted py-3">
                            <i class="ti ti-search me-1"></i> Không tìm thấy kết quả phù hợp
                        </td>`;
                    document.getElementById('categoryTable').appendChild(newEmptyRow);
                } else if (visibleCount > 0 && emptyRow) {
                    emptyRow.remove();
                }

                const info = document.querySelector('.text-muted');
                if (info) {
                    const totalCount = rows.length - (document.getElementById('emptyRow') ? 1 : 0);
                    info.textContent = `Hiển thị ${visibleCount} trong tổng số ${totalCount} thể loại`;
                }
            }

            document.getElementById('searchInput')?.addEventListener('input', filterTable);
        });
    </script>
@endsection

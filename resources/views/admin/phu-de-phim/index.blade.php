@extends('layouts.admin')

@section('title', 'Quản lý Phụ đề phim')
@section('page-title', 'Danh sách phụ đề phim')
@section('breadcrumb', 'Danh sách phụ đề phim')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Auth::user()->vai_tro_id == 1)
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.phu-de-phim.create') }}"
                                class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2">
                                <i class="ti ti-plus fs-5"></i> Thêm phụ đề
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
                                <th>Tên phụ đề</th>
                                <th>Mô tả</th>
                                <th class="text-center" style="width: 20%">Ngày tạo</th>
                                <th class="text-center" style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTable">
                            @forelse($phuDePhims as $index => $phuDe)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $phuDe->ten_phu_de }}</td>
                                    <td>{{ Str::limit($phuDe->mo_ta, 50) }}</td>
                                    <td class="text-center">{{ $phuDe->create_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.phu-de-phim.destroy', $phuDe->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa phụ đề này?')">
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
                        <small class="text-muted">Hiển thị {{ $phuDePhims->count() }} trong tổng số
                            {{ $phuDePhims->total() }} phụ đề</small>
                    </div>
                    <div>
                        {{ $phuDePhims->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

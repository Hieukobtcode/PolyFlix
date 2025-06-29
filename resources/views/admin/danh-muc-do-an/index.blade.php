@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <a href="{{ route('admin.danh-muc-do-an.create') }}"
                    class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 px-3 py-2 mb-4">
                    <i class="ti ti-plus fs-6"></i> Thêm danh mục
                </a>
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">
                                    <h6 class="fs-5 fw-semibold mb-0">#</h6>
                                </th>
                                <th>
                                    <h6 class="fs-5 fw-semibold mb-0">Tên danh mục</h6>
                                </th>
                                <th class="text-center" style="width: 15%">
                                    <h6 class="fs-5 fw-semibold mb-0">Thao tác</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($danhMucs as $index => $dm)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $dm->ten }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.danh-muc-do-an.edit', $dm->id) }}">
                                                        <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.danh-muc-do-an.destroy', $dm->id) }}"
                                                        method="POST" onsubmit="return confirm('Xóa danh mục này?')">
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
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Hiển thị {{ $danhMucs->count() }} trong tổng số {{ $danhMucs->total() }} danh mục
                        </small>
                    </div>
                    <div>
                        {{ $danhMucs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

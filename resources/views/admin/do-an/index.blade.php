@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <a href="{{ route('admin.do-an.create') }}"
                    class="btn btn-sm btn-primary d-inline-flex align-items-center mb-4 gap-2 px-3 py-2">
                    <i class="ti ti-plus fs-6"></i> Thêm món ăn
                </a>
                <form method="GET" action="{{ route('admin.do-an.index') }}" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                                placeholder="Tìm theo tên món ăn...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="hien" {{ request('trang_thai') == 'hien' ? 'selected' : '' }}>Hiện</option>
                            <option value="an" {{ request('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                 
                </form>

                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">
                                    <h6 class="fw-semibold mb-0">#</h6>
                                </th>
                                <th>
                                    <h6 class="fw-semibold mb-0">Tiêu đề</h6>
                                </th>
                                <th>
                                    <h6 class="fw-semibold mb-0">Danh mục</h6>
                                </th>
                                <th>
                                    <h6 class="fw-semibold mb-0">Giá</h6>
                                </th>
                                <th>
                                    <h6 class="fw-semibold mb-0">Chi nhánh</h6>
                                </th>
                                <th class="text-center">
                                    <h6 class="fw-semibold mb-0">Trạng thái</h6>
                                </th>
                                <th class="text-center" style="width: 15%">
                                    <h6 class="fw-semibold mb-0">Thao tác</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doAns as $index => $doAn)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $doAn->tieu_de }}</td>
                                    <td>{{ $doAn->danhMuc->ten ?? '---' }}</td>
                                    <td>{{ number_format($doAn->gia) }} đ</td>
                                    <td>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($doAn->chiNhanhs as $cn)
                                                <li>{{ $cn->ten_chi_nhanh }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $doAn->trang_thai == 'hien' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($doAn->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="javascript:void(0)" class="text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('admin.do-an.show', $doAn->id) }}"
                                                        class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="ti ti-eye fs-5"></i> Xem chi tiết
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.do-an.edit', $doAn->id) }}"
                                                        class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.do-an.destroy', $doAn->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa món ăn này?')">
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
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $doAns->count() }} trong tổng số {{ $doAns->total() }} món
                            ăn</small>
                    </div>
                    <div>
                        {{ $doAns->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

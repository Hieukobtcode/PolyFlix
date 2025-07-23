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
                    <table class="table align-middle text-nowrap mb-0">
    <thead class="text-center">
        <tr>
            <th style="width: 5%">#</th>
            <th>Tên món ăn</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Chi nhánh</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th style="width: 10%">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse($doAns as $index => $doAn)
            <tr class="text-center">
                <td>{{ $index + 1 }}</td>
                <td class="text-start">{{ $doAn->tieu_de }}</td>
                <td>{{ $doAn->danhMuc->ten ?? '---' }}</td>
                <td>{{ number_format($doAn->gia) }} đ</td>
                <td class="text-start">
                    <ul class="mb-0 ps-3">
                        @foreach ($doAn->chiNhanhs as $chiNhanh)
                            <li>
                                <strong>{{ $chiNhanh->ten_chi_nhanh }}</strong>
                                @php
                                    $raps = $doAn->rapPhims->where('chi_nhanh_id', $chiNhanh->id);
                                @endphp
                                @if ($raps->count())
                                    <ul class="mb-0 ps-3">
                                        @foreach ($raps as $rap)
                                            <li>{{ $rap->ten_rap }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="small text-muted">(Chưa có rạp)</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <span
                        class="badge bg-{{ $doAn->trang_thai === 'hien' ? 'success' : 'secondary' }}">
                        {{ ucfirst($doAn->trang_thai) }}
                    </span>
                </td>
                <td>{{ $doAn->created_at ? $doAn->created_at->format('d/m/Y H:i') : '---' }}</td>
                <td>
                    <div class="dropdown dropstart">
                        <a href="#" data-bs-toggle="dropdown" class="text-muted">
                            <i class="ti ti-dots-vertical fs-5"></i>
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
                                    method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa món ăn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="dropdown-item text-danger d-flex align-items-center gap-2">
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
                <td colspan="8" class="text-center text-muted py-4">
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

@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('admin.bai-viet.create') }}"
                        class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 py-2 px-3">
                        <i class="ti ti-plus fs-5"></i> Thêm bài viết
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.bai-viet.index') }}" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                                placeholder="Tìm theo tiêu đề...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản
                            </option>
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table text-nowrap align-middle mb-0">
                        <thead class="bg-gradient-dark text-white">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tiêu đề</th>
                                <th class="text-center" style="width: 15%">Hình ảnh</th>
                                <th class="text-center" style="width: 15%">Ngày tạo</th>
                                <th class="text-center" style="width: 15%">Cập nhật</th>
                                <th class="text-center" style="width: 15%">Trạng thái</th>
                                <th class="text-center" style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($baiViets as $index => $baiViet)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($baiViet->tieu_de, 50) }}</td>
                                    <td class="text-center">
                                        @if ($baiViet->hinh_anh)
                                            <img src="{{ asset('storage/' . $baiViet->hinh_anh) }}" alt=""
                                                style="width: 80px; border-radius: 6px;">
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($baiViet->ngay_tao)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if ($baiViet->ngay_cap_nhat)
                                            {{ \Carbon\Carbon::parse($baiViet->ngay_cap_nhat)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">Chưa cập nhật</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($baiViet->status === 'published')
                                            <span class="badge bg-success">Xuất bản</span>
                                        @else
                                            <span class="badge bg-secondary">Bản nháp</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <a href="#" class="text-muted" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="ti ti-dots-vertical fs-6"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="{{ route('admin.bai-viet.edit', $baiViet->id) }}"
                                                        class="dropdown-item d-flex align-items-center gap-2">
                                                        <i class="ti ti-edit fs-5"></i>Xem
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.bai-viet.destroy', $baiViet->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
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
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="ti ti-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <small class="text-muted">
                        Hiển thị {{ $baiViets->count() }} trong tổng số {{ $baiViets->total() }} bài viết
                    </small>
                    <div>
                        {{ $baiViets->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

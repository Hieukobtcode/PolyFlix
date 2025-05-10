@extends('layouts.admin')

@section('title', 'Quản lý bài viết')
@section('page-title', 'Quản lý bài viết')
@section('breadcrumb', 'Danh sách bài viết')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 5px;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .pagination {
            justify-content: end;
        }

        .table-dark {
            background-color: #343a40;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách bài viết</h5>

                <a href="{{ route('admin.bai-viet.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm bài viết
                </a>

            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.bai-viet.index') }}" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Tìm theo tiêu đề...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                    </div>

                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center" style="width: 5%">#</th>
                                <th scope="col">Tiêu đề</th>
                               
                                <th scope="col" class="text-center" style="width: 15%">Hình ảnh</th>
                                <th scope="col" class="text-center" style="width: 15%">Ngày tạo</th>
                                <th scope="col" class="text-center" style="width: 15%">ngày cập nhật</th>
                                <th scope="col" class="text-center" style="width: 15%">Trạng thái</th>
                                <th scope="col" class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="baiVietTable">
                            @forelse($baiViets as $index => $baiViet)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $baiViet->tieu_de }}</td>
                                   
                                    <td class="text-center">
                                        @if ($baiViet->hinh_anh)
                                            <img src="{{ asset('storage/' . $baiViet->hinh_anh) }}" alt="" style="width: 100px">
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">{{ \Carbon\Carbon::parse($baiViet->ngay_tao)->format('d/m/Y H:i') }}</td>
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
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.bai-viet.show', $baiViet->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.bai-viet.edit', $baiViet->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.bai-viet.destroy', $baiViet->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">Hiển thị {{ $baiViets->count() }} trong tổng số {{ $baiViets->total() }} bài viết</small>
                    </div>
                    <div>
                        {{ $baiViets->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

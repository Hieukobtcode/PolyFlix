@extends('layouts.admin')

@section('title', 'Quản lý Món Ăn')
@section('page-title', 'Quản lý Món Ăn')
@section('breadcrumb', 'Danh sách Món Ăn')

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
                <h5 class="mb-0 fw-bold">Danh sách Món Ăn</h5>
                <a href="{{ route('admin.do-an.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Thêm món ăn
                </a>
            </div>

            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.do-an.index') }}" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
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
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Ngày tạo</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doAns as $index => $doAn)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $doAn->tieu_de }}</td>
                                    <td>{{ $doAn->danhMuc->ten ?? '---' }}</td>
                                    <td>{{ number_format($doAn->gia) }} đ</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $doAn->trang_thai == 'hien' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($doAn->trang_thai) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($doAn->ngay_tao)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                             <a href="{{ route('admin.do-an.show', $doAn->id) }}"
                                                class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.do-an.edit', $doAn->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.do-an.destroy', $doAn->id) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa món ăn này?')">
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
                        <small class="text-muted">Hiển thị {{ $doAns->count() }} trong tổng số {{ $doAns->total() }} món ăn</small>
                    </div>
                    <div>
                        {{ $doAns->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

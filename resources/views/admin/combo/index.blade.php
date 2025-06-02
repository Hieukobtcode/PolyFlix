@extends('layouts.admin')

@section('title', 'Quản lý Combo')
@section('page-title', 'Quản lý Combo')
@section('breadcrumb', 'Danh sách Combo')

@section('styles')
<style>
    .card { border-radius: 10px; }
    .table th, .table td { vertical-align: middle; }
    .btn-group .btn { border-radius: 5px; }
    .badge { font-size: 0.9em; padding: 0.5em 1em; }
    .pagination { justify-content: end; }
    .table-dark { background-color: #343a40; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Danh sách Combo</h5>
            <a href="{{ route('admin.combos.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus me-1"></i> Thêm combo
            </a>
        </div>

        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.combos.index') }}" class="row mb-4">
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                            placeholder="Tìm theo tên combo...">
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
                            <th>Tên combo</th>
                            <th>Giá gốc</th>
                            <th>Giá sau giảm</th>
                            <th>Món ăn</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Ngày tạo</th>
                            <th class="text-center" style="width: 15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($combos as $index => $combo)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $combo->tieu_de }}</td>
                                <td>{{ number_format($combo->gia) }} đ</td>
                                <td>{{ number_format($combo->gia_combo) }} đ</td>
                                <td>
                                    <ul class="mb-0">
                                        @foreach($combo->doAns as $doAn)
                                            <li>{{ $doAn->tieu_de }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $combo->trang_thai === 'hien' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($combo->trang_thai) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $combo->created_at ? $combo->created_at->format('d/m/Y H:i') : '---' }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.combos.edit', $combo->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.combos.destroy', $combo->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Xóa combo này?')">
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
                                <td colspan="8" class="text-center text-muted py-3">
                                    <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small class="text-muted">Hiển thị {{ $combos->count() }} trong tổng số {{ $combos->total() }} combo</small>
                </div>
                <div>
                    {{ $combos->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

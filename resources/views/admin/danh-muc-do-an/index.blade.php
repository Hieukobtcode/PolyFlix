@extends('layouts.admin')

@section('title', 'Quản lý Danh Mục Món Ăn')
@section('page-title', 'Quản lý Danh Mục Món Ăn')
@section('breadcrumb', 'Danh sách Danh Mục')

@section('styles')
<style>
    .card { border-radius: 10px; }
    .table th, .table td { vertical-align: middle; }
    .btn-group .btn { border-radius: 5px; }
    .pagination { justify-content: end; }
    .table-dark { background-color: #343a40; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Danh sách Danh Mục Món Ăn</h5>
            <a href="{{ route('admin.danh-muc-do-an.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus me-1"></i> Thêm danh mục
            </a>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 5%">#</th>
                            <th>Tên danh mục</th>
                            <th class="text-center" style="width: 15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($danhMucs as $index => $dm)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $dm->ten }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.danh-muc-do-an.edit', $dm->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.danh-muc-do-an.destroy', $dm->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Xóa danh mục này?')">
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
                                <td colspan="3" class="text-center text-muted py-3">
                                    <i class="fas fa-folder-open me-1"></i> Không có dữ liệu
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small class="text-muted">Hiển thị {{ $danhMucs->count() }} trong tổng số {{ $danhMucs->total() }} danh mục</small>
                </div>
                <div>
                    {{ $danhMucs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

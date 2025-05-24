@extends('layouts.admin')

@section('title', 'Quản lý Phân quyền')
@section('page-title', 'Chi tiết phân quyền')
@section('breadcrumb', 'Chi tiết phân quyền')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }

        .btn {
            border-radius: 8px;
        }

        .table-dark {
            background-color: #343a40;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thông tin phân quyền</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.phan-quyen.edit', $phanQuyen->id) }}" class="btn btn-light btn-sm"
                        title="Chỉnh sửa">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.phan-quyen.index') }}" class="btn btn-outline-light btn-sm" title="Quay lại">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Thông tin chi tiết -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">ID:</div>
                            <div>{{ $phanQuyen->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Tên quyền:</div>
                            <div>{{ $phanQuyen->ten }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Slug:</div>
                            <div>{{ $phanQuyen->slug }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Ngày tạo:</div>
                            <div>{{ $phanQuyen->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="fw-semibold text-muted" style="width: 150px;">Cập nhật lần cuối:</div>
                            <div>
                                {{ $phanQuyen->updated_at ? $phanQuyen->updated_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách vai trò có quyền này -->
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Các vai trò có quyền này</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 5%">#</th>
                                    <th>Tên vai trò</th>
                                    <th class="text-center" style="width: 15%">Ngày tạo</th>
                                    <th class="text-center" style="width: 10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse($phanQuyen->vaiTros as $index => $vaiTro)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $vaiTro->ten }}</td>
                                    <td class="text-center">{{ $vaiTro->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.vai-tro.show', $vaiTro->id) }}"
                                           class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty --}}
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="fas fa-user-lock me-1"></i> Không có vai trò nào sử dụng quyền này
                                    </td>
                                </tr>
                                {{-- @endforelse --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

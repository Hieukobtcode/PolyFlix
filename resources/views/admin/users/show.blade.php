@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')
@section('page-title', 'Chi tiết Người dùng')
@section('breadcrumb', 'Chi tiết Người dùng')

@section('styles')
    <style>
        .card { border-radius: 10px; }
        .badge { font-size: 0.9em; padding: 0.5em 1em; }
        .list-group-item { padding: 0.75rem 1rem; }
        .btn { border-radius: 8px; }
        .img-thumbnail { max-height: 200px; border-radius: 8px; }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thông tin chi tiết người dùng</h5>
                <div class="btn-group gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h5 class="fw-bold">Ảnh đại diện</h5>
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="img-thumbnail">
                        @else
                            <p class="text-muted">Không có ảnh</p>
                        @endif

                        <h5 class="fw-bold mt-4">Vai trò</h5>
                        <p class="text-muted">{{ optional($user->vaiTro)->ten ?? 'Chưa gán' }}</p>

                        <h5 class="fw-bold mt-4">Trạng thái</h5>
                        <span class="badge rounded-pill {{ $user->trang_thai === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($user->trang_thai) }}
                        </span>

                        <h5 class="fw-bold mt-4">Hoạt động</h5>
                        <span class="badge rounded-pill {{ $user->hoat_dong ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->hoat_dong ? 'Có' : 'Không' }}
                        </span>
                    </div>

                    <div class="col-md-8">
                        <h3 class="fw-bold mb-4">{{ $user->name }}</h3>

                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-semibold text-muted">Email:</span>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-semibold text-muted">Email đã xác minh:</span>
                                <span>{{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'Chưa xác minh' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-semibold text-muted">Số điện thoại:</span>
                                <span>{{ $user->so_dien_thoai ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-semibold text-muted">Địa chỉ:</span>
                                <span>{{ $user->dia_chi ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-semibold text-muted">Ngày tạo:</span>
                                <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-semibold text-muted">Cập nhật lần cuối:</span>
                                <span>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

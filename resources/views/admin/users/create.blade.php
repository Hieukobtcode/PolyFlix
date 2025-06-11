@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')
@section('page-title', 'Thêm người dùng')
@section('breadcrumb', 'Thêm người dùng')

@section('styles')
    <style>
        .card { border-radius: 10px; }
        .form-control, .form-select { border-radius: 8px; }
        .form-label { font-weight: 500; }
        .btn { border-radius: 8px; }
        .invalid-feedback { font-size: 0.9em; }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm người dùng</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                <input type="text" id="so_dien_thoai" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror" value="{{ old('so_dien_thoai') }}">
                                @error('so_dien_thoai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="vai_tro_id" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select id="vai_tro_id" name="vai_tro_id" class="form-select @error('vai_tro_id') is-invalid @enderror">
                                    <option value="">-- Chọn vai trò --</option>
                                    @foreach($vaiTros as $vaiTro)
                                        <option value="{{ $vaiTro->id }}" {{ old('vai_tro_id') == $vaiTro->id ? 'selected' : '' }}>
                                            {{ $vaiTro->ten }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vai_tro_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select id="trang_thai" name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="active" {{ old('trang_thai') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="block" {{ old('trang_thai') === 'block' ? 'selected' : '' }}>Block</option>
                                </select>
                                @error('trang_thai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

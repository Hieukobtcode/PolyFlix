@extends('layouts.admin')
@section('content')
    <div class="container-fluid d-flex justify-content-center">
        <div class="card shadow-sm border-0" style="width: 100%; max-width: 1100px;">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm nhân viên mới</h5>
                <a href="{{ route('admin.chi-nhanh.show', $rapPhim->chiNhanh->id) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
            <div class="card-body px-4 py-3">
                <form action="{{ route('admin.rap-phim.store-staff') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rap_id" value="{{ $rapPhim->id }}">
                    <div class="row g-4 justify-content-center align-items-end">

                        {{-- Email --}}
                        <div class="col-md-5">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                id="email" value="{{ old('email') }}" placeholder="VD: nhanvien@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mật khẩu --}}
                        <div class="col-md-5">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" placeholder="Nhập mật khẩu">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nút lưu --}}
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-success mt-2">
                                Lưu
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('email').focus();
    </script>
@endsection

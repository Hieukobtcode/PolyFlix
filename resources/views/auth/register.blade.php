{{-- @extends('layouts.auth')

@section('content')
    <div class="container py-5">
        <h3>Đăng ký tài khoản</h3>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label>Họ tên:</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label>Mật khẩu:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Xác nhận mật khẩu:</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            @error('email')
                <div class="text-danger mb-3">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-success">Đăng ký</button>
        </form>
    </div>
@endsection --}}

@extends('layouts.auth')


@section('content')
    <div class="container py-5">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h3>Đăng nhập</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label>Mật khẩu:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            @error('email')
                <div class="text-danger mb-3">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
        <a href="{{ route('google.redirect') }}" class="btn btn-danger">Đăng nhập bằng Google</a>
        <a href="{{ route('facebook.redirect') }}" class="btn btn-primary">Đăng nhập bằng Facebook</a>
    </div>
@endsection

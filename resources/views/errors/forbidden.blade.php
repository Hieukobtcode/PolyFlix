@extends('layouts.error')
@section('title', '403 - Không có quyền truy cập')

@section('content')
    <div class="container py-5 text-center">
        <h1 class="display-1 fw-bold text-danger">403</h1>
        <p class="fs-3"><span class="text-danger">Oops!</span> Bạn không có quyền truy cập vào trang này.</p>
        <p class="lead">
            Vui lòng liên hệ quản trị viên nếu bạn nghĩ đây là nhầm lẫn.
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
@endsection

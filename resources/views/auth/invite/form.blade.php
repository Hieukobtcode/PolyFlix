@extends('layouts.auth')
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thông tin cá nhân</h2>

    <form action="{{ route('invite.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $invite->token }}">

        <div class="mb-3">
            <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
            <input type="date" name="dob" id="dob" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện</label>
            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Gửi thông tin</button>
    </form>
</div>
@endsection
@extends('layouts.auth')
@section('title', 'Hoàn tất thông tin cá nhân')
@section('styles')
    <style>
        .invite-container {
            width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invite-container h2 {
            text-align: center;
            font-size: 24px;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }

        .invite-container .form-group {
            margin-bottom: 18px;
        }

        .invite-container .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 16px;
            font-weight: 500;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #000;
        }

        .invite-container .form-control {
            width: 100%;
            height: 45px;
            padding: 8px 12px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .invite-container button {
            position: relative;
            width: 100%;
            height: 50px;
            border-radius: 5px;
            background-color: #e2d115;
            font-family: "Poppins", sans-serif;
            color: rgb(0, 0, 0);
            font-weight: bold;
            font-size: 18px;
            border: none;
            cursor: pointer;
            z-index: 1;
            overflow: hidden;
            transition: color 0.4s ease;
            margin-top: 30px;
        }

        .invite-container button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(to right, #6a11cb, #2575fc);
            z-index: -1;
            transition: left 0.4s ease;
        }

        .invite-container button:hover::before {
            left: 0;
        }

        .invite-container button:hover {
            color: #fff;
        }

        .invite-container small.text-danger {
            color: red;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }
    </style>
    @endsection
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="invite-container">
        <h2>Hoàn tất thông tin cá nhân</h2>
        <form action="{{ route('invite.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $invite->token }}">

            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="dob">Ngày sinh</label>
                <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob') }}">
                @error('dob')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit">Gửi thông tin</button>
        </form>
    </div>
@endsection

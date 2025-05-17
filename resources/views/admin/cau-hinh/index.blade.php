@extends('layouts.admin')

@section('title', 'Cập nhật cấu hình website')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.cau-hinh.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Cột trái -->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light fw-bold">Thông tin cơ bản</div>
                        <div class="card-body row g-3">

                            @php
                                $fields = [
                                    ['ten_website', 'Tên Website'],
                                    ['ten_thuong_hieu', 'Tên Thương Hiệu'],
                                    ['khau_hieu', 'Khẩu hiệu'],
                                    ['so_dien_thoai', 'Số Điện Thoại'],
                                    ['email', 'Email'],
                                    ['dia_chi', 'Trụ Sở Chính'],
                                    ['giay_phep_kinh_doanh', 'Giấy Phép Kinh Doanh'],
                                    ['thoi_gian_lam_viec', 'Thời Gian Làm Việc'],
                                    ['link_facebook', 'Link Facebook'],
                                    ['link_youtube', 'Link YouTube'],
                                    ['ban_quyen', 'Bản quyền'],
                                ];
                            @endphp

                            @foreach ($fields as $field)
                                <div class="col-md-6">
                                    <label class="form-label">{{ $field[1] }}</label>
                                    <input type="text" name="{{ $field[0] }}" class="form-control"
                                        value="{{ old($field[0], $cauHinh->{$field[0]}) }}">
                                    @error($field[0])
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Cập nhật cấu hình
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cột phải -->
                <div class="col-md-4">
                    @php
                        $images = [
                            ['logo', 'Logo Website'],
                            ['anh_chinh_sach_bao_mat', 'Ảnh Chính Sách Bảo Mật'],
                            ['anh_dieu_khoan_dich_vu', 'Ảnh Điều Khoản Dịch Vụ'],
                            ['anh_gioi_thieu', 'Ảnh Giới Thiệu'],
                        ];
                    @endphp

                    @foreach ($images as $img)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light fw-bold">{{ $img[1] }}</div>
                            <div class="card-body text-center">
                                <input type="file" name="{{ $img[0] }}" class="form-control mb-2">
                                @error($img[0])
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                @if ($cauHinh->{$img[0]})
                                    <img width="100px" src="{{ asset('storage/' . $cauHinh->{$img[0]}) }}"
                                        class="img-fluid">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>


    </div>
@endsection

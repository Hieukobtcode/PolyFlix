@extends('layouts.admin')

@section('title', 'Quản lý phòng chiếu')
@section('page-title', 'Chỉnh sửa Phòng Chiếu')
@section('breadcrumb', 'Chỉnh sửa Phòng Chiếu')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .form-label {
            font-weight: 500;
        }

        .btn {
            border-radius: 8px;
        }

        .invalid-feedback {
            font-size: 0.9em;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chỉnh sửa Phòng: {{ $phongChieu->ten_phong }}</h5>
                <a href="{{ route('admin.rap-phim.show', $phongChieu->rap_phim_id) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('admin.phong-chieu.update', $phongChieu->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4 align-items-end">
                        <div class="col-md-4">
                            <label for="ten_phong" class="form-label">Tên Phòng <span class="text-danger">*</span></label>
                            <input type="text" id="ten_phong" name="ten_phong"
                                class="form-control @error('ten_phong') is-invalid @enderror"
                                value="{{ old('ten_phong', $phongChieu->ten_phong) }}">
                            @error('ten_phong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="loai_phong_id" class="form-label">Loại Phòng <span
                                    class="text-danger">*</span></label>
                            <select name="loai_phong_id" id="loai_phong_id"
                                class="form-select @error('loai_phong_id') is-invalid @enderror">
                                <option value="">-- Chọn loại phòng --</option>
                                @foreach ($loaiPhongs as $loai)
                                    <option value="{{ $loai->id }}"
                                        {{ old('loai_phong_id', $phongChieu->loai_phong_id) == $loai->id ? 'selected' : '' }}>
                                        {{ $loai->ten_loai_phong }}
                                    </option>
                                @endforeach
                            </select>
                            @error('loai_phong_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="hoat_dong"
                                    {{ old('status', $phongChieu->status) == 'hoat_dong' ? 'selected' : '' }}>Hoạt động
                                </option>
                                <option value="tam_dung"
                                    {{ old('status', $phongChieu->status) == 'tam_dung' ? 'selected' : '' }}>Tạm dừng
                                </option>
                                <option value="bao_tri"
                                    {{ old('status', $phongChieu->status) == 'bao_tri' ? 'selected' : '' }}>Bảo trì
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.rap-phim.show', $phongChieu->rap_phim_id) }}"
                            class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

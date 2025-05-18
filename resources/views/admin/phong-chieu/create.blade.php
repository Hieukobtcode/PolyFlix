@extends('layouts.admin')

@section('title', 'Quản lý Phòng chiếu')
@section('page-title', 'Thêm phòng chiếu')
@section('breadcrumb', 'Thêm phòng chiếu')

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
            margin-bottom: 0.5rem;
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
                <h5 class="mb-0 fw-bold">Thêm phòng chiếu mới</h5>
                <a href="{{ route('admin.phong-chieu.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.phong-chieu.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="ten_phong" class="form-label fw-semibold">Tên phòng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten_phong') is-invalid @enderror"
                                       id="ten_phong" name="ten_phong" value="{{ old('ten_phong') }}"
                                       placeholder="Nhập tên phòng chiếu">
                                @error('ten_phong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="rap_phim_id" class="form-label fw-semibold">Rạp phim <span class="text-danger">*</span></label>
                                <select class="form-select @error('rap_phim_id') is-invalid @enderror" name="rap_phim_id" id="rap_phim_id">
                                    <option value="">-- Chọn rạp phim --</option>
                                    @foreach($rapPhims as $rap)
                                        <option value="{{ $rap->id }}" {{ old('rap_phim_id') == $rap->id ? 'selected' : '' }}>
                                            {{ $rap->ten_rap }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rap_phim_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="loai_phong_id" class="form-label fw-semibold">Loại phòng <span class="text-danger">*</span></label>
                                <select class="form-select @error('loai_phong_id') is-invalid @enderror" name="loai_phong_id" id="loai_phong_id">
                                    <option value="">-- Chọn loại phòng --</option>
                                    @foreach($loaiPhongs as $loai)
                                        <option value="{{ $loai->id }}" {{ old('loai_phong_id') == $loai->id ? 'selected' : '' }}>
                                            {{ $loai->ten_loai }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('loai_phong_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="sẵn sàng" {{ old('status') == 'sẵn sàng' ? 'selected' : '' }}>Sẵn sàng</option>
                                    <option value="không khả dụng" {{ old('status') == 'không khả dụng' ? 'selected' : '' }}>Không khả dụng</option>
                                    <option value="bảo trì" {{ old('status') == 'bảo trì' ? 'selected' : '' }}>Bảo trì</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.phong-chieu.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('ten_phong').focus();

        document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
            if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection

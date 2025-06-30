@extends('layouts.admin')
@section('content')
    <div class="container-fluid d-flex justify-content-center">
        <div class="card shadow-sm border-0" style="width: 100%; max-width: 1100px;">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm phòng chiếu cho rạp: {{ $rapPhim->ten_rap }}</h5>
                <a href="{{ route('admin.chi-nhanh.show', $rapPhim->chi_nhanh_id) }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Quay lại rạp
                </a>
            </div>
            <div class="card-body px-4 py-3">
                <form action="{{ route('admin.phong-chieu.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rap_phim_id" value="{{ $rapPhim->id }}">

                    <div class="row g-4 justify-content-center align-items-end">
                        <div class="col-md-4">
                            <label for="ten_phong" class="form-label">Tên phòng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ten_phong') is-invalid @enderror"
                                name="ten_phong" id="ten_phong" value="{{ old('ten_phong') }}"
                                placeholder="VD: Phòng 1, VIP, 4DX...">
                            @error('ten_phong')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="loai_phong_id" class="form-label">Loại phòng <span
                                    class="text-danger">*</span></label>
                            <select name="loai_phong_id" id="loai_phong_id"
                                class="form-select @error('loai_phong_id') is-invalid @enderror">
                                <option value="">-- Chọn loại --</option>
                                @foreach ($loaiPhongs as $loai)
                                    <option value="{{ $loai->id }}"
                                        {{ old('loai_phong_id') == $loai->id ? 'selected' : '' }}>
                                        {{ $loai->ten_loai_phong }}
                                    </option>
                                @endforeach
                            </select>
                            @error('loai_phong_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="hoat_dong" {{ old('status') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động
                                </option>
                                <option value="tam_dung" {{ old('status') == 'tam_dung' ? 'selected' : '' }}>Tạm dừng
                                </option>
                                <option value="bao_tri" {{ old('status') == 'bao_tri' ? 'selected' : '' }}>Bảo trì</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


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
        document.getElementById('ten_phong').focus();
    </script>
@endsection

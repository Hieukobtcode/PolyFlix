@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')
@section('page-title', 'Thêm mới liên hệ')
@section('breadcrumb', 'Thêm mới liên hệ')

@section('content')
    <div class="body flex-grow-1">
        <div class="container-lg px-4">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Thêm mới liên hệ</strong>
                        <a href="{{ route('admin.lien-he.index') }}" class="btn btn-outline-primary btn-sm">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-arrow-left') }}">
                                </use>
                            </svg>Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.lien-he.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Thông tin cơ bản -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <strong>Thông tin cơ bản</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="ten" class="form-label">Tên <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('ten') is-invalid @enderror" id="ten"
                                                        name="ten" value="{{ old('ten') }}" required>
                                                    @error('ten')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                                        name="email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="so_dien_thoai" class="form-label">Số điện thoại <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                                        id="so_dien_thoai" name="so_dien_thoai"
                                                        value="{{ old('so_dien_thoai') }}" required>
                                                    @error('so_dien_thoai')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nguon_goc" class="form-label">Nguồn gốc</label>
                                                    <input type="text"
                                                        class="form-control @error('nguon_goc') is-invalid @enderror"
                                                        id="nguon_goc" name="nguon_goc" value="{{ old('nguon_goc') }}"
                                                        list="nguon_goc_list">
                                                    <datalist id="nguon_goc_list">
                                                        @foreach($sources as $source)
                                                            <option value="{{ $source }}">
                                                        @endforeach
                                                    </datalist>
                                                    @error('nguon_goc')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="noi_dung" class="form-label">Nội dung <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control @error('noi_dung') is-invalid @enderror"
                                                id="noi_dung" name="noi_dung" rows="5"
                                                required>{{ old('noi_dung') }}</textarea>
                                            @error('noi_dung')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Ghi chú nội bộ -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <strong>Ghi chú nội bộ</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="ghi_chu_noi_bo" class="form-label">Ghi chú nội bộ</label>
                                            <textarea class="form-control @error('ghi_chu_noi_bo') is-invalid @enderror"
                                                id="ghi_chu_noi_bo" name="ghi_chu_noi_bo"
                                                rows="3">{{ old('ghi_chu_noi_bo') }}</textarea>
                                            @error('ghi_chu_noi_bo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="note" class="form-label">Thêm ghi chú</label>
                                            <textarea class="form-control @error('note') is-invalid @enderror" id="note"
                                                name="note" rows="3">{{ old('note') }}</textarea>
                                            <div class="form-text">Ghi chú này sẽ được lưu vào lịch sử ghi chú.</div>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Trạng thái và phân loại -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <strong>Trạng thái và phân loại</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="trang_thai" class="form-label">Trạng thái</label>
                                            <select class="form-select @error('trang_thai') is-invalid @enderror"
                                                id="trang_thai" name="trang_thai">
                                                <option value="0" {{ old('trang_thai') == '0' ? 'selected' : '' }}>Chưa xử lý
                                                </option>
                                                <option value="1" {{ old('trang_thai') == '1' ? 'selected' : '' }}>Đã xử lý
                                                </option>
                                            </select>
                                            @error('trang_thai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="muc_do_uu_tien" class="form-label">Mức độ ưu tiên</label>
                                            <select class="form-select @error('muc_do_uu_tien') is-invalid @enderror"
                                                id="muc_do_uu_tien" name="muc_do_uu_tien">
                                                <option value="cao" {{ old('muc_do_uu_tien') == 'cao' ? 'selected' : '' }}>Cao
                                                </option>
                                                <option value="binh_thuong" {{ old('muc_do_uu_tien') == 'binh_thuong' ? 'selected' : '' }} selected>Bình thường</option>
                                                <option value="thap" {{ old('muc_do_uu_tien') == 'thap' ? 'selected' : '' }}>
                                                    Thấp</option>
                                            </select>
                                            @error('muc_do_uu_tien')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phan_loai" class="form-label">Phân loại</label>
                                            <input type="text" class="form-control @error('phan_loai') is-invalid @enderror"
                                                id="phan_loai" name="phan_loai" value="{{ old('phan_loai') }}"
                                                list="phan_loai_list">
                                            <datalist id="phan_loai_list">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category }}">
                                                @endforeach
                                            </datalist>
                                            @error('phan_loai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="da_phan_hoi" class="form-label">Đã phản hồi</label>
                                            <select class="form-select @error('da_phan_hoi') is-invalid @enderror"
                                                id="da_phan_hoi" name="da_phan_hoi">
                                                <option value="0" {{ old('da_phan_hoi') == '0' ? 'selected' : '' }} selected>
                                                    Chưa phản hồi</option>
                                                <option value="1" {{ old('da_phan_hoi') == '1' ? 'selected' : '' }}>Đã phản
                                                    hồi</option>
                                            </select>
                                            @error('da_phan_hoi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Thông tin bổ sung -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <strong>Thông tin bổ sung</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="nguoi_phu_trach" class="form-label">Người phụ trách</label>
                                            <input type="text"
                                                class="form-control @error('nguoi_phu_trach') is-invalid @enderror"
                                                id="nguoi_phu_trach" name="nguoi_phu_trach"
                                                value="{{ old('nguoi_phu_trach') }}">
                                            @error('nguoi_phu_trach')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="ngay_hen" class="form-label">Ngày hẹn</label>
                                            <input type="date" class="form-control @error('ngay_hen') is-invalid @enderror"
                                                id="ngay_hen" name="ngay_hen" value="{{ old('ngay_hen') }}">
                                            @error('ngay_hen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <svg class="icon me-2">
                                            <use
                                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-save') }}">
                                            </use>
                                        </svg>Lưu liên hệ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
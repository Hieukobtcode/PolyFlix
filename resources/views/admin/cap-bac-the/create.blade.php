@extends('layouts.admin')

@section('title', 'Thêm cấp bậc thẻ')
@section('page-title', 'Thêm cấp bậc thẻ')
@section('breadcrumb', 'Thêm cấp bậc thẻ')

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        .form-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .btn {
            border-radius: 8px;
        }

        .invalid-feedback {
            font-size: 0.9em;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Thêm cấp bậc thẻ</h5>
                <a href="{{ route('admin.cap-bac-the.index') }}" class="btn btn-light btn-sm" title="Quay lại">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.cap-bac-the.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="mb-4">
                                <label for="ten" class="form-label fw-semibold">Tên cấp bậc <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded @error('ten') is-invalid @enderror" id="ten"
                                    name="ten" value="{{ old('ten') }}" placeholder="Nhập tên cấp bậc">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="mo_ta" class="form-label fw-semibold">Mô tả <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control rounded @error('mo_ta') is-invalid @enderror" id="mo_ta"
                                    name="mo_ta" rows="4" placeholder="Nhập mô tả cấp bậc">{{ old('mo_ta') }}</textarea>
                                @error('mo_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tong_chi_tieu" class="form-label fw-semibold">Tổng chi tiêu (VNĐ) <span
                                        class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control rounded @error('tong_chi_tieu') is-invalid @enderror"
                                    id="tong_chi_tieu" name="tong_chi_tieu" value="{{ old('tong_chi_tieu', 0) }}" min="0"
                                    placeholder="Nhập tổng chi tiêu">
                                <div class="form-text">Tổng số tiền(VNĐ) chi tiêu để đạt được cấp bậc đó.</div>
                                @error('tong_chi_tieu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="phan_tram_ve" class="form-label fw-semibold">Phần trăm hoàn tiền (%) <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control rounded @error('phan_tram_ve') is-invalid @enderror"
                                        id="phan_tram_ve" name="phan_tram_ve" value="{{ old('phan_tram_ve', 0) }}"
                                        placeholder="Nhập % hoàn tiền">
                                    <div class="form-text">Tỷ lệ phần trăm(%) điểm tích lũy nhận được khi đặt vé.</div>
                                    @error('phan_tram_ve')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="phan_tram_dich_vu" class="form-label fw-semibold">Phần trăm ưu đãi dịch vụ
                                        (%) <span class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control rounded @error('phan_tram_dich_vu') is-invalid @enderror"
                                        id="phan_tram_dich_vu" name="phan_tram_dich_vu"
                                        value="{{ old('phan_tram_dich_vu', 0) }}" placeholder="Nhập % ưu đãi dịch vụ">
                                    <div class="form-text">Tỷ lệ phần trăm(%) điểm tích lũy nhận được ưu đãi dịch vụ.</div>
                                    @error('phan_tram_dich_vu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(!$hasDefault)
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1"
                                        {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">Đặt làm mặc định</label>
                                </div>
                            @else
                                <div class="mb-4">
                                    <div class="alert alert-info rounded">
                                        <i class="fas fa-info-circle me-1"></i> Đã có cấp bậc mặc định trong hệ thống
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.cap-bac-the.index') }}" class="btn btn-outline-secondary"
                            title="Hủy">Hủy</a>
                        <button type="submit" class="btn btn-primary" title="Lưu">
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
        document.addEventListener('DOMContentLoaded', function () {
            // Tự động focus vào trường tên cấp bậc
            document.getElementById('ten').focus();

            // Xác nhận trước khi hủy
            document.querySelector('.btn-outline-secondary').addEventListener('click', function (e) {
                if (!confirm('Bạn có muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
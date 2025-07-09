@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.cap-bac-the.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-12">

                            {{-- Tên cấp bậc --}}
                            <div class="mb-4">
                                <label for="ten" class="form-label fw-semibold">Tên cấp bậc <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="ten" name="ten"
                                    class="form-control rounded @error('ten') is-invalid @enderror"
                                    placeholder="Nhập tên cấp bậc" value="{{ old('ten') }}">
                                @error('ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Mô tả --}}
                            <div class="mb-4">
                                <label for="mo_ta" class="form-label fw-semibold">Mô tả <span
                                        class="text-danger">*</span></label>
                                <textarea id="mo_ta" name="mo_ta" rows="4" class="form-control rounded @error('mo_ta') is-invalid @enderror"
                                    placeholder="Nhập mô tả cấp bậc">{{ old('mo_ta') }}</textarea>
                                @error('mo_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tổng chi tiêu --}}
                            <div class="mb-4">
                                <label for="tong_chi_tieu" class="form-label fw-semibold">Tổng chi tiêu <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="tong_chi_tieu" name="tong_chi_tieu" min="0"
                                    class="form-control rounded @error('tong_chi_tieu') is-invalid @enderror"
                                    value="{{ old('tong_chi_tieu', 0) }}" placeholder="Nhập Tổng chi tiêu">
                                <div class="form-text">Tổng chi tiêu để đạt được cấp bậc đó.</div>
                                @error('tong_chi_tieu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- % hoàn điểm & % ưu đãi dịch vụ --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="phan_tram_ve" class="form-label fw-semibold">Phần trăm hoàn điểm (%) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="phan_tram_ve" name="phan_tram_ve"
                                        class="form-control rounded @error('phan_tram_ve') is-invalid @enderror"
                                        value="{{ old('phan_tram_ve', 0) }}" placeholder="Nhập % hoàn điểm">
                                    <div class="form-text">Tỷ lệ tích điểm khi đặt vé.</div>
                                    @error('phan_tram_ve')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="col-md-6 mb-4">
                                    <label for="phan_tram_dich_vu" class="form-label fw-semibold">Phần trăm ưu đãi dịch vụ
                                        (%) <span class="text-danger">*</span></label>
                                    <input type="number" id="phan_tram_dich_vu" name="phan_tram_dich_vu"
                                        class="form-control rounded @error('phan_tram_dich_vu') is-invalid @enderror"
                                        value="{{ old('phan_tram_dich_vu', 0) }}" placeholder="Nhập % ưu đãi dịch vụ">
                                    <div class="form-text">Tỷ lệ tích điểm cho ưu đãi dịch vụ.</div>
                                    @error('phan_tram_dich_vu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                            </div>

                            {{-- Mặc định --}}
                            @if (!$hasDefault)
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default"
                                        value="1" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_default">Đặt làm mặc định</label>
                                </div>
                            @else
                                <div class="alert alert-info rounded">
                                    <i class="ti ti-info-circle me-1"></i> Đã có cấp bậc mặc định trong hệ thống.
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Hành động --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.cap-bac-the.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-x"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('ten').focus();

            // Xác nhận khi nhấn nút Hủy
            const cancelBtn = document.querySelector('.btn-outline-secondary');
            cancelBtn.addEventListener('click', function(e) {
                if (!confirm('Bạn có chắc chắn muốn hủy và quay lại danh sách?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection

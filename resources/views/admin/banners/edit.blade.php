@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Hình ảnh --}}
                    <div class="mb-4">
                        <label for="hinh_anh" class="form-label fw-semibold">Hình ảnh</label>
                        <input type="file" class="form-control @error('hinh_anh') is-invalid @enderror" id="hinh_anh"
                            name="hinh_anh">
                        @if ($banner->hinh_anh)
                            <img src="{{ asset('storage/' . $banner->hinh_anh) }}" alt="Hình hiện tại"
                                class="img-preview mt-2" style="max-height: 150px;">
                        @endif
                        @error('hinh_anh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="mb-4">
                        <label for="trang_thai" class="form-label fw-semibold">Trạng thái</label>
                        <select class="form-select @error('trang_thai') is-invalid @enderror" id="trang_thai"
                            name="trang_thai">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" {{ old('trang_thai', $banner->trang_thai) == '1' ? 'selected' : '' }}>
                                Hiển thị</option>
                            <option value="0" {{ old('trang_thai', $banner->trang_thai) == '0' ? 'selected' : '' }}>Ẩn
                            </option>
                        </select>
                        @error('trang_thai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary" title="Hủy">
                            <i class="ti ti-arrow-back-up me-1"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary" title="Cập nhật">
                            <i class="ti ti-device-floppy me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelector('.btn-outline-secondary').addEventListener('click', function(e) {
            if (!confirm('Bạn có chắc chắn muốn hủy chỉnh sửa?')) {
                e.preventDefault();
            }
        });
    </script>
@endsection

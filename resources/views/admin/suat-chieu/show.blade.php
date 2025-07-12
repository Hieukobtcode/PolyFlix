@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Chi tiết suất chiếu</h5>
            </div>

            <div class="card-body p-4">

                {{-- Thông tin phim --}}
                <div class="mb-5">
                    <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-4">Thông tin phim</h6>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Poster:</label>
                                <div class="flex-grow-1">
                                    @if ($suatChieu->phim->poster)
                                        <img src="{{ asset('storage/' . $suatChieu->phim->poster) }}"
                                            class="img-thumbnail rounded shadow-sm"
                                            style="width: 120px; height: 160px; object-fit: cover;"
                                            alt="{{ $suatChieu->phim->ten_phim }}">
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Không có ảnh</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Tên phim:</label>
                                <div class="flex-grow-1">{{ $suatChieu->phim->ten_phim }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Thời lượng:</label>
                                <div class="flex-grow-1">
                                    {{ $suatChieu->phim->thoi_luong ? $suatChieu->phim->thoi_luong . ' phút' : 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Thể loại:</label>
                                <div class="flex-grow-1">
                                    @forelse ($suatChieu->phim->theLoais as $tl)
                                        <span
                                            class="badge bg-info text-dark rounded-pill me-1">{{ $tl->ten_the_loai }}</span>
                                    @empty
                                        <span class="text-muted">Chưa có thể loại</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Phiên bản:</label>
                                <div class="flex-grow-1">{{ $suatChieu->formatted_version }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thông tin suất chiếu --}}
                <div class="mb-5">
                    <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-4">Thông tin suất chiếu</h6>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Ngày chiếu:</label>
                                <div class="flex-grow-1">
                                    {{ \Carbon\Carbon::parse($suatChieu->ngay_bat_dau)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Thời gian:</label>
                                <div class="flex-grow-1">
                                    {{ \Carbon\Carbon::parse($suatChieu->bat_dau)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($suatChieu->ket_thuc)->format('H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Trạng thái:</label>
                                <div class="flex-grow-1">
                                    <span
                                        class="badge rounded-pill bg-{{ $suatChieu->trang_thai === 'hoat_dong' ? 'success' : 'secondary' }}">
                                        {{ $suatChieu->trang_thai === 'hoat_dong' ? 'Hoạt động' : 'Tạm dừng' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thông tin địa điểm --}}
                <div class="mb-5">
                    <h6 class="fw-bold text-uppercase border-bottom pb-2 mb-4">Thông tin địa điểm</h6>
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Phòng chiếu:</label>
                                <div class="flex-grow-1">
                                    {{ $suatChieu->phongChieu->ten_phong ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Rạp:</label>
                                <div class="flex-grow-1">
                                    {{ $suatChieu->phongChieu->rapPhim->ten_rap ?? 'Chưa có rạp' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <label class="fw-semibold w-40">Chi nhánh:</label>
                                <div class="flex-grow-1">
                                    {{ $suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh ?? 'Chưa có chi nhánh' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.suat-chieu.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        Quay lại
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

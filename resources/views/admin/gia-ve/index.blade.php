@extends('layouts.admin')

@section('title', 'Cập nhật giá vé')
@section('page-title', 'Cập nhật giá vé')
@section('breadcrumb', 'Cập nhật giá vé')

@section('content')
    <form method="POST" action="{{ route('admin.gia-ve.cap-nhat') }}">
        @csrf
        <div class="container-fluid">
            <div class="row g-4">

                {{-- Bên trái: Giá theo ghế và phòng --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            {{-- Giá theo ghế --}}
                            <h5 class="text-center mb-4 text-primary fw-semibold">Phụ thu theo Loại Ghế</h5>
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    @foreach ($loaiGhes as $ghe)
                                        <tr>
                                            <td class="fw-medium">{{ $ghe->ten_loai_ghe }}</td>
                                            <td class="text-end" style="width: 160px;">
                                                <input type="number" name="gia_ghe[{{ $ghe->id }}]"
                                                    class="form-control text-end" value="{{ $ghe->phu_thu ?? 0 }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Giá theo loại phòng --}}
                            <h5 class="text-center mt-5 mb-4 text-primary fw-semibold">Phụ thu theo Loại Phòng</h5>
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    @foreach ($loaiPhongs as $phong)
                                        <tr>
                                            <td class="fw-medium">{{ $phong->ten_loai_phong }}</td>
                                            <td class="text-end" style="width: 160px;">
                                                <input type="number" name="phu_thu_phong[{{ $phong->id }}]"
                                                    class="form-control text-end" value="{{ $phong->phu_thu ?? 0 }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Bên phải: Phụ thu theo Rạp --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="text-primary fw-semibold mb-0">Phụ thu theo Rạp</h5>
                                <button type="submit" class="btn btn-success">
                                    Cập nhật
                                </button>
                            </div>

                            {{-- Bộ lọc Chi nhánh --}}
                            <div class="mb-3">
                                <label class="form-label fw-medium">Lọc theo Chi nhánh</label>
                                <select id="chiNhanhSelect" class="form-select">
                                    <option value="">-- Tất cả Chi nhánh --</option>
                                    @foreach ($chiNhanhs as $cn)
                                        <option value="{{ $cn->id }}">{{ $cn->ten_chi_nhanh }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Bảng phụ thu rạp --}}
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Tên Rạp</th>
                                            <th style="width: 160px;">Phụ thu (VNĐ)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rapPhims as $rap)
                                            <tr class="rap" data-chi-nhanh-id="{{ $rap->chi_nhanh_id }}">
                                                <td>{{ $rap->ten_rap }}</td>
                                                <td>
                                                    <input type="number" name="phu_thu_rap[{{ $rap->id }}]"
                                                        class="form-control text-end" value="{{ $rap->phu_thu ?? 0 }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-muted small mt-3">
                                <i class="ti ti-info-circle me-1"></i> Phụ thu sẽ được cộng thêm vào giá vé cơ bản.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('chiNhanhSelect');
            select.addEventListener('change', function() {
                const selectedId = this.value;
                document.querySelectorAll('.rap').forEach(row => {
                    const chiNhanhId = row.getAttribute('data-chi-nhanh-id');
                    row.style.display = (selectedId === "" || chiNhanhId === selectedId) ? "" :
                        "none";
                });
            });
        });
    </script>
@endsection

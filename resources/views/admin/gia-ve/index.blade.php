@extends('layouts.admin')

@section('title', 'Quản lý giá vé')
@section('page-title', 'Quản lý giá vé')
@section('breadcrumb', 'Giá vé')

@section('content')
    <form method="POST" action="{{ route('admin.gia-ve.cap-nhat') }}">
        @csrf
        <div class="container">
            <div class="row g-4">

                <!-- Bên trái -->
                <div class="col-lg-6">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <!-- Giá theo ghế -->
                            <h5 class="text-center mb-3">Giá theo Ghế</h5>
                            <table class="table align-middle">
                                <tbody>
                                    @foreach ($loaiGhes as $ghe)
                                        <tr>
                                            <td class="fw-semibold">{{ $ghe->ten_loai_ghe }}</td>
                                            <td style="width: 150px;">
                                                <input type="number" name="gia_ghe[{{ $ghe->id }}]"
                                                    class="form-control text-end" value="{{ $ghe->phu_thu }}" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Phụ thu theo loại phòng -->
                            <h5 class="text-center mb-3">Giá theo loại phòng</h5>
                            <table class="table align-middle">
                                <tbody>
                                    @foreach ($loaiPhongs as $phong)
                                        <tr>
                                            <td class="fw-semibold">{{ $phong->ten_loai_phong }}</td>
                                            <td style="width: 150px;">
                                                <input type="number" name="phu_thu_phong[{{ $phong->id }}]"
                                                    class="form-control text-end" value="{{ $phong->phu_thu }}" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Bên phải -->
                <div class="col-lg-6">
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                    <div class="card shadow-sm rounded-3">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Phụ thu theo Rạp</h4>

                            <!-- Bộ lọc chi nhánh -->
                            <div class="mb-3">
                                <select id="chiNhanhSelect" class="form-select">
                                    <option value="">-- Tất cả Chi nhánh --</option>
                                    @foreach ($chiNhanhs as $cn)
                                        <option value="{{ $cn->id }}">{{ $cn->ten_chi_nhanh }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bảng phụ thu -->
                            <div class="table-responsive mt-3" id="bangRapPhim">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Tên Rạp</th>
                                            <th class="text-center" style="width: 160px;">Giá phụ thu (VNĐ)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rapPhims as $rap)
                                            <tr class="rap" data-chi-nhanh-id="{{ $rap->chi_nhanh_id }}">
                                                <td>{{ $rap->ten_rap }}</td>
                                                <td>
                                                    <input type="number" name="phu_thu_rap[{{ $rap->id }}]"
                                                        class="form-control text-end" value="{{ $rap->phu_thu ?? 0 }}" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $('#chiNhanhSelect').on('change', function() {
                const selectedChiNhanh = $(this).val();

                $('.rap').each(function() {
                    if (selectedChiNhanh === "" || $(this).data('chi-nhanh-id') ==
                        selectedChiNhanh) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endsection

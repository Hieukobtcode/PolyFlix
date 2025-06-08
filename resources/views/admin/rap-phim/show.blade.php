@extends('layouts.admin')
@section('title', 'Quản lý chi nhánh')
@section('page-title', 'Chi tiết rạp chiếu')
@section('breadcrumb')
    <a href="{{ route('admin.chi-nhanh.index') }}">Danh sách chi nhánh</a> / 
    <a href="{{ route('admin.chi-nhanh.show', $rapPhim->chi_nhanh_id) }}">Danh sách rạp chiếu</a>/
    Danh sách phòng chiếu
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 12px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3">

            {{-- Cột trái --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <strong><i class="fas fa-film me-1"></i> Rạp Chiếu</strong>
                    </div>
                    <div class="card-body px-3 py-2 small">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-store-alt text-primary me-1"></i>
                                <strong>Tên rạp</strong><br>
                                <span class="text-muted">{{ $rapPhim->ten_rap }}</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                <strong>Địa chỉ</strong><br>
                                <span class="text-muted">{{ $rapPhim->dia_chi }}</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-building text-success me-1"></i>
                                <strong>Chi nhánh</strong><br>
                                <span class="text-muted">
                                    <a href="{{ route('admin.chi-nhanh.show', $rapPhim->chiNhanh->id) }}"
                                        class="text-decoration-none">
                                        {{ $rapPhim->chiNhanh->ten_chi_nhanh }}
                                    </a>
                                </span>
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-info-circle text-warning me-1"></i>
                                <strong>Trạng thái</strong><br>
                                @php
                                    $statusColors = [
                                        'đang hoạt động' => '#198754',
                                        'bảo trì' => '#ffc107',
                                        'đã đóng' => '#dc3545',
                                    ];
                                    $statusLabels = [
                                        'đang hoạt động' => 'Hoạt động',
                                        'bảo trì' => 'Tạm dừng',
                                        'đã đóng' => 'Đóng cửa',
                                    ];
                                    $bg = $statusColors[$rapPhim->trang_thai] ?? '#6c757d';
                                    $label = $statusLabels[$rapPhim->trang_thai] ?? 'Không rõ';
                                @endphp
                                <span class="d-inline-block mt-1 px-3 py-1 fw-bold text-white rounded"
                                    style="background-color: {{ $bg }}; font-size: 0.85rem;">
                                    {{ $label }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Cột phải --}}
            <div class="col-md-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                        <strong><i class="fas fa-door-open me-1"></i> Phòng Chiếu</strong>
                        <a href="{{ route('admin.phong-chieu.create', ['rap_phim_id' => $rapPhim->id]) }}"
                            class="btn btn-light btn-sm d-flex align-items-center" title="Thêm phòng chiếu">
                            <i class="fas fa-plus me-1"></i> Thêm phòng chiếu
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @if ($rapPhim->phongChieus->isEmpty())
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i> Không có phòng chiếu nào.
                            </p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-dark text-center small">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th>Tên phòng</th>
                                            <th>Loại phòng</th>
                                            <th style="width: 20%">Số ghế</th>
                                            <th style="width: 15%">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rapPhim->phongChieus as $index => $phong)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $phong->ten_phong }}</td>
                                                <td>{{ ucfirst($phong->loaiPhong->ten_loai_phong ?? 'Không rõ') }}</td>
                                                <td class="text-center">
                                                    @if ($phong->so_do_ghe_id)
                                                        {{ $phong->so_ghe }}
                                                    @else
                                                        <span class="text-muted fst-italic">Chưa có</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.phong-chieu.edit', $phong->id) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Sửa phòng chiếu">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if (is_null($phong->so_do_ghe_id))
                                                        <a href="#" class="btn btn-sm btn-outline-success"
                                                            data-bs-toggle="modal" data-bs-target="#modalSoDoGhe"
                                                            data-id="{{ $phong->id }}"
                                                            data-tenphong="{{ $phong->ten_phong }}" title="Thêm sơ đồ ghế">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.ghe-ngoi.show', $phong->id) }}"
                                                            class="btn btn-sm btn-outline-secondary"
                                                            data-bs-toggle="tooltip" title="Xem sơ đồ ghế"
                                                            data-bs-placement="top">
                                                            <i class="fa-solid fa-couch fa-spin-pulse"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.rap-phim.modal')

@endsection
@section('scripts')
    <script>
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const phongId = this.getAttribute('data-id');
                const tenPhong = this.getAttribute('data-tenphong');

                document.getElementById('modalSoDoGheLabel').innerHTML =
                    `Tạo sơ đồ ghế cho phòng chiếu: ${tenPhong}`;
                document.getElementById('phong_id').value = phongId;
                document.getElementById('formSoDoGhe').reset();
            });
        });

        document.getElementById('formSoDoGhe').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            $.ajax({
                url: form.action,
                method: 'POST',
                data: $(form).serialize() + '&_token=' + '{{ csrf_token() }}',
                success: function(response) {
                    $('#modalSoDoGhe').modal('hide');
                    window.location.href = response.redirectUrl;
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;

                    $('.text-danger').remove();

                    for (var field in errors) {
                        $('#' + field).after('<div class="text-danger">' + errors[field][0] + '</div>');
                    }
                }
            });
        });

        document.getElementById('mau_so_do').addEventListener('change', function() {
            const selectedOption = this.value;

            if (selectedOption) {
                const [rows, cols] = selectedOption.split('x');

                document.getElementById('so_hang').value = rows;
                document.getElementById('so_cot').value = cols;

                updateLoaiGhe(rows);
            } else {
                document.getElementById('so_hang').value = "";
                document.getElementById('so_cot').value = "";
                document.getElementById('ghe_thuong').value = "";
                document.getElementById('ghe_vip').value = "";
                document.getElementById('ghe_doi').value = "";
            }
        });

        function updateLoaiGhe(rows) {
            const hangGheThuong = document.getElementById('ghe_thuong');
            const hangGheVip = document.getElementById('ghe_vip');
            const hangGheDoi = document.getElementById('ghe_doi');

            const gheThuong = Math.ceil(rows * 50 / 100);
            const gheVip = Math.ceil(rows * 30 / 100);
            const gheDoi = rows - (gheThuong + gheVip);

            hangGheThuong.value = gheThuong;
            hangGheVip.value = gheVip;
            hangGheDoi.value = gheDoi;
        }
    </script>
@endsection

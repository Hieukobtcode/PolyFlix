@extends('layouts.admin')
@section('title', 'Quản lý chi nhánh')
@section('page-title', 'Chi tiết rạp chiếu')
@section('breadcrumb')
    <a href="{{ route('admin.chi-nhanh.index') }}">Danh sách chi nhánh</a> /
    <a href="{{ route('admin.chi-nhanh.show', $rapPhim->chi_nhanh_id) }}">Danh sách rạp chiếu</a>/
    Danh sách phòng chiếu
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
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

    @if ($errors->any() && session('show_create_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('modalSoDoGhe'));
                modal.show();
            });
        </script>
    @endif

    @include('admin.rap-phim.modal')

@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loai_ghe_ids').select2({
                theme: 'bootstrap-5',
                placeholder: 'Chọn loại ghế',
                allowClear: true,
                dropdownParent: $('#modalSoDoGhe'),
                width: '100%'
            });

            $('#loai_ghe_ids').on('change', function() {
                const selected = $(this).val();
                const container = $('#input_container');
                container.empty();

                if (selected && selected.length > 0) {
                    selected.forEach(function(loaiGheId) {
                        const tenLoaiGhe = $('#loai_ghe_ids option[value="' + loaiGheId.trim() +
                            '"]').text();

                        const inputHtml = `
                <div class="mb-3">
                    <label class="form-label">${tenLoaiGhe}</label>
                    <input type="number" id="loai_ghe_${loaiGheId}" data-id-loai="${loaiGheId}" name="so_hang_${loaiGheId}" min="1"
                        class="form-control form-control-lg shadow-sm"
                        placeholder="Số hàng ghế">
                    <div class="text-danger" id="error_loai_ghe_${loaiGheId}"></div>
                </div>`;

                        container.append(inputHtml);

                        const errorMessage = document.getElementById(`error_loai_ghe_${loaiGheId}`);
                        if (errorMessage && errorMessage.innerHTML) {
                            document.getElementById(`loai_ghe_${loaiGheId}`).classList.add(
                                'is-invalid');
                        }
                    });
                }
            });

            $('[data-bs-toggle="modal"]').on('click', function() {
                const phongId = $(this).data('id');
                const tenPhong = $(this).data('tenphong');

                $('#modalSoDoGheLabel').html(`Tạo sơ đồ ghế cho phòng chiếu: ${tenPhong}`);
                $('#phong_id').val(phongId);

                const form = $('#formSoDoGhe')[0];
                form.reset();

                $('#loai_ghe_ids').val(null).trigger('change');
                $('#input_container').empty();
            });

            $('#mau_so_do').on('change', function() {
                const selectedOption = $(this).val();

                if (selectedOption) {
                    const [rows, cols] = selectedOption.split('x');

                    $('#so_hang').val(rows);
                    $('#so_cot').val(cols);

                    updateLoaiGhe(rows);
                } else {
                    $('#so_hang').val('');
                    $('#so_cot').val('');
                    $('#ghe_thuong').val('');
                    $('#ghe_vip').val('');
                    $('#ghe_doi').val('');
                }
            });

            function updateLoaiGhe(rows) {
                rows = parseInt(rows);
                const gheThuong = Math.ceil(rows * 50 / 100);
                const gheVip = Math.ceil(rows * 30 / 100);
                const gheDoi = rows - (gheThuong + gheVip);

                $('#ghe_thuong').val(gheThuong);
                $('#ghe_vip').val(gheVip);
                $('#ghe_doi').val(gheDoi);
            }

        });

        $('#formSoDoGhe').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);

            const soHang = Number($('#so_hang').val()) || 0;
            const soCot = Number($('#so_cot').val()) || 0;

            let selectedIds = $('#loai_ghe_ids').val();
            if (!Array.isArray(selectedIds)) {
                selectedIds = selectedIds ? [selectedIds] : [];
            }

            const inputHangGhe = selectedIds.map(id => ({
                id,
                soHang: Number($('#loai_ghe_' + id).val()) || 0
            }));

            const maTranGhe = {};
            let currentRow = 1;
            inputHangGhe.forEach(({
                id,
                soHang
            }) => {
                for (let j = 1; j <= soHang; j++) {
                    const rowLabel = String.fromCharCode(64 + currentRow);
                    for (let c = 1; c <= soCot; c++) {
                        maTranGhe[rowLabel + c] = id;
                    }
                    currentRow++;
                }
            });

            $('#ma_tran_ghe').val(JSON.stringify(maTranGhe));
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                success(response) {
                    $('#modalSoDoGhe').modal('hide');
                    window.location.href = response.redirectUrl;
                },
                error(xhr) {
                    const errors = xhr.responseJSON.errors;
                    $('.text-danger').remove();
                    $.each(errors, function(field, messages) {
                        $('#' + field)
                            .after('<div class="text-danger">' + messages[0] + '</div>');
                    });
                }
            });
        });
    </script>

@endsection

@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row g-3">
            {{-- Cột phải --}}
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                        <strong><i class="fas fa-door-open me-1"></i> Phòng Chiếu: Rạp {{ $rapPhim->ten_rap }}</strong>
                        <a href="{{ route('admin.phong-chieu.create', ['rap_phim_id' => $rapPhim->id]) }}"
                            class="btn btn-sm btn-success d-inline-flex align-items-center gap-2 py-2 px-3"
                            title="Thêm phòng chiếu">
                            <i class="ti ti-plus"></i> Thêm phòng chiếu
                        </a>
                    </div>

                    <div class="card-body p-3">
                        @if ($rapPhim->phongChieus->isEmpty())
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i> Không có phòng chiếu nào.
                            </p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-gradient-dark text-white text-center small">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th class="text-start">Tên phòng</th>
                                            <th class="text-start">Loại phòng</th>
                                            <th style="width: 15%">Số ghế</th>
                                            <th style="width: 15%">Trạng thái</th>
                                            <th style="width: 10%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rapPhim->phongChieus as $index => $phong)
                                            @php
                                                $statusMap = [
                                                    'đang hoạt động' => ['bg-success-subtle text-success', 'Hoạt động'],
                                                    'bảo trì' => ['bg-warning-subtle text-warning', 'Bảo trì'],
                                                    'đã đóng' => ['bg-secondary-subtle text-muted', 'Đã đóng'],
                                                ];
                                                [$statusClass, $statusLabel] = $statusMap[
                                                    $phong->trang_thai ?? 'đang hoạt động'
                                                ] ?? ['bg-secondary-subtle text-muted', 'Không rõ'];
                                            @endphp
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
                                                    <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="dropdown dropstart">
                                                        <a href="javascript:void(0)" class="text-muted"
                                                            data-bs-toggle="dropdown">
                                                            <i class="ti ti-dots-vertical fs-6"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="{{ route('admin.phong-chieu.edit', $phong->id) }}"
                                                                    class="dropdown-item d-flex align-items-center gap-2">
                                                                    <i class="ti ti-edit fs-5"></i> Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            @if (is_null($phong->so_do_ghe_id))
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item d-flex align-items-center gap-2"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalSoDoGhe"
                                                                        data-id="{{ $phong->id }}"
                                                                        data-tenphong="{{ $phong->ten_phong }}">
                                                                        <i class="ti ti-plus-circle fs-5 text-success"></i>
                                                                        Thêm sơ đồ ghế
                                                                    </button>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a href="{{ route('admin.ghe-ngoi.show', $phong->id) }}"
                                                                        class="dropdown-item d-flex align-items-center gap-2">
                                                                        <i class="ti ti-armchair fs-5"></i> Xem
                                                                        sơ đồ ghế
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
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
                            <input type="number"
                                   id="loai_ghe_${loaiGheId}"
                                   data-id-loai="${loaiGheId}"
                                   name="so_hang_${loaiGheId}"
                                   min="1"
                                   class="form-control form-control-lg shadow-sm"
                                   placeholder="Số hàng ghế">
                            <div class="text-danger" id="error_loai_ghe_${loaiGheId}"></div>
                        </div>
                    `;

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

                $('input').removeClass('is-invalid');
                $('.text-danger').empty();

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success(response) {
                        $('#modalSoDoGhe').modal('hide');
                        window.location.href = response.redirectUrl;
                    },
                    error(xhr) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field).after('<div class="text-danger">' + messages[0] +
                                '</div>');
                        });
                    }
                });
            });
        });
    </script>
@endsection

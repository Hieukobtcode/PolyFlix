@extends('layouts.admin')

@section('title', 'Quản lý chi nhánh')
@section('page-title', 'Chi tiết chi nhánh')
@section('breadcrumb')
    <a href="{{ route('admin.chi-nhanh.index') }}">Danh sách chi nhánh</a> / Danh sách rạp chiếu
@endsection

@section('styles')
@section('styles')
    <style>
        .card {
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e6ed;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, #f6c343, #f08a24);
            color: #fff;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #fff;
        }

        .card-header strong {
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .btn-poly {
            background-color: #fff3cd;
            color: #f08a24;
            border: 1px solid #f08a24;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-poly:hover {
            background-color: #ff5900;
            color: #ffffff;
            border-color: #f08a24;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(248, 191, 49, 0.637);
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em 0.8em;
            border-radius: 8px;
        }

        /* Input đẹp */
        .form-control {
            border-radius: 12px;
            border: 1px solid #d8dbe0;
            padding: 10px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #f08a24;
            box-shadow: 0 0 0 0.2rem rgba(240, 138, 36, 0.25);
            outline: none;
        }

        .select2-container .select2-selection--single {
            border-radius: 12px !important;
            border: 1px solid #d8dbe0 !important;
            padding: 8px 12px;
            height: auto !important;
            min-height: 44px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        /* Khi có chọn trạng thái — vùng cam ôm trọn box */
        .select2-selection--single.is-active {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: #fff !important;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.25);
        }

        /* Tạm dừng → cam */
        .select2-selection--single.is-pause {
            background-color: #f08a24 !important;
            border-color: #f08a24 !important;
            color: #fff !important;
            box-shadow: 0 4px 8px rgba(240, 138, 36, 0.25);
        }

        /* Đóng cửa → xám */
        .select2-selection--single.is-closed {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.25);
        }

        /* Text của option được chọn */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0;
            margin: 0;
            color: inherit;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Placeholder khi chưa chọn */
        .select2-container--default .select2-selection__placeholder {
            color: #999 !important;
            background: none !important;
        }

        /* Clear button X */
        .select2-container--default .select2-selection__clear {
            color: #fff !important;
            font-size: 1.2em;
            margin-right: 10px;
            cursor: pointer;
        }

        .select2-container--default .select2-selection__clear:hover {
            color: #ffdcb3 !important;
        }

        /* Focus */
        .select2-container--default.select2-container--focus .select2-selection {
            border-color: #f08a24 !important;
            box-shadow: 0 0 0 0.2rem rgba(240, 138, 36, 0.25) !important;
        }

        /* Dropdown option hover */
        .select2-results__option {
            transition: all 0.2s ease;
        }

        .select2-results__option--highlighted {
            background-color: #f08a24 !important;
            color: #fff !important;
            transition: all 0.2s ease;
        }

        /* Option selected */
        .select2-results__option--selected {
            background-color: #f6c343 !important;
            color: #fff !important;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: #f6c343;
            padding: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: none;
            color: #fff;
        }

        .table tbody tr {
            background-color: #fff;
            transition: none;
        }

        .table tbody tr:hover td {
            background-color: rgba(142, 244, 255, 0.277);
            transform: scale(1.015);
        }

        .table tbody td {
            border: none !important;
            box-shadow: none;
            padding: 1.1rem 0.9rem;
            transition: transform 0.8s ease, box-shadow 0.8s ease, background-color 0.8s ease;
        }

        /* Modal đẹp */
        .modal-content {
            border-radius: 16px;
            border: 1px solid #ffcba0;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            font-size: 1rem;
        }

        .modal-header {
            background: linear-gradient(90deg, #f6c343, #f08a24);
            color: #fff;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #ffcba0;
        }

        .modal-header .modal-title {
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body p {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .modal-footer .btn {
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }
    </style>
@endsection

@endsection

@section('content')
<div class="container-fluid">
    <div class="row g-3">
        {{-- Cột trái: Thông tin chi nhánh --}}
        <div class="col-md-12">
            <div class="card shadow-lg border-0 h-100 mb-4">
                <div class="card-header d-flex align-items-center"
                    style="background: linear-gradient(90deg, #f6c343, #f08a24); color: #fff; padding: 1rem 1.5rem;">
                    <i class="fas fa-building fa-fw me-3"></i>
                    <strong style="font-size: 1.25rem;">Thông tin chi nhánh</strong>
                </div>
                <div class="card-body px-4 py-4">
                    <div class="row g-4">
                        <!-- Cột 1 -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-store-alt text-primary fa-fw me-3 pt-1"></i>
                                <div>
                                    <div class="fw-bold mb-1">Tên chi nhánh</div>
                                    <div class="text-muted">{{ $chiNhanh->ten_chi_nhanh }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-user-tie text-secondary fa-fw me-3 pt-1"></i>
                                <div>
                                    <div class="fw-bold mb-1">Quản lý</div>
                                    <div>
                                        @if ($chiNhanh->quan_ly_id)
                                            <span class="text-success fw-semibold">
                                                {{ $chiNhanh->quanLy->name ?? 'ID: ' . $chiNhanh->quan_ly_id }}
                                            </span>
                                        @elseif (in_array($chiNhanh->id, $pendingInvites))
                                            <button type="button" class="badge bg-warning text-dark border-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cancelInviteModal{{ $chiNhanh->id }}">
                                                Đang phân công
                                            </button>
                                        @else
                                            <span class="badge bg-secondary text-dark border-0">Chưa phân công</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cột 2 -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-map-marker-alt text-danger fa-fw me-3 pt-1"></i>
                                <div>
                                    <div class="fw-bold mb-1">Địa chỉ</div>
                                    <div class="text-muted">{{ $chiNhanh->dia_chi }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-info-circle text-warning fa-fw me-3 pt-1"></i>
                                <div>
                                    <div class="fw-bold mb-1">Trạng thái</div>
                                    @php
                                        $statusColors = [
                                            'hoat_dong' => '#198754',
                                            'tam_dung' => '#ffc107',
                                            'dong_cua' => '#6c757d',
                                        ];
                                        $statusLabels = [
                                            'hoat_dong' => 'Hoạt động',
                                            'tam_dung' => 'Tạm dừng',
                                            'dong_cua' => 'Đóng cửa',
                                        ];
                                    @endphp
                                    <span class="d-inline-block mt-1 px-3 py-1 text-white fw-bold rounded"
                                        style="background-color: {{ $statusColors[$chiNhanh->trang_thai] ?? '#6c757d' }}; font-size: 0.95rem;">
                                        {{ $statusLabels[$chiNhanh->trang_thai] ?? 'Không rõ' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Cột phải: Danh sách rạp --}}
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                    <strong><i class="fas fa-film me-1"></i> Rạp thuộc chi nhánh</strong>
                    <a href="{{ route('admin.rap-phim.create', ['chiNhanhId' => $chiNhanh->id]) }}"
                        class="btn btn-poly rounded-pill px-4 py-2" title="Thêm rạp chiếu">
                        <i class="fas fa-plus me-1"></i> Thêm rạp chiếu
                    </a>
                </div>


                <div class="card-body p-3">
                    <!-- Form search realtime + select filter cho RẠP -->
                    <div class="row mb-4 g-2 align-items-center">

                        <!-- Tên Rạp -->
                        <div class="col-md-3">
                            <input type="text" id="searchTenRap" class="form-control"
                                placeholder="Tìm theo tên rạp...">
                        </div>

                        <!-- Tên Quản lý -->
                        <div class="col-md-3">
                            <input type="text" id="searchTenQuanLyRap" class="form-control"
                                placeholder="Tìm theo tên quản lý...">
                        </div>

                        <!-- Trạng thái Quản lý -->
                        <div class="col-md-3">
                            <select id="filterTrangThaiQLRap" class="form-select select2">
                                <option value="">Trạng thái quản lý</option>
                                <option value="chua_phan_cong">Chưa phân công</option>
                                <option value="dang_phan_cong">Đang phân công</option>
                                <option value="da_phan_cong">Đã phân công</option>
                            </select>
                        </div>

                        <!-- Trạng thái Rạp -->
                        <div class="col-md-3">
                            <select id="filterTrangThaiRap" class="form-select select2">
                                <option value="">Tất cả trạng thái</option>
                                <option value="đang hoạt động">Hoạt động</option>
                                <option value="bảo trì">Tạm dừng</option>
                                <option value="đã đóng">Đóng cửa</option>
                            </select>
                        </div>

                    </div>
                    @if ($chiNhanh->rapPhims->isEmpty())
                        <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Không có rạp nào thuộc chi
                            nhánh này.</p>
                    @else
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-dark text-center small">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>Tên rạp</th>
                                        <th>Địa chỉ</th>
                                        <th style="width: 15%">Trạng thái</th>
                                        <th>Quản lý</th>
                                        <th style="width: 20%">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody id="rapTable">
                                    @foreach ($chiNhanh->rapPhims as $index => $rap)
                                        <tr class="data-row" data-ten-rap="{{ strtolower($rap->ten_rap) }}"
                                            data-ten-ql="{{ strtolower($rap->quanLy->name ?? ($pendingRapEmails[$rap->id] ?? '')) }}"
                                            data-trang-thai="{{ strtolower($rap->trang_thai) }}"
                                            data-trang-thai-ql="
                                            @if ($rap->quan_ly_id) da_phan_cong
                                            @elseif (array_key_exists($rap->id, $pendingRapEmails)) dang_phan_cong
                                            @else chua_phan_cong @endif
                                        ">
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $rap->ten_rap }}</td>
                                            <td>{{ $rap->dia_chi }}</td>
                                            <td class="text-center">
                                                @php
                                                    $statusColors = [
                                                        'đang hoạt động' => '#198754',
                                                        'bảo trì' => '#ffc107',
                                                        'đã đóng' => '#dc3545',
                                                    ];

                                                    $statusTextColors = [
                                                        'đang hoạt động' => '#fff',
                                                        'bảo trì' => '#000',
                                                        'đã đóng' => '#fff',
                                                    ];

                                                    $statusLabels = [
                                                        'đang hoạt động' => 'Hoạt động',
                                                        'bảo trì' => 'Tạm dừng',
                                                        'đã đóng' => 'Đóng cửa',
                                                    ];

                                                    $bg = $statusColors[$rap->trang_thai] ?? '#6c757d';
                                                    $text = $statusTextColors[$rap->trang_thai] ?? '#fff';
                                                    $label = $statusLabels[$rap->trang_thai] ?? 'Không rõ';
                                                @endphp
                                                <span class="d-inline-block mt-1 px-3 py-1 fw-bold rounded"
                                                    style="background-color: {{ $bg }}; color: {{ $text }}; font-size: 0.85rem;">
                                                    {{ $label }}
                                                </span>

                                            </td>
                                            <td class="text-center">
                                                @if ($rap->quan_ly_id)
                                                    <a href="{{ route('admin.users.show', $rap->quan_ly_id) }}"
                                                        class="text-decoration-none">
                                                        {{ $rap->quanLy->name ?? 'ID: ' . $rap->quan_ly_id }}
                                                    </a>
                                                @elseif (array_key_exists($rap->id, $pendingRapEmails))
                                                    <div class="text-center">
                                                        <button type="button"
                                                            class="badge bg-warning text-dark border-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#cancelInviteModalRap{{ $rap->id }}">
                                                            Đang phân công
                                                        </button>


                                                    </div>
                                                @else
                                                    <span class="text-muted fst-italic">Chưa phân công</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('admin.rap-phim.show', $rap->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="Xem">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.rap-phim.edit', $rap->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.phong-chieu.create', ['rap_phim_id' => $rap->id]) }}"
                                                    class="btn btn-sm btn-outline-success" title="Thêm phòng">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                                @if (!$rap->quan_ly_id && !in_array($rap->id, $pendingRapInvites))
                                                    <button class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#inviteModal{{ $rap->id }}"
                                                        title="Phân công quản lý">
                                                        <i class="fa-solid fa-user-plus" style="color: #FFD43B;"></i>
                                                    </button>


                                                    {{-- @elseif($rap->quanLy)
                                                        <a href="{{ route('admin.users.show', $rap->quan_ly_id) }}"
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Xem thông tin quản lý">
                                                            <i class="fa-solid fa-user" style="color: #FFD43B;"></i>
                                                        </a> --}}
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @foreach ($chiNhanh->rapPhims as $rap)
                                @if (!$rap->quan_ly_id && !in_array($rap->id, $pendingRapInvites))
                                    <div class="modal fade" id="inviteModal{{ $rap->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('invite.send') }}">
                                                @csrf
                                                <input type="hidden" name="loai_quan_ly" value="2">
                                                <input type="hidden" name="rap_phim_id"
                                                    value="{{ $rap->id }}">
                                                <div class="modal-content"
                                                    style="border-radius: 16px; overflow: hidden; border: 1px solid #e0e6ed; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);">
                                                    <div class="modal-header"
                                                        style="background: linear-gradient(90deg, #f6c343, #f08a24); color: #fff; padding: 1rem 1.5rem;">
                                                        <h5 class="modal-title fw-semibold mb-0">Phân công quản lý rạp
                                                            phim</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body py-4 px-4">
                                                        <label class="fw-bold mb-2">Email người quản lý</label>
                                                        <input type="email" name="email" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="modal-footer justify-content-between px-3 pb-3">
                                                        <button type="button"
                                                            class="btn btn-secondary btn-sm rounded-pill px-4"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit"
                                                            class="btn btn-poly btn-sm rounded-pill px-4">
                                                            <i class="fas fa-paper-plane me-1"></i> Gửi lời mời
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Modal hủy lời mời Rạp --}}
                            @foreach ($chiNhanh->rapPhims as $rap)
                                @if (array_key_exists($rap->id, $pendingRapEmails))
                                    <div class="modal fade" id="cancelInviteModalRap{{ $rap->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content"
                                                style="border-radius: 16px; overflow: hidden; border: 1px solid #e0e6ed; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);">
                                                <div class="modal-header"
                                                    style="background: linear-gradient(90deg, #f6c343, #f08a24); color: #fff; padding: 1rem 1.5rem;">
                                                    <h5 class="modal-title fw-semibold mb-0">Lời mời đang chờ</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Đóng"></button>
                                                </div>
                                                <div class="modal-body text-center py-4 px-4">
                                                    <p class="mb-3"><strong>Email:</strong><br>
                                                        <span
                                                            class="text-muted">{{ $pendingRapEmails[$rap->id] }}</span>
                                                    </p>
                                                    <p class="text-muted mb-0">Bạn có chắc chắn muốn hủy lời mời này?
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-between px-3 pb-3">
                                                    <button type="button"
                                                        class="btn btn-secondary btn-sm rounded-pill px-4"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <form action="{{ route('admin.invite.cancel') }}" method="POST"
                                                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy lời mời gửi đến {{ $pendingRapEmails[$rap->id] }}?')">
                                                        @csrf
                                                        <input type="hidden" name="rap_phim_id"
                                                            value="{{ $rap->id }}">
                                                        <input type="hidden" name="loai_quan_ly" value="2">
                                                        <button type="submit"
                                                            class="btn btn-poly btn-sm rounded-pill px-4">
                                                            <i class="fas fa-times-circle me-1"></i> Hủy lời mời
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach



                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kích hoạt tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(el) {
            new bootstrap.Tooltip(el);
        });

        // Hover tr nhẹ nhàng
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseover', () => row.style.transition =
                'background-color 0.4s ease, box-shadow 0.4s ease');
            row.addEventListener('mouseout', () => row.style.transition =
                'background-color 0.4s ease, box-shadow 0.4s ease');
        });
    });

    function toggleCancelBtn(id) {
        const form = document.getElementById('cancel-form-' + id);
        form.style.display = (form.style.display === 'none') ? 'inline-block' : 'none';
    }

    function confirmCancelInvite(form, email) {
        return confirm(`Bạn có chắc chắn muốn hủy lời mời đã gửi đến ${email}?`);
    }

    // Select2 cho Trạng thái Rạp
    $('#filterTrangThaiRap').select2({
        theme: 'bootstrap-5',
        placeholder: 'Tất cả trạng thái',
        allowClear: true,
        dropdownParent: $('body'),
        width: '100%'
    }).on('change', function() {
        var val = $(this).val();
        var selection = $(this).next('.select2-container').find('.select2-selection--single');

        // Xóa hết class trước
        selection.removeClass('is-active is-pause is-closed');

        // Add class theo giá trị
        if (val === 'đang hoạt động') {
            selection.addClass('is-active');
        } else if (val === 'bảo trì') {
            selection.addClass('is-pause');
        } else if (val === 'đã đóng') {
            selection.addClass('is-closed');
        }
    });

    // Select2 cho Trạng thái Quản lý Rạp
    $('#filterTrangThaiQLRap').select2({
        theme: 'bootstrap-5',
        placeholder: 'Trạng thái quản lý',
        allowClear: true,
        dropdownParent: $('body'),
        width: '100%'
    }).on('change', function() {
        var val = $(this).val();
        var selection = $(this).next('.select2-container').find('.select2-selection--single');

        // Xóa hết class trước
        selection.removeClass('is-active is-pause is-closed');

        // Add class theo giá trị
        if (val === 'chua_phan_cong') {
            selection.addClass('is-closed');
        } else if (val === 'dang_phan_cong') {
            selection.addClass('is-pause');
        } else if (val === 'da_phan_cong') {
            selection.addClass('is-active');
        }
    });

    // Event khi filter & search
    $('#searchTenRap, #searchTenQuanLyRap, #filterTrangThaiQLRap, #filterTrangThaiRap').on('input change', function() {
        performSearchRap();
    });

    // Hàm lọc realtime
    function performSearchRap() {
        const keywordRap = $('#searchTenRap').val().toLowerCase();
        const keywordQL = $('#searchTenQuanLyRap').val().toLowerCase();
        const trangThaiQL = $('#filterTrangThaiQLRap').val();
        const trangThai = $('#filterTrangThaiRap').val();

        let matchCount = 0;

        $('#rapTable tr.data-row').each(function() {
            const nameRap = $(this).data('ten-rap');
            const nameQL = $(this).data('ten-ql');
            const stateQL = $(this).data('trang-thai-ql').trim();
            const state = $(this).data('trang-thai').trim();

            const matchRap = nameRap.includes(keywordRap);
            const matchQL = nameQL.includes(keywordQL);
            const matchStateQL = !trangThaiQL || stateQL === trangThaiQL;
            const matchState = !trangThai || state === trangThai;

            if (matchRap && matchQL && matchStateQL && matchState) {
                $(this).show();
                matchCount++;
            } else {
                $(this).hide();
            }
        });

        // Xử lý "Không tìm thấy"
        if (matchCount === 0) {
            if ($('#rapTable tr.no-result').length === 0) {
                $('#rapTable').append(`
                <tr class="no-result">
                    <td colspan="7" class="text-center text-muted py-3">
                        <i class="fas fa-folder-open me-1"></i> Không tìm thấy kết quả phù hợp
                    </td>
                </tr>
            `);
            }
        } else {
            $('#rapTable .no-result').remove();
        }


    }
</script>
@endsection

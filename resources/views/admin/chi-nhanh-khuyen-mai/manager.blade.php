@extends('layouts.admin')

@section('title', 'Quản lý Khuyến mãi Chi nhánh')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-tags text-primary me-2"></i>
                        Quản lý Khuyến mãi
                    </h2>
                    <p class="text-muted mb-0">Chi nhánh: <strong>{{ $chiNhanh->ten_chi_nhanh }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Không cần tabs nữa, chỉ hiển thị khuyến mãi theo rạp -->

    <!-- Khuyến mãi theo rạp -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #28a745; color: white;">
                    <h5 class="mb-0" style="color: white !important;">
                        <i class="fas fa-building me-2"></i>
                        Gán khuyến mãi cho rạp cụ thể
                    </h5>
                </div>
                <div class="card-body">
                    @if($khuyenMaisCoTheGan->count() > 0)
                    <div class="row">
                        @foreach($khuyenMaisCoTheGan as $khuyenMai)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title text-success">{{ $khuyenMai->ten }}</h6>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($khuyenMai->mo_ta, 60) }}
                                    </p>

                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Giá trị</small>
                                            <strong class="text-success">
                                                {{ $khuyenMai->loai_giam_gia == 'phan_tram' ? $khuyenMai->gia_tri_giam.'%' : number_format($khuyenMai->gia_tri_giam).'đ' }}
                                            </strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Áp dụng</small>
                                            <span
                                                class="badge bg-secondary">{{ ucfirst($khuyenMai->ap_dung_cho) }}</span>
                                        </div>
                                    </div>

                                    <!-- Rạp đã gán -->
                                    <div class="mb-3 flex-grow-1">
                                        <h6 class="text-muted mb-2">
                                            <i class="fas fa-building me-1"></i>
                                            Rạp đã gán:
                                        </h6>
                                        <div class="assigned-cinemas" id="assigned-cinemas-{{ $khuyenMai->id }}">
                                            @if($khuyenMai->rapPhims->count() > 0)
                                            @foreach($khuyenMai->rapPhims as $rap)
                                            <span class="badge bg-primary me-1 mb-1">
                                                {{ $rap->ten_rap }}
                                                <button type="button" class="btn-close btn-close-white btn-sm ms-1"
                                                    onclick="removeFromCinema({{ $khuyenMai->id }}, {{ $rap->id }})"
                                                    title="Hủy gán"></button>
                                            </span>
                                            @endforeach
                                            @else
                                            <small class="text-muted fst-italic">Chưa gán cho rạp nào</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Nút hành động -->
                                    <div class="d-flex gap-2 mt-auto">
                                        <button class="btn btn-info btn-sm flex-fill"
                                            onclick="viewKhuyenMaiDetail({{ $khuyenMai->id }})">
                                            <i class="fas fa-eye me-1"></i>
                                            Xem chi tiết
                                        </button>
                                        <button class="btn btn-success btn-sm flex-fill"
                                            onclick="showAssignModal({{ $khuyenMai->id }}, '{{ $khuyenMai->ten }}')">
                                            <i class="fas fa-plus me-1"></i>
                                            Gán cho rạp
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có khuyến mãi nào có thể gán</h5>
                        <p class="text-muted">Hiện tại chưa có khuyến mãi nào có thể gán cho rạp cụ thể.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal gán khuyến mãi cho rạp -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-building me-2"></i>
                    Gán khuyến mãi cho rạp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    <input type="hidden" id="khuyenMaiId" name="khuyen_mai_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-success">Khuyến mãi:</label>
                        <p id="khuyenMaiName" class="h6 text-primary"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn rạp <span class="text-danger">*</span></label>


                        @if($rapPhims && $rapPhims->count() > 0)
                        <div class="row">
                            @foreach($rapPhims as $rap)
                            <div class="col-md-6 mb-2">
                                <div class="form-check p-2 border rounded">
                                    <input class="form-check-input" type="checkbox" name="rap_phim_ids[]"
                                        value="{{ $rap->id }}" id="rap_{{ $rap->id }}">
                                    <label class="form-check-label w-100" for="rap_{{ $rap->id }}">
                                        <strong>{{ $rap->ten_rap }}</strong>
                                        <br><small class="text-muted">{{ $rap->dia_chi }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Không có rạp nào trong chi nhánh này. Vui lòng liên hệ quản trị viên để thêm rạp.
                        </div>
                        @endif

                        <small class="text-muted">Chọn một hoặc nhiều rạp để gán khuyến mãi</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Hủy
                </button>
                <button type="button" class="btn btn-primary" onclick="directAssignPromotion()">
                    <i class="fas fa-bolt me-1"></i>
                    Gán trực tiếp
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentKhuyenMaiId = null;

    function showAssignModal(khuyenMaiId, khuyenMaiName) {
        currentKhuyenMaiId = khuyenMaiId;
        document.getElementById('khuyenMaiId').value = khuyenMaiId;
        document.getElementById('khuyenMaiName').textContent = khuyenMaiName;

        // Reset checkboxes
        document.querySelectorAll('input[name="rap_phim_ids[]"]').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.disabled = false;
        });

        // Load rạp đã được gán và disable chúng
        loadAssignedCinemas(khuyenMaiId);

        // Show modal
        new bootstrap.Modal(document.getElementById('assignModal')).show();
    }

    function loadAssignedCinemas(khuyenMaiId) {
        fetch(`/admin/chi-nhanh-promotion-manager/assigned-cinemas/${khuyenMaiId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    // Disable checkboxes cho các rạp đã được gán
                    data.data.forEach(rap => {
                        const checkbox = document.getElementById(`rap_${rap.id}`);
                        if (checkbox) {
                            checkbox.checked = true;
                            checkbox.disabled = true;
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading assigned cinemas:', error);
                // Không hiển thị lỗi cho user vì đây chỉ là load trạng thái
            });
    }





    // Gán khuyến mãi trực tiếp (bypass middleware)
    function directAssignPromotion() {
        const formData = new FormData();
        const khuyenMaiId = document.getElementById('khuyenMaiId').value;

        // Lấy các rạp được chọn
        const selectedCinemas = [];
        document.querySelectorAll('input[name="rap_phim_ids[]"]:checked').forEach(checkbox => {
            selectedCinemas.push(checkbox.value);
        });

        if (selectedCinemas.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn rạp!',
                text: 'Vui lòng chọn ít nhất một rạp để gán khuyến mãi'
            });
            return;
        }

        formData.append('khuyen_mai_id', khuyenMaiId);
        selectedCinemas.forEach(cinemaId => {
            formData.append('rap_phim_ids[]', cinemaId);
        });

        console.log('Direct assign promotion with:', {
            khuyen_mai_id: khuyenMaiId,
            selected_cinemas: selectedCinemas
        });

        // Show loading
        Swal.fire({
            title: 'Đang gán khuyến mãi...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/direct-assign-promotion', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => {
                console.log('Direct assign response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Direct assign response:', data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: data.message,
                        timer: 3000
                    }).then(() => {
                        // Đóng modal và refresh trang
                        $('#assignModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gán thất bại!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Direct assign error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: `Không thể gán: ${error.message}`
                });
            });
    }



    // Xem chi tiết khuyến mãi
    function viewKhuyenMaiDetail(khuyenMaiId) {
        window.open(`/admin/chi-nhanh-khuyen-mai/${khuyenMaiId}`, '_blank');
    }

    function removeFromCinema(khuyenMaiId, rapPhimId) {
        Swal.fire({
            title: 'Xác nhận hủy gán',
            text: 'Bạn có chắc chắn muốn hủy gán khuyến mãi cho rạp này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Hủy gán',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('khuyen_mai_id', khuyenMaiId);
                formData.append('rap_phim_id', rapPhimId);

                fetch('/test-remove-bypass', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: data.message || 'Có lỗi xảy ra khi hủy gán khuyến mãi.'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi hủy gán khuyến mãi.'
                        });
                    });
            }
        });
    }



    // Animation cho cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s, transform 0.5s';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection

@section('styles')
<style>
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .assigned-cinemas .badge {
        font-size: 0.8em;
    }

    .form-check {
        transition: background-color 0.2s;
    }

    .form-check:hover {
        background-color: #f8f9fa;
    }

    .modal-lg {
        max-width: 800px;
    }

    .card-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    .card-header h5 {
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
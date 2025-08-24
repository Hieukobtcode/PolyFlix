@extends('layouts.admin')

@section('title', 'Quản Lý Khuyến Mãi Rạp - ' . $chiNhanh->ten_chi_nhanh)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Quản Lý Khuyến Mãi Rạp - {{ $chiNhanh->ten_chi_nhanh }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Alert Messages -->
                    <div id="alert-container"></div>

                    @if($khuyenMais->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Hiện tại không có khuyến mãi nào được gán cho chi nhánh này.
                    </div>
                    @else
                    <div class="row">
                        @foreach($khuyenMais as $khuyenMai)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-gradient-primary text-white">
                                    <h6 class="mb-0 fw-bold">
                                        {{ $khuyenMai->ma_khuyen_mai }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title text-dark fw-bold">{{ $khuyenMai->ten }}</h6>

                                    <div class="mb-3">
                                        <small class="text-muted">Loại giảm giá:</small>
                                        <br>
                                        @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                        <span class="badge bg-success">{{ $khuyenMai->gia_tri_giam }}%</span>
                                        @else
                                        <span
                                            class="badge bg-info">{{ number_format($khuyenMai->gia_tri_giam) }}đ</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Áp dụng cho:</small>
                                        <br>
                                        @switch($khuyenMai->ap_dung_cho)
                                        @case('ve')
                                        <span class="badge bg-primary">Vé phim</span>
                                        @break
                                        @case('do_an')
                                        <span class="badge bg-warning">Đồ ăn</span>
                                        @break
                                        @default
                                        <span class="badge bg-secondary">Tất cả</span>
                                        @endswitch
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Thời hạn:</small>
                                        <br>
                                        <small class="text-info">
                                            {{ \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc)->format('d/m/Y') }}
                                        </small>
                                    </div>

                                    <!-- Hiển thị rạp đã được gán -->
                                    <div class="mb-3">
                                        <small class="text-muted">Rạp đã gán:</small>
                                        <div class="assigned-raps-{{ $khuyenMai->id }}">
                                            @if($khuyenMai->rapPhims->count() > 0)
                                            @foreach($khuyenMai->rapPhims as $rap)
                                            <span class="badge bg-success me-1 mb-1">
                                                {{ $rap->ten_rap }}
                                                <button type="button" class="btn-close btn-close-white btn-sm ms-1"
                                                    onclick="removeFromRap({{ $khuyenMai->id }}, {{ $rap->id }})"
                                                    title="Hủy gán"></button>
                                            </span>
                                            @endforeach
                                            @else
                                            <small class="text-muted fst-italic">Chưa gán cho rạp nào</small>
                                            @endif
                                        </div>
                                    </div>

                                    <button class="btn btn-primary btn-sm w-100"
                                        onclick="showAssignModal({{ $khuyenMai->id }}, '{{ $khuyenMai->ten }}')">
                                        <i class="fas fa-plus me-1"></i>
                                        Gán cho rạp
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal gán khuyến mãi cho rạp -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-tags me-2"></i>
                    Gán khuyến mãi cho rạp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="assignForm">
                    <input type="hidden" id="khuyenMaiId" name="khuyen_mai_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Khuyến mãi:</label>
                        <p id="khuyenMaiName" class="text-primary fw-bold"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chọn rạp <span class="text-danger">*</span></label>
                        <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                            @foreach($rapPhims as $rap)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rap_phim_ids[]"
                                    value="{{ $rap->id }}" id="rap_{{ $rap->id }}">
                                <label class="form-check-label" for="rap_{{ $rap->id }}">
                                    {{ $rap->ten_rap }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Chọn một hoặc nhiều rạp để gán khuyến mãi</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="submitAssign()">
                    <i class="fas fa-save me-1"></i>
                    Gán khuyến mãi
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
        });

        // Load rạp đã được gán
        loadAssignedRaps(khuyenMaiId);

        new bootstrap.Modal(document.getElementById('assignModal')).show();
    }

    function loadAssignedRaps(khuyenMaiId) {
        fetch(`/admin/chi-nhanh-rap-khuyen-mai/assigned-raps/${khuyenMaiId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Check các rạp đã được gán
                    data.data.forEach(rap => {
                        const checkbox = document.getElementById(`rap_${rap.id}`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function submitAssign() {
        const form = document.getElementById('assignForm');
        const formData = new FormData(form);

        // Kiểm tra ít nhất một rạp được chọn
        const checkedRaps = document.querySelectorAll('input[name="rap_phim_ids[]"]:checked');
        if (checkedRaps.length === 0) {
            showAlert('warning', 'Vui lòng chọn ít nhất một rạp!');
            return;
        }

        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';
        button.disabled = true;

        fetch('/admin/chi-nhanh-rap-khuyen-mai/assign', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();

                    // Reload trang để cập nhật hiển thị
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Có lỗi xảy ra khi gán khuyến mãi!');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }

    function removeFromRap(khuyenMaiId, rapPhimId) {
        if (!confirm('Bạn có chắc chắn muốn hủy gán khuyến mãi khỏi rạp này?')) {
            return;
        }

        fetch('/admin/chi-nhanh-rap-khuyen-mai/remove', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    khuyen_mai_id: khuyenMaiId,
                    rap_phim_id: rapPhimId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    // Reload trang để cập nhật hiển thị
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Có lỗi xảy ra!');
            });
    }

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
        alertContainer.innerHTML = alertHTML;

        // Auto hide after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                bootstrap.Alert.getOrCreateInstance(alert).close();
            }
        }, 5000);
    }
</script>
@endsection

@section('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-size: 0.75em;
    }

    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .form-check {
        padding: 0.25rem 0;
    }

    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
@endsection
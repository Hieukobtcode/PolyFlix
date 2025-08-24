@extends('layouts.admin')
@section('content')
    <div class="row">
        <!-- Tổng quan suất chiếu -->
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="row g-1 w-100">
                <div class="col-md-3">
                    <div class="card warning-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-movie fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $tongSuatChieu }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Tổng suất chiếu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card info-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-ticket fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $tongVeBan }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Vé bán được</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card danger-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-currency-dollar fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14">
                                {{ number_format($tongDoanhThu, 0, ',', '.') }} VNĐ
                            </h5>
                            <p class="opacity-50 mb-0">Doanh thu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card info-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-percentage fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $tyLeLapDayGhe }}% <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Tỷ lệ lấp đầy ghế</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="d-flex col-12 mb-4 gap-3 align-items-center">
            @if (Auth::user()->vaiTro->ten_vai_tro === 'Admin Chi Nhánh' && $danhSachChiNhanh->count() == 1)
                <div class="flex-grow-1">
                    <h6 class="mb-0">Chi nhánh: <strong>{{ $danhSachChiNhanh->first()->ten_chi_nhanh }}</strong></h6>
                    <input type="hidden" id="branch-select" value="{{ $danhSachChiNhanh->first()->id }}">
                </div>
            @else
                <div class="flex-grow-1">
                    <select name="branch_id" id="branch-select" class="form-select">
                        <option value="">Tất cả chi nhánh</option>
                        @foreach ($danhSachChiNhanh as $chiNhanh)
                            <option value="{{ $chiNhanh->id }}" {{ request('branch_id') == $chiNhanh->id ? 'selected' : '' }}>
                                {{ $chiNhanh->ten_chi_nhanh }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex-grow-1">
                <select name="rap_id" id="rap-select" class="form-select">
                    <option value="">Tất cả rạp chiếu</option>
                    @foreach ($danhSachRap as $rap)
                        <option value="{{ $rap->id }}" {{ request('rap_id') == $rap->id ? 'selected' : '' }}>
                            {{ $rap->ten_rap }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow-1">
                <input type="date" id="tu-ngay" class="form-control" value="{{ $tuNgay ?? '' }}" placeholder="Từ ngày">
            </div>

            <div class="flex-grow-1">
                <input type="date" id="den-ngay" class="form-control" value="{{ $denNgay ?? '' }}" placeholder="Đến ngày">
            </div>
        </div>

        <!-- Biểu đồ số vé bán được -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body pb-2">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h4 class="card-title mb-1">Số vé bán được theo thời gian</h4>
                        <select id="chon-thoi-gian" class="form-select fw-bold w-auto shadow-none">
                            <option value="week">Tuần</option>
                            <option value="month">Tháng</option>
                            <option value="custom">Tùy chỉnh</option>
                        </select>
                    </div>
                    <canvas id="veBanChart" class="mx-n6" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ tỷ lệ suất chiếu -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title mb-1">Tỷ lệ suất chiếu theo trạng thái</h4>
                    <canvas id="trangThaiSuatChieuChart" class="my-8" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 suất chiếu -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title mb-1">Top 5 suất chiếu có doanh thu cao nhất</h4>
                    @if ($top5SuatChieu->isEmpty())
                        <p class="text-muted">Không có dữ liệu suất chiếu.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Phim</th>
                                    <th>Thời gian</th>
                                    <th>Phòng chiếu</th>
                                    <th>Rạp</th>
                                    <th>Số vé</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top5SuatChieu as $item)
                                    <tr>
                                        <td>{{ Str::limit($item->ten_phim, 20, '...') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->thoi_gian_bat_dau)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $item->ten_phong }}</td>
                                        <td>{{ $item->ten_rap }}</td>
                                        <td>{{ $item->so_ve_ban }}</td>
                                        <td>{{ number_format($item->tong_doanh_thu, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top 5 phim có số suất chiếu nhiều nhất -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title mb-1">Top 5 phim có số suất chiếu nhiều nhất</h4>
                    @if ($top5PhimSuatChieu->isEmpty())
                        <p class="text-muted">Không có dữ liệu phim.</p>
                    @else
                        @foreach ($top5PhimSuatChieu as $item)
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-circle text-primary fs-4 me-2"></i>
                                    <p class="mb-0">{{ Str::limit($item->ten_phim, 30, '...') }}</p>
                                </div>
                                <p class="mb-0">{{ $item->so_suat_chieu }} suất chiếu</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const branchSelect = document.getElementById('branch-select');
            const rapSelect = document.getElementById('rap-select');
            const chonThoiGian = document.getElementById('chon-thoi-gian');
            const tuNgay = document.getElementById('tu-ngay');
            const denNgay = document.getElementById('den-ngay');
            let veBanChart = null;
            let trangThaiSuatChieuChart = null;

            function updateCharts() {
                const branchId = branchSelect ? branchSelect.value : '';
                const rapId = rapSelect ? rapSelect.value : '';
                const loai = chonThoiGian ? chonThoiGian.value : 'week';
                const tuNgayVal = tuNgay ? tuNgay.value : '';
                const denNgayVal = denNgay ? denNgay.value : '';

                // Cập nhật biểu đồ số vé bán được
                let url = `/api/doanh-thu-${loai}?branch_id=${branchId}&rap_id=${rapId}`;
                if (loai === 'custom' && tuNgayVal && denNgayVal) {
                    url += `&tu_ngay=${tuNgayVal}&den_ngay=${denNgayVal}`;
                }

                fetch(url)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (veBanChart) veBanChart.destroy();

                        const ctxBar = document.getElementById('veBanChart');
                        if (!ctxBar) {
                            console.error('Canvas veBanChart không tồn tại!');
                            return;
                        }

                        if (!data.labels || data.labels.length === 0) {
                            ctxBar.parentElement.innerHTML =
                                '<p class="text-muted text-center">Không có dữ liệu vé bán.</p>';
                            return;
                        }

                        veBanChart = new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Số vé bán',
                                    data: data.so_ve,
                                    backgroundColor: '#4F46E5',
                                    borderColor: '#4F46E5',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return `Số vé: ${context.parsed.y}`;
                                            }
                                        }
                                    },
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: loai === 'week' ? 'Số vé bán theo tuần' : loai ===
                                            'month' ? 'Số vé bán theo tháng' : 'Số vé bán theo ngày',
                                        font: {
                                            size: 16
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: loai === 'week' ? 'Tuần' : loai === 'month' ?
                                                'Tháng' : 'Ngày'
                                        },
                                        ticks: {
                                            maxRotation: 45,
                                            minRotation: 45
                                        }
                                    },
                                    y: {
                                        title: {
                                            display: true,
                                            text: 'Số vé'
                                        },
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải dữ liệu vé bán:', error);
                        const ctxBar = document.getElementById('veBanChart');
                        if (ctxBar) {
                            ctxBar.parentElement.innerHTML =
                                '<p class="text-muted text-center">Lỗi tải dữ liệu vé bán.</p>';
                        }
                    });

                // Cập nhật biểu đồ tỷ lệ suất chiếu
                fetch(
                        `/api/ty-le-suat-chieu?branch_id=${branchId}&rap_id=${rapId}&tu_ngay=${tuNgayVal}&den_ngay=${denNgayVal}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (trangThaiSuatChieuChart) trangThaiSuatChieuChart.destroy();

                        const ctxPie = document.getElementById('trangThaiSuatChieuChart');
                        if (!ctxPie) {
                            console.error('Canvas trangThaiSuatChieuChart không tồn tại!');
                            return;
                        }

                        if (!data.labels || data.labels.length === 0) {
                            ctxPie.parentElement.innerHTML =
                                '<p class="text-muted text-center">Không có dữ liệu suất chiếu.</p>';
                            return;
                        }

                        trangThaiSuatChieuChart = new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Số suất chiếu',
                                    data: data.values,
                                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return `${context.label}: ${context.parsed} suất`;
                                            }
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Tỷ lệ suất chiếu theo trạng thái',
                                        font: {
                                            size: 16
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải dữ liệu tỷ lệ suất chiếu:', error);
                        const ctxPie = document.getElementById('trangThaiSuatChieuChart');
                        if (ctxPie) {
                            ctxPie.parentElement.innerHTML =
                                '<p class="text-muted text-center">Lỗi tải dữ liệu tỷ lệ suất chiếu.</p>';
                        }
                    });
            }

            // Xử lý thay đổi chi nhánh
            if (branchSelect) {
                branchSelect.addEventListener('change', function() {
                    const branchId = this.value;
                    const currentUrl = new URL(window.location.href);
                    if (branchId) {
                        currentUrl.searchParams.set('branch_id', branchId);
                    } else {
                        currentUrl.searchParams.delete('branch_id');
                    }
                    currentUrl.searchParams.delete('rap_id');
                    window.location.href = currentUrl.toString();
                });
            }

            // Xử lý thay đổi rạp
            if (rapSelect) {
                rapSelect.addEventListener('change', function() {
                    const rapId = this.value;
                    const currentUrl = new URL(window.location.href);
                    if (rapId) {
                        currentUrl.searchParams.set('rap_id', rapId);
                    } else {
                        currentUrl.searchParams.delete('rap_id');
                    }
                    window.location.href = currentUrl.toString();
                });
            }

            // Xử lý thay đổi thời gian
            if (chonThoiGian) {
                chonThoiGian.addEventListener('change', function() {
                    const tuNgayVal = tuNgay ? tuNgay.value : '';
                    const denNgayVal = denNgay ? denNgay.value : '';
                    if (this.value === 'custom' && (!tuNgayVal || !denNgayVal)) {
                        alert('Vui lòng chọn khoảng thời gian!');
                        this.value = 'week';
                        return;
                    }
                    updateCharts();
                });
            }

            // Xử lý thay đổi ngày
            if (tuNgay && denNgay) {
                const updateOnDateChange = () => {
                    if (chonThoiGian.value === 'custom') {
                        const currentUrl = new URL(window.location.href);
                        if (tuNgay.value && denNgay.value) {
                            currentUrl.searchParams.set('tu_ngay', tuNgay.value);
                            currentUrl.searchParams.set('den_ngay', denNgay.value);
                        } else {
                            currentUrl.searchParams.delete('tu_ngay');
                            currentUrl.searchParams.delete('den_ngay');
                        }
                        window.location.href = currentUrl.toString();
                    }
                };
                tuNgay.addEventListener('change', updateOnDateChange);
                denNgay.addEventListener('change', updateOnDateChange);
            }

            // Tải dữ liệu ban đầu
            updateCharts();
        });
    </script>
@endsection

@extends('layouts.admin')
@section('content')
    <style>
        #netsells,
        #tyleGheChart {
            max-height: 300px;
            width: 100%;
        }

        .card-body {
            overflow: visible !important;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="row g-1 w-100">
                <div class="col-md-3">
                    <div class="card warning-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-building-community fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $soChiNhanhs }}<span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Chi nhánh</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card info-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-building fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $soRaps }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Rạp chiếu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card danger-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-screen-share fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14">
                                {{ $soPhongChieus }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Phòng chiếu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card info-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-users fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $soNguoiDungs }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Người dùng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex col-12 mb-4 gap-3">
            <div class="d-flex gap-2 w-100">
                <select name="branch_id" id="branch-select" class="form-select w-100">
                    <option value="">Tất cả chi nhánh</option>
                    @foreach ($danhSachChiNhanh as $chiNhanh)
                        <option value="{{ $chiNhanh->id }}" {{ request('branch_id') == $chiNhanh->id ? 'selected' : '' }}>
                            {{ $chiNhanh->ten_chi_nhanh }}
                        </option>
                    @endforeach
                </select>
            </div>
            <select name="rap_id" id="rap-select" class="form-select w-100">
                <option value="">Tất cả rạp chiếu</option>
                @foreach ($danhSachRap as $rap)
                    <option value="{{ $rap->id }}" {{ request('rap_id') == $rap->id ? 'selected' : '' }}>
                        {{ $rap->ten_rap }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-3 d-flex align-items-stretch">
            <div class="d-block w-100">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Top doanh thu</h4>
                            </div>
                        </div>
                        @if ($top5ChiNhanh->isEmpty())
                            <p class="text-muted">Không có doanh thu.</p>
                        @else
                            @foreach ($top5ChiNhanh as $item)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-circle text-primary fs-4 me-2"></i>
                                        <p class="mb-0">{{ $item->ten_chi_nhanh }}</p>
                                    </div>
                                    <p class="mb-0">
                                        ({{ $item->phan_tram }}%)
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Top doanh thu phim</h4>
                            </div>
                        </div>
                        @if ($topDoanhThuPhimHeThong->isEmpty())
                            <p class="text-muted">Không có doanh thu.</p>
                        @else
                            @foreach ($topDoanhThuPhimHeThong as $item)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-circle text-primary fs-4 me-2"></i>
                                        <p class="mb-0">{{ Str::limit($item->ten_phim, 30, '...') }}</p>
                                    </div>
                                    <p class="mb-0">
                                        ({{ $item->phan_tram }}%)
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body pb-2">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">Tổng doanh thu</h4>
                        </div>
                        <select id="chonThoiGian" class="form-select fw-bold w-auto shadow-none">
                            <option value="week">Tuần</option>
                            <option value="month">Tháng</option>
                        </select>
                    </div>
                    <canvas id="netsells" class="mx-n6" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-3 d-flex align-items-stretch">
            <div class="d-block w-100">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Tỷ lệ lấp đầy ghế</h4>
                            </div>
                            <div>
                                <span
                                    class="badge rounded-pill bg-success-subtle text-success border-success border text-end">{{ $tyLeLapDayGhe }}%</span>
                            </div>
                        </div>
                        <canvas id="tyleGheChart" class="my-8" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const branchSelect = document.getElementById('branch-select');
            const rapSelect = document.getElementById('rap-select');
            const chonThoiGian = document.getElementById('chonThoiGian');
            let barChart = null;
            let doughnutChart = null;

            function updateCharts() {
                const branchId = branchSelect ? branchSelect.value : '';
                const rapId = rapSelect ? rapSelect.value : '';
                const loai = chonThoiGian ? chonThoiGian.value : 'week';

                // Cập nhật biểu đồ doanh thu
                fetch(`/api/doanh-thu-${loai}?branch_id=${branchId}&rap_id=${rapId}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (barChart) barChart.destroy();

                        const ctxBar = document.getElementById('netsells');
                        if (!ctxBar) {
                            console.error('Canvas netsells không tồn tại!');
                            return;
                        }

                        // Nếu không có dữ liệu, hiển thị thông báo
                        if (!data.labels || data.labels.length === 0) {
                            ctxBar.parentElement.innerHTML =
                                '<p class="text-muted text-center">Không có dữ liệu doanh thu.</p>';
                            return;
                        }

                        barChart = new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Doanh thu',
                                    data: data.values,
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
                                                return new Intl.NumberFormat('vi-VN', {
                                                    style: 'currency',
                                                    currency: 'VND'
                                                }).format(context.parsed.y);
                                            }
                                        }
                                    },
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: loai === 'week' ? 'Doanh thu theo tuần' :
                                            'Doanh thu theo tháng',
                                        font: {
                                            size: 16
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: loai === 'week' ? 'Tuần' : 'Tháng'
                                        },
                                        ticks: {
                                            maxRotation: 45,
                                            minRotation: 45
                                        }
                                    },
                                    y: {
                                        title: {
                                            display: true,
                                            text: 'Doanh thu (VND)'
                                        },
                                        ticks: {
                                            callback: function(val) {
                                                return new Intl.NumberFormat('vi-VN', {
                                                    style: 'currency',
                                                    currency: 'VND'
                                                }).format(val);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải dữ liệu doanh thu:', error);
                        const ctxBar = document.getElementById('netsells');
                        if (ctxBar) {
                            ctxBar.parentElement.innerHTML =
                                '<p class="text-muted text-center">Lỗi tải dữ liệu doanh thu.</p>';
                        }
                    });

                // Cập nhật biểu đồ tỷ lệ lấp đầy ghế
                fetch(`/api/ty-le-lap-day-ghe?branch_id=${branchId}&rap_id=${rapId}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (doughnutChart) doughnutChart.destroy();

                        const ctxDoughnut = document.getElementById('tyleGheChart');
                        if (!ctxDoughnut) {
                            console.error('Canvas tyleGheChart không tồn tại!');
                            return;
                        }

                        doughnutChart = new Chart(ctxDoughnut, {
                            type: 'doughnut',
                            data: {
                                labels: ['Lấp đầy', 'Còn trống'],
                                datasets: [{
                                    label: 'Tỷ lệ ghế',
                                    data: [data.tyLeLapDayGhe, 100 - data.tyLeLapDayGhe],
                                    backgroundColor: ['#6C5DD3', '#E0E0E0'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                cutout: '60%',
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.label + ": " + context.parsed
                                                    .toFixed(2) + "%";
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // Cập nhật badge tỷ lệ lấp đầy ghế
                        const badge = document.querySelector('.badge.text-success');
                        if (badge) {
                            badge.textContent = `${data.tyLeLapDayGhe}%`;
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải dữ liệu tỷ lệ lấp đầy ghế:', error);
                        const ctxDoughnut = document.getElementById('tyleGheChart');
                        if (ctxDoughnut) {
                            ctxDoughnut.parentElement.innerHTML =
                                '<p class="text-muted text-center">Lỗi tải dữ liệu tỷ lệ lấp đầy ghế.</p>';
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
                chonThoiGian.addEventListener('change', updateCharts);
            }

            // Tải dữ liệu ban đầu
            updateCharts();
        });
    </script>
@endsection

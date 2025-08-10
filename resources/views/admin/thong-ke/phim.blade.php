@extends('layouts.admin')
@section('content')
    <div class="row">
        <!-- Tổng quan phim -->
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="row g-1 w-100">
                <div class="col-md-3">
                    <div class="card warning-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-movie fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $tongSoPhim }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Tổng số phim</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card info-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-calendar fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14 text-nowrap">
                                {{ $tongSuatChieu }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Tổng suất chiếu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card danger-card overflow-hidden text-bg-primary">
                        <div class="card-body p-4">
                            <div class="mb-7">
                                <i class="ti ti-ticket fs-8"></i>
                            </div>
                            <h5 class="text-white fw-bold fs-14">
                                {{ $tongVeBan }} <span class="fs-2 fw-light"></span>
                            </h5>
                            <p class="opacity-50 mb-0">Vé bán được</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card info-card overflow-hidden text-bg-primary">
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
            </div>
        </div>

        <!-- Bộ lọc -->
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
            <input type="date" id="tu-ngay" class="form-control" value="{{ $tuNgay ?? '' }}">
            <input type="date" id="den-ngay" class="form-control" value="{{ $denNgay ?? '' }}">
        </div>

        <!-- Biểu đồ doanh thu top 5 phim -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body pb-2">
                    <h4 class="card-title mb-1">Doanh thu top 5 phim</h4>
                    <canvas id="topPhimChart" class="mx-n6" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ tỷ lệ doanh thu theo thể loại -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title mb-1">Tỷ lệ doanh thu theo thể loại</h4>
                    <canvas id="theLoaiPhimChart" class="my-8" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 phim có doanh thu cao nhất -->
        <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title mb-1">Top 5 phim có doanh thu cao nhất</h4>
                    @if ($top5PhimDoanhThu->isEmpty())
                        <p class="text-muted">Không có dữ liệu phim.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Phim</th>
                                    <th>Thể loại</th>
                                    <th>Số vé</th>
                                    <th>Doanh thu</th>
                                    <th>Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top5PhimDoanhThu as $item)
                                    <tr>
                                        <td>{{ Str::limit($item->ten_phim, 20, '...') }}</td>
                                        <td>{{ $item->the_loai }}</td>
                                        <td>{{ $item->so_ve_ban }}</td>
                                        <td>{{ number_format($item->tong_doanh_thu, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ $item->phan_tram }}%</td>
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Phim</th>
                                    <th>Thể loại</th>
                                    <th>Số suất chiếu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top5PhimSuatChieu as $item)
                                    <tr>
                                        <td>{{ Str::limit($item->ten_phim, 20, '...') }}</td>
                                        <td>{{ $item->the_loai }}</td>
                                        <td>{{ $item->so_suat_chieu }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            const tuNgay = document.getElementById('tu-ngay');
            const denNgay = document.getElementById('den-ngay');
            let topPhimChart = null;
            let theLoaiPhimChart = null;

            function updateCharts() {
                const branchId = branchSelect ? branchSelect.value : '';
                const rapId = rapSelect ? rapSelect.value : '';
                const tuNgayVal = tuNgay ? tuNgay.value : '';
                const denNgayVal = denNgay ? denNgay.value : '';

                // Cập nhật biểu đồ top 5 phim
                fetch(
                        `/api/ty-le-doanh-thu-phim?branch_id=${branchId}&rap_id=${rapId}&tu_ngay=${tuNgayVal}&den_ngay=${denNgayVal}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (topPhimChart) topPhimChart.destroy();

                        const ctxBar = document.getElementById('topPhimChart');
                        if (!ctxBar) {
                            console.error('Canvas topPhimChart không tồn tại!');
                            return;
                        }

                        if (!data.labels || data.labels.length === 0) {
                            ctxBar.parentElement.innerHTML =
                                '<p class="text-muted text-center">Không có dữ liệu doanh thu phim.</p>';
                            return;
                        }

                        topPhimChart = new Chart(ctxBar, {
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
                                        text: 'Doanh thu top 5 phim',
                                        font: {
                                            size: 16
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Phim'
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
                        console.error('Lỗi khi tải dữ liệu doanh thu phim:', error);
                        const ctxBar = document.getElementById('topPhimChart');
                        if (ctxBar) {
                            ctxBar.parentElement.innerHTML =
                                '<p class="text-muted text-center">Lỗi tải dữ liệu doanh thu phim.</p>';
                        }
                    });

                // Cập nhật biểu đồ tỷ lệ doanh thu theo thể loại
                fetch(
                        `/api/ty-le-the-loai-phim?branch_id=${branchId}&rap_id=${rapId}&tu_ngay=${tuNgayVal}&den_ngay=${denNgayVal}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                        return res.json();
                    })
                    .then(data => {
                        if (theLoaiPhimChart) theLoaiPhimChart.destroy();

                        const ctxPie = document.getElementById('theLoaiPhimChart');
                        if (!ctxPie) {
                            console.error('Canvas theLoaiPhimChart không tồn tại!');
                            return;
                        }

                        if (!data.labels || data.labels.length === 0) {
                            ctxPie.parentElement.innerHTML =
                                '<p class="text-muted text-center">Không có dữ liệu doanh thu theo thể loại.</p>';
                            return;
                        }

                        theLoaiPhimChart = new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Doanh thu theo thể loại',
                                    data: data.values,
                                    backgroundColor: ['#4F46E5', '#10B981', '#F59E0B',
                                        '#EF4444', '#6B7280'
                                    ],
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
                                                return `${context.label}: ${new Intl.NumberFormat('vi-VN', {
                                                    style: 'currency',
                                                    currency: 'VND'
                                                }).format(context.parsed)}`;
                                            }
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Tỷ lệ doanh thu theo thể loại',
                                        font: {
                                            size: 16
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải dữ liệu tỷ lệ thể loại phim:', error);
                        const ctxPie = document.getElementById('theLoaiPhimChart');
                        if (ctxPie) {
                            ctxPie.parentElement.innerHTML =
                                '<p class="text-muted text-center">Lỗi tải dữ liệu tỷ lệ thể loại phim.</p>';
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
                        currentUrl.searchParams.set('rap_id', $rapId);
                    } else {
                        currentUrl.searchParams.delete('rap_id');
                    }
                    window.location.href = currentUrl.toString();
                });
            }

            // Xử lý thay đổi ngày
            if (tuNgay && denNgay) {
                const updateOnDateChange = () => {
                    const currentUrl = new URL(window.location.href);
                    if (tuNgay.value && denNgay.value) {
                        currentUrl.searchParams.set('tu_ngay', tuNgay.value);
                        currentUrl.searchParams.set('den_ngay', denNgay.value);
                    } else {
                        currentUrl.searchParams.delete('tu_ngay');
                        currentUrl.searchParams.delete('den_ngay');
                    }
                    window.location.href = currentUrl.toString();
                };
                tuNgay.addEventListener('change', updateOnDateChange);
                denNgay.addEventListener('change', updateOnDateChange);
            }

            // Tải dữ liệu ban đầu
            updateCharts();
        });
    </script>
@endsection

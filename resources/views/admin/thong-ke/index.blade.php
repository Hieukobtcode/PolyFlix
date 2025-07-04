@extends('layouts.admin')
@section('content')
    {{-- <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Chào mừng đến PolyFix</h5>
                <h6 class="text-white fs-2 mb-0">No Seat, No Ch!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div> --}}

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
                            <p class="opacity-50 mb-0 ">Chi nhánh</p>
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

        @php
            $rapsTheoChiNhanh = collect();
            if (request('branch_id')) {
                $rapsTheoChiNhanh = $danhSachRap->where('chi_nhanh_id', request('branch_id'));
            }
        @endphp

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
                @foreach ($rapsTheoChiNhanh as $rap)
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
                                        {{ $item->phan_tram }}%
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
                                        {{ $item->phan_tram }}%
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

                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-1">Customers</h4>
                                <p class="card-subtitle">Last 7 Days</p>
                            </div>
                            <div>
                                <h4 class="card-title mb-1 text-end">6,380</h4>
                                <span
                                    class="badge rounded-pill bg-success-subtle text-success border-success border text-end">+26.5%</span>
                            </div>
                        </div>
                        <div id="customers" class="my-5"></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0">April 07 - April 14</p>
                            <p class="mb-0">6,380</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0">Last Week</p>
                            <p class="mb-0">4,298</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-lg-3 d-flex align-items-stretch">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-end justify-content-between">
                            <div>
                                <h4 class="mb-0 card-title fs-6">2,545</h4>
                                <p class="card-subtitle">Followers</p>
                            </div>
                            <span class="text-success fw-normal">+1.20%</span>
                        </div>
                    </div>
                    <div id="widgest-chart-1"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 d-flex align-items-stretch">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-end justify-content-between mb-3">
                            <div>
                                <h4 class="mb-0 card-title fs-6">15,480</h4>
                                <p class="card-subtitle">Views</p>
                            </div>
                            <span class="text-danger fw-normal">-4.150%</span>
                        </div>
                        <div id="widgest-chart-2" class="mx-n2"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 d-flex align-items-stretch">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-end justify-content-between">
                            <div>
                                <h4 class="mb-0 card-title fs-6">2,545</h4>
                                <p class="card-subtitle">Earned</p>
                            </div>
                            <span class="text-success fw-normal">+1.20%</span>
                        </div>
                    </div>
                    <div id="widgest-chart-3"></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 d-flex align-items-stretch">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body p-4">
                        <div class="mb-7 pb-8">
                            <h4 class="mb-0 card-title fs-6">$78,298</h4>
                            <p class="card-subtitle">Total Earning</p>
                        </div>
                        <div id="widgest-chart-4" class="mx-n2"></div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-8">
                            <h4 class="card-title mb-0">Current Value</h4>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-primary">Buy</button>
                                <button class="btn btn-outline-primary">Sell</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-stretch">
                                <div class="card w-100 position-relative overflow-hidden border shadow-none mb-7 mb-lg-0">
                                    <div class="card-body">
                                        <div id="widgest-chart-5" class="mx-n4"></div>
                                        <div class="d-flex align-items-end justify-content-between mt-7">
                                            <div>
                                                <p class="mb-1">Income</p>
                                                <h4 class="mb-0 fw-semibold">$25,260</h4>
                                            </div>
                                            <span class="text-success fw-normal">+1.20%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-stretch">
                                <div class="card w-100 position-relative overflow-hidden border shadow-none mb-7 mb-lg-0">
                                    <div class="card-body">
                                        <div id="widgest-chart-6" class="mx-n4"></div>
                                        <div class="d-flex align-items-end justify-content-between mt-7">
                                            <div>
                                                <p class="mb-1">Expance</p>
                                                <h4 class="mb-0 fw-semibold">$12,260</h4>
                                            </div>
                                            <span class="text-success fw-normal">+4.25%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-stretch">
                                <div class="card w-100 position-relative overflow-hidden border shadow-none mb-7 mb-lg-0">
                                    <div class="card-body">
                                        <div id="current-year"></div>
                                        <div class="d-flex align-items-end justify-content-between mt-7">
                                            <div>
                                                <p class="mb-1">Current Year</p>
                                                <h4 class="mb-0 fw-semibold">$98,260</h4>
                                            </div>
                                            <span class="text-success fw-normal">+2.5%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card w-100 position-relative">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="card-title mb-9">Yearly Breakup</h4>
                                <h4 class="fw-semibold mb-2">$36,358</h4>
                                <div class="d-flex align-items-center mb-7 pb-8">
                                    <span
                                        class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-arrow-up-left text-success"></i>
                                    </span>
                                    <p class="text-dark me-1 fs-3 mb-0">+9%</p>
                                    <p class="fs-3 mb-0">last year</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="me-4">
                                        <span class="round-8 text-bg-primary rounded-circle me-2 d-inline-block"></span>
                                        <span class="fs-2">2022</span>
                                    </div>
                                    <div>
                                        <span class="round-8 bg-primary-subtle rounded-circle me-2 d-inline-block"></span>
                                        <span class="fs-2">2021</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-center">
                                    <div id="breakup" class="me-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body pb-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title mb-0"> Monthly Earnings </h4>
                            <div class="p-2 bg-primary-subtle rounded-1 d-inline-block">
                                <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/svgs/icon-master-card-2.svg"
                                    alt="matdash-img" class="img-fluid" width="24" height="24">
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-7 pb-8">
                            <h4 class="fw-semibold mb-0 fs-7">$6,820</h4>
                            <div class="d-flex align-items-center">
                                <span
                                    class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-left text-success"></i>
                                </span>
                                <p class="text-muted me-1 fs-3 mb-0">+9%</p>
                            </div>
                        </div>

                    </div>
                    <div id="monthly-earning"></div>
                </div>
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title mb-0"> Monthly Earnings </h4>
                            <div>
                                <select class="form-select text-dark">
                                    <option value="1">March 2024</option>
                                    <option value="2">April 2024</option>
                                    <option value="3">May 2024</option>
                                </select>
                            </div>
                        </div>
                        <div id="most-visited" class="rounded-bars mx-n3"></div>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="me-4">
                                <span class="round-8 text-bg-primary rounded-circle me-2 d-inline-block"></span>
                                <span>San Francisco</span>
                            </div>
                            <div>
                                <span class="round-8 text-bg-secondary rounded-circle me-2 d-inline-block"></span>
                                <span>Diego</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Yearly Sales</h5>
                            <p class="card-subtitle mb-0">Every month</p>
                            <div id="yearly-salary" class="mx-n7"></div>
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex align-items-center">
                                    <div
                                        class="bg-primary-subtle rounded-1 me-8 p-8 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-grid-dots text-primary fs-6"></i>
                                    </div>
                                    <div>
                                        <p class="fs-3 mb-0 fw-normal">Salary</p>
                                        <h6 class="fw-semibold text-dark fs-4 mb-0">$36,358</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="text-bg-light rounded-1 me-8 p-8 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-grid-dots text-muted fs-6"></i>
                                    </div>
                                    <div>
                                        <p class="fs-3 mb-0 fw-normal">Expance</p>
                                        <h6 class="fw-semibold text-dark fs-4 mb-0">$5,296</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body">
                        <h4 class="card-title mb-0">Page Impressions</h4>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="fw-semibold mb-0 mt-4">$456,120</h4>
                                <p class="mb-1 fs-2 mb-2">(Change Yesterday)</p>
                                <div class="d-flex align-items-center">
                                    <span
                                        class="me-1 rounded-circle bg-danger-subtle round-20 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-arrow-down-right text-danger"></i>
                                    </span>
                                    <p class="text-muted fs-3 mb-0">+9%</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="impressions"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex align-items-stretch">
                        <div class="card w-100 position-relative overflow-hidden">
                            <div class="card-body">
                                <p class="mb-1 fs-3">Customers</p>
                                <h4 class="fw-semibold">36,358</h4>
                                <div class="d-flex align-items-center">
                                    <span
                                        class="me-1 rounded-circle bg-danger-subtle round-20 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-arrow-down-right text-danger"></i>
                                    </span>
                                    <p class="text-muted fs-3 mb-0">+9%</p>
                                </div>
                            </div>
                            <div id="customers"></div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-stretch">
                        <div class="card w-100 position-relative overflow-hidden">
                            <div class="card-body">
                                <p class="mb-1 fs-3">Projects</p>
                                <h4 class="fw-semibold">78,298</h4>
                                <div class="d-flex align-items-center mb-2">
                                    <span
                                        class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-arrow-up-left text-success"></i>
                                    </span>
                                    <p class="text-muted fs-3 mb-0">+9%</p>
                                </div>
                                <div id="projects" class="rounded-bars mx-n2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-body pb-4">
                        <h5 class="card-title">Revenue Updates</h5>
                        <p class="card-subtitle mb-4">Overview of Profit</p>
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <span class="round-8 text-bg-primary rounded-circle me-2 d-inline-block"></span>
                                <span class="fs-2">Footware</span>
                            </div>
                            <div>
                                <span class="round-8 text-bg-secondary rounded-circle me-2 d-inline-block"></span>
                                <span class="fs-2">Fashionware</span>
                            </div>
                        </div>
                        <div id="revenue-updates" class="rounded-bars mx-n6"></div>
                    </div>
                </div>
                <div class="card w-100">
                    <div class="card-body">
                        <h5 class="card-title">Sales Overview</h5>
                        <p class="card-subtitle mb-4">Every Month</p>
                        <div id="sales-overview"></div>
                        <div class="d-flex align-items-center justify-content-between mt-5 pb-2">
                            <div class="d-flex align-items-center">
                                <div
                                    class="bg-primary-subtle rounded-1 me-8 p-8 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-grid-dots text-primary fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-dark fs-4 mb-0">$23,450</h6>
                                    <p class="fs-3 mb-0 fw-normal">Profit</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div
                                    class="bg-secondary-subtle rounded-1 me-8 p-8 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-grid-dots text-secondary fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-dark fs-4 mb-0">$23,450</h6>
                                    <p class="fs-3 mb-0 fw-normal">Expance</p>
                                </div>
                            </div>
                        </div>
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

            const ctxDoughnut = document.getElementById('tyleGheChart');

            if (ctxDoughnut) {
                new Chart(ctxDoughnut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Lấp đầy', 'Còn trống'],
                        datasets: [{
                            label: 'Tỷ lệ ghế',
                            data: [{{ $tyLeLapDayGhe }}, {{ 100 - $tyLeLapDayGhe }}],
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
                                        return context.label + ": " + context.parsed.toFixed(2) + "%";
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const ctxBar = document.getElementById('netsells');
            let barChart = null;

            function taiDuLieuDoanhThu(loai) {
                fetch(`/api/doanh-thu-${loai}`)
                    .then(res => res.json())
                    .then(data => {
                        if (barChart) barChart.destroy();

                        barChart = new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Doanh thu',
                                    data: data.values,
                                    backgroundColor: '#4F46E5'
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
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            maxRotation: 45,
                                            minRotation: 45
                                        }
                                    },
                                    y: {
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
                    });
            }

            taiDuLieuDoanhThu('week');

            const chonThoiGian = document.getElementById('chonThoiGian');
            if (chonThoiGian) {
                chonThoiGian.addEventListener('change', function() {
                    const loai = this.value === '1' ? 'week' : 'month';
                    taiDuLieuDoanhThu(loai);
                });
            }
        });
    </script>
@endsection

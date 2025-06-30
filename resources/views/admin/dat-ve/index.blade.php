@extends('layouts.admin')


@section('content')

    @vite('resources/js/dat-ve.js')

    <!-- Modal quét mã -->
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quét mã vạch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="barcode-scanner" style="width:100%; height:500px; background:rgb(255, 252, 252);"></div>
                    <p><strong>Kết quả:</strong> <span id="scan-result">Chưa quét</span></p>
                </div>
                <div class="modal-footer">
                    <button id="restartScan" type="button" class="btn btn-primary">Quét lại </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4">
        <div class="card shadow rounded-4 border-0 mb-4">

            <div class="card-body p-4">

                <!-- Bộ lọc -->
                <form method="GET" action="{{ route('admin.dat-ves.index') }}" class="row gy-3 gx-4 align-items-end mb-4">
                    <!-- Chi nhánh -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Chi nhánh</label>
                        <select name="chi_nhanh" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả chi nhánh --</option>
                            @foreach ($chiNhanhs as $cn)
                                <option value="{{ $cn->id }}" {{ request('chi_nhanh') == $cn->id ? 'selected' : '' }}>
                                    {{ $cn->ten_chi_nhanh }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rạp -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Rạp</label>
                        <select name="rap" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả rạp --</option>
                            @foreach ($chiNhanhs->flatMap->rapPhims as $rap)
                                <option value="{{ $rap->id }}" {{ request('rap') == $rap->id ? 'selected' : '' }}>
                                    {{ $rap->ten_rap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Phim -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Phim</label>
                        <select name="phim" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả phim --</option>
                            @foreach ($dsPhim as $phim)
                                <option value="{{ $phim->id }}" {{ request('phim') == $phim->id ? 'selected' : '' }}>
                                    {{ $phim->ten_phim }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </form>

                @if ($datVes->count())
                    <div class="table-responsive">
                        <table class="table align-middle text-center mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Mã vé</th>
                                    <th>Phim</th>
                                    <th>Thời gian đặt</th>
                                    <th style="width: 10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datVes as $index => $datVe)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-semibold">{{ $datVe->ma_dat_ve }}</td>
                                        <td>{{ $datVe->suatChieu->phim->ten_phim }}</td>
                                        <td>{{ $datVe->created_at->format('H:i d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.dat-ve.show', ['id' => $datVe->id, 'ma_ve' => $datVe->ma_dat_ve]) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mt-4 mb-0 rounded-3">
                        <i class="ti ti-info-circle me-1"></i> Không có dữ liệu đặt vé nào.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

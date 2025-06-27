    @extends('layouts.admin')

    @section('title', 'Quản lý Đặt vé')
    @section('page-title', 'Danh sách Đặt vé')
    @section('breadcrumb', 'Danh sách Đặt vé')

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
                <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
                    <h4 class="mb-0 py-2"><i class="fas fa-ticket-alt me-2"></i>Danh sách Đặt vé</h4>
                </div>

                <div class="card-body py-4">


                    <!-- Bộ lọc -->
                    <form method="GET" action="{{ route('admin.dat-ves.index') }}"
                        class="filter-form d-flex flex-wrap align-items-end gap-4 mb-4">
                            {{-- Chi nhánh --}}
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1">Chi nhánh</label>
                                <select name="chi_nhanh" class="form-select" style="min-width: 220px"
                                    onchange="this.form.submit()">
                                    <option value="">-- Tất cả chi nhánh --</option>
                                    @foreach ($chiNhanhs as $cn)
                                        <option value="{{ $cn->id }}"
                                            {{ request('chi_nhanh') == $cn->id ? 'selected' : '' }}>
                                            {{ $cn->ten_chi_nhanh }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Rạp --}}
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1">Rạp</label>
                                <select name="rap" class="form-select" style="min-width: 220px"
                                    onchange="this.form.submit()">
                                    <option value="">-- Tất cả rạp --</option>
                                    @foreach ($chiNhanhs->flatMap->rapPhims as $rap)
                                        <option value="{{ $rap->id }}"
                                            {{ request('rap') == $rap->id ? 'selected' : '' }}>
                                            {{ $rap->ten_rap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Phim --}}
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1">Phim</label>
                                <select name="phim" class="form-select" style="min-width: 220px"
                                    onchange="this.form.submit()">
                                    <option value="">-- Tất cả phim --</option>
                                    @foreach ($dsPhim as $phim)
                                        <option value="{{ $phim->id }}"
                                            {{ request('phim') == $phim->id ? 'selected' : '' }}>
                                            {{ $phim->ten_phim }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Lọc --}}
                            <div class="form-group d-flex align-items-end">
                                <button type="submit" class="btn d-flex align-items-center gap-1 px-4 py-2 text-white"
                                    style="background-color: #6c4efc; border-radius: 0.5rem;">
                                    <i class="bi bi-funnel-fill"></i> Lọc
                                </button>
                            </div>

                            {{-- Xóa bộ lọc --}}
                            <div class="form-group d-flex align-items-end">
                                <a href="{{ route('admin.dat-ves.index') }}"
                                    class="btn btn-outline-secondary d-flex align-items-center gap-1 px-4 py-2"
                                    style="border-radius: 0.5rem;">
                                    <i class="bi bi-arrow-clockwise"></i> Xóa bộ lọc
                                </a>
                            </div>
                    </form>

                    <!-- Bảng dữ liệu -->
                        @if ($datVes->count())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">STT</th>
                                            <th>Mã vé</th>
                                            <th scope="col"><i class="fas fa-film"></i> Phim</th>
                                            <th scope="col"><i class="far fa-clock"></i> Thời gian đặt</th>
                                            <th scope="col"><i class="fas fa-cogs"></i> Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datVes as $index => $datVe)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $datVe->ma_dat_ve }}</td>
                                                <td>{{ $datVe->suatChieu->phim->ten_phim }}</td>
                                                <td>{{ $datVe->created_at->format('H:i d/m/Y') }}</td>
                                                <td>
                                                    <a class="btn btn-warning"
                                                        href="{{ route('admin.dat-ve.show', ['id' => $datVe->id, 'ma_ve' => $datVe->ma_dat_ve]) }}">
                                                        <i class="fa-solid fa-eye fa-spin-pulse"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle me-1"></i> Không có dữ liệu đặt vé nào.
                            </div>
                        @endif
                </div>
            </div>
        </div>

    @endsection

    {{-- @section('scripts')
     
    @endsection --}}

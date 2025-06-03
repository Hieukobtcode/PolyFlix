@extends('layouts.admin')

@section('title', 'Quản lý Sơ đồ ghế')
@section('page-title', 'Danh sách sơ đồ ghế')
@section('breadcrumb', 'Danh sách sơ đồ ghế')


@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Danh sách sơ đồ ghế</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createSoDoModal">
                    <i class="fas fa-plus me-1"></i> Thêm sơ đồ ghế
                </button>
            </div>

            <div class="card-body p-4">
                {{-- @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif --}}

                <div class="mb-3 row">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên sơ đồ...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">#</th>
                                <th>Tên sơ đồ</th>
                                <th>Số hàng</th>
                                <th>Số cột</th>
                                <th class="text-center">Ngày tạo</th>
                                <th class="text-center" style="width: 15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="soDoTable">
                            @forelse($soDoGhes as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->ten_so_do }}</td>
                                    <td>{{ $item->so_hang }}</td>
                                    <td>{{ $item->so_cot }}</td>
                                    <td class="text-center">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.so-do-ghe.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.so-do-ghe.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá sơ đồ này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <small class="text-muted">
                            Hiển thị {{ $soDoGhes->count() }} trong tổng số {{ $soDoGhes->total() }} sơ đồ
                        </small>
                    </div>
                    <div>
                        {{ $soDoGhes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm sơ đồ -->
    <div class="modal fade" id="createSoDoModal" tabindex="-1" aria-labelledby="createSoDoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.so-do-ghe.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="createSoDoLabel">Thêm sơ đồ ghế</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">

                        {{-- Tên sơ đồ --}}
                        <div class="mb-3">
                            <label class="form-label">Tên sơ đồ</label>
                            <input type="text" name="ten_so_do"
                                class="form-control @error('ten_so_do') is-invalid @enderror"
                                value="{{ old('ten_so_do') }}">

                            @error('ten_so_do')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Chọn ma trận mẫu --}}
                        <div class="mb-3">
                            <label class="form-label">Chọn ma trận mẫu</label>
                            <select id="maTranSelect" name="ma_tran"
                                class="form-select @error('so_hang') is-invalid @enderror">
                                <option value="">-- Chọn mẫu --</option>
                                @foreach (['5x10', '6x12', '8x8', '8x12', '10x14', '12x16', '14x20'] as $option)
                                    @php
                                        [$rows, $cols] = explode('x', $option);
                                        $totalSeats = $rows * $cols;
                                    @endphp
                                    <option value="{{ $option }}" {{ old('ma_tran') == $option ? 'selected' : '' }}>
                                        Ma trận ghế {{ $option }} – Tối đa {{ $totalSeats }} chỗ ngồi
                                    </option>
                                @endforeach
                            </select>
                        </div>




                        {{-- Tổng số hàng / cột --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input hidden readonly type="number" name="so_hang" id="so_hang"
                                    class="form-control @error('so_hang') is-invalid @enderror"
                                    value="{{ old('so_hang') }}">
                                @error('so_hang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <input hidden readonly type="number" name="so_cot" id="so_cot"
                                    class="form-control @error('so_cot') is-invalid @enderror" value="{{ old('so_cot') }}">
                            </div>

                        </div>

                        {{-- Hàng theo loại --}}
                        @php
                            $loiLoaiHang = $errors->has('so_hang_loai');
                        @endphp

                        <div class="row">
                            {{-- Hàng thường --}}
                            <div class="col-md-4">
                                <label for="so_hang_thuong" class="form-label">Hàng thường</label>
                                <input type="number" name="so_hang_thuong" id="so_hang_thuong" min="0"
                                    value="{{ old('so_hang_thuong', 0) }}"
                                    class="form-control @error('so_hang_thuong') is-invalid @enderror {{ $loiLoaiHang ? 'is-invalid' : '' }}">
                            </div>

                            {{-- Hàng VIP --}}
                            <div class="col-md-4">
                                <label for="so_hang_vip" class="form-label">Hàng VIP</label>
                                <input type="number" name="so_hang_vip" id="so_hang_vip" min="0"
                                    value="{{ old('so_hang_vip', 0) }}"
                                    class="form-control @error('so_hang_vip') is-invalid @enderror {{ $loiLoaiHang ? 'is-invalid' : '' }}">
                            </div>

                            {{-- Hàng đôi --}}
                            <div class="col-md-4">
                                <label for="so_hang_doi" class="form-label">Hàng đôi</label>
                                <input type="number" name="so_hang_doi" id="so_hang_doi" min="0"
                                    value="{{ old('so_hang_doi', 0) }}"
                                    class="form-control @error('so_hang_doi') is-invalid @enderror {{ $loiLoaiHang ? 'is-invalid' : '' }}">
                            </div>
                        </div>

                        {{-- Hiển thị lỗi tổng số hàng ghế vượt quá --}}
                        @error('so_hang_loai')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        {{-- Mô tả --}}
                        <div class="mb-3 mt-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="mo_ta" class="form-control">{{ old('mo_ta') }}</textarea>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="1" {{ old('trang_thai') == 1 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('trang_thai') == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Thêm mới</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Tìm kiếm theo tên sơ đồ ===
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('#soDoTable tr');

            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                let count = 0;
                rows.forEach((row) => {
                    const nameCell = row.querySelector('td:nth-child(2)');
                    if (!nameCell) return;
                    const isVisible = nameCell.textContent.toLowerCase().includes(value);
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) {
                        row.querySelector('td:first-child').textContent = ++count;
                    }
                });
            });

            // === Tự động cập nhật ma trận và loại ghế ===
            const select = document.getElementById('maTranSelect');
            const inputHang = document.getElementById('so_hang');
            const inputCot = document.getElementById('so_cot');
            const hangThuong = document.getElementById('so_hang_thuong');
            const hangVip = document.getElementById('so_hang_vip');
            const hangDoi = document.getElementById('so_hang_doi');

            function autoFillMatrix(value) {
                if (value) {
                    const [hang, cot] = value.split('x');
                    inputHang.value = hang;
                    inputCot.value = cot;

                    const total = parseInt(hang);
                    const vip = total >= 2 ? 2 : 1;
                    const doi = total >= 4 ? 1 : 0;
                    const thuong = Math.max(0, total - vip - doi);

                    hangVip.value = vip;
                    hangDoi.value = doi;
                    hangThuong.value = thuong;

                    // Reset lỗi giao diện nếu có
                    [hangThuong, hangVip, hangDoi].forEach(input => input.classList.remove('is-invalid'));
                    const feedback = document.getElementById('loiTongHang');
                    if (feedback) feedback.textContent = '';
                } else {
                    inputHang.value = '';
                    inputCot.value = '';
                    hangVip.value = 0;
                    hangDoi.value = 0;
                    hangThuong.value = 0;
                }
            }

            // Khi người dùng chọn ma trận mẫu
            select.addEventListener('change', function() {
                autoFillMatrix(this.value);
            });

            // Gọi lại khi reload nếu có old('ma_tran')
            const oldMatrix = "{{ old('ma_tran') }}";
            if (oldMatrix && {{ $errors->any() ? 'false' : 'true' }}) {
                autoFillMatrix(oldMatrix);
            }
        });
    </script>
@endsection


@if ($errors->any() && session('show_create_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('createSoDoModal'));
            modal.show();
        });
    </script>
@endif

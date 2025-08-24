@extends('layouts.admin')

@section('title', 'Báo cáo khuyến mãi chi nhánh')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb và nút quay lại -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.chi-nhanh-khuyen-mai.index') }}">Khuyến mãi chi nhánh</a>
                            </li>
                            <li class="breadcrumb-item active">Báo cáo</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.chi-nhanh-khuyen-mai.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>
                    Quay lại
                </a>
            </div>

            <!-- Bộ lọc thời gian -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="ti ti-filter me-2"></i>
                        Bộ lọc báo cáo - {{ $chiNhanh->ten_chi_nhanh }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="tu_ngay" class="form-control" value="{{ $tuNgay }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="den_ngay" class="form-control" value="{{ $denNgay }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i>
                                Lọc báo cáo
                            </button>
                            <button type="button" class="btn btn-success ms-2" onclick="exportReport()">
                                <i class="ti ti-download me-1"></i>
                                Xuất Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="ti ti-gift fs-3 mb-2"></i>
                            <h3 class="mb-1">{{ number_format($tongQuan['tong_khuyen_mai']) }}</h3>
                            <p class="mb-0">Tổng khuyến mãi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="ti ti-check-circle fs-3 mb-2"></i>
                            <h3 class="mb-1">{{ number_format($tongQuan['khuyen_mai_dang_hoat_dong']) }}</h3>
                            <p class="mb-0">Đang hoạt động</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="ti ti-tickets fs-3 mb-2"></i>
                            <h3 class="mb-1">{{ number_format($tongQuan['tong_ve_su_dung']) }}</h3>
                            <p class="mb-0">Vé đã sử dụng</p>
                            <small class="opacity-75">Trong kỳ báo cáo</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="ti ti-coin fs-3 mb-2"></i>
                            <h3 class="mb-1">{{ number_format($tongQuan['tong_tien_giam']) }}đ</h3>
                            <p class="mb-0">Tổng tiền giảm</p>
                            <small class="opacity-75">Trong kỳ báo cáo</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Báo cáo chi tiết -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="ti ti-chart-bar me-2"></i>
                        Báo cáo chi tiết theo khuyến mãi
                        <small class="ms-2 opacity-75">
                            (Từ {{ \Carbon\Carbon::parse($tuNgay)->format('d/m/Y') }} 
                            đến {{ \Carbon\Carbon::parse($denNgay)->format('d/m/Y') }})
                        </small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="reportTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Mã KM</th>
                                    <th>Tên khuyến mãi</th>
                                    <th>Loại giảm giá</th>
                                    <th>Giá trị</th>
                                    <th>Áp dụng cho</th>
                                    <th>Số vé sử dụng</th>
                                    <th>Tổng tiền giảm</th>
                                    <th>Tỷ lệ sử dụng</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($baoCaoChiTiet as $index => $item)
                                    @php $khuyenMai = $item['khuyen_mai']; @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $khuyenMai->ma_khuyen_mai }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $khuyenMai->ten }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($khuyenMai->ngay_bat_dau)->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($khuyenMai->ngay_ket_thuc)->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                                <span class="badge bg-info">Phần trăm</span>
                                            @else
                                                <span class="badge bg-warning">Tiền mặt</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($khuyenMai->loai_giam_gia == 'phan_tram')
                                                <strong class="text-primary">{{ $khuyenMai->gia_tri_giam }}%</strong>
                                                @if($khuyenMai->giam_toi_da)
                                                    <br><small class="text-muted">Max: {{ number_format($khuyenMai->giam_toi_da) }}đ</small>
                                                @endif
                                            @else
                                                <strong class="text-primary">{{ number_format($khuyenMai->gia_tri_giam) }}đ</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($khuyenMai->ap_dung_cho)
                                                @case('ve')
                                                    <span class="badge bg-primary">Vé xem phim</span>
                                                    @break
                                                @case('do_an')
                                                    <span class="badge bg-success">Đồ ăn</span>
                                                    @break
                                                @case('tat_ca')
                                                    <span class="badge bg-purple">Tất cả</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="text-center">
                                            <strong class="text-primary">{{ number_format($item['so_ve_su_dung']) }}</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-success">{{ number_format($item['tien_giam']) }}đ</strong>
                                        </td>
                                        <td class="text-center">
                                            @if($khuyenMai->so_lan_su_dung_toi_da)
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-primary" 
                                                             style="width: {{ $item['ty_le_su_dung'] }}%">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $item['ty_le_su_dung'] }}%</small>
                                                </div>
                                            @else
                                                <span class="badge bg-info">Không giới hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $now = now();
                                                $isActive = $khuyenMai->trang_thai == 'hoat_dong' 
                                                    && $khuyenMai->ngay_bat_dau <= $now 
                                                    && $khuyenMai->ngay_ket_thuc >= $now;
                                                $isExpired = $khuyenMai->ngay_ket_thuc < $now;
                                            @endphp

                                            @if($isExpired)
                                                <span class="badge bg-danger">Đã hết hạn</span>
                                            @elseif($khuyenMai->trang_thai == 'tam_dung')
                                                <span class="badge bg-warning">Tạm dừng</span>
                                            @elseif($isActive)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Chưa bắt đầu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ti ti-chart-bar fs-3 mb-2"></i>
                                                <p class="mb-0">Không có dữ liệu báo cáo trong khoảng thời gian này</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($baoCaoChiTiet->isNotEmpty())
                                <tfoot class="table-light">
                                    <tr class="fw-bold">
                                        <td colspan="6" class="text-end">Tổng cộng:</td>
                                        <td class="text-center">{{ number_format($baoCaoChiTiet->sum('so_ve_su_dung')) }}</td>
                                        <td class="text-end">{{ number_format($baoCaoChiTiet->sum('tien_giam')) }}đ</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ thống kê (nếu có dữ liệu) -->
            @if($baoCaoChiTiet->isNotEmpty())
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top khuyến mãi được sử dụng nhiều nhất</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="topKhuyenMaiChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Phân bố theo loại áp dụng</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="phanBoLoaiChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75em;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.progress {
    border-radius: 5px;
}

.table tfoot td {
    border-top: 2px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Hàm xuất báo cáo Excel
function exportReport() {
    const table = document.getElementById('reportTable');
    let csv = [];
    
    // Header
    const headerRow = [];
    table.querySelectorAll('thead tr th').forEach(th => {
        headerRow.push('"' + th.textContent.trim() + '"');
    });
    csv.push(headerRow.join(','));
    
    // Data rows
    table.querySelectorAll('tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('td').forEach(td => {
            // Lấy text content và loại bỏ các ký tự đặc biệt
            let cellValue = td.textContent.trim().replace(/"/g, '""');
            row.push('"' + cellValue + '"');
        });
        if (row.length > 0) {
            csv.push(row.join(','));
        }
    });
    
    // Download
    const csvContent = '\uFEFF' + csv.join('\n'); // \uFEFF for UTF-8 BOM
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'bao-cao-khuyen-mai-{{ $chiNhanh->ten_chi_nhanh }}.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

@if($baoCaoChiTiet->isNotEmpty())
// Biểu đồ top khuyến mãi
const topKhuyenMaiData = {!! json_encode($baoCaoChiTiet->sortByDesc('so_ve_su_dung')->take(5)->values()) !!};
const topKhuyenMaiLabels = topKhuyenMaiData.map(item => item.khuyen_mai.ma_khuyen_mai);
const topKhuyenMaiValues = topKhuyenMaiData.map(item => item.so_ve_su_dung);

new Chart(document.getElementById('topKhuyenMaiChart'), {
    type: 'bar',
    data: {
        labels: topKhuyenMaiLabels,
        datasets: [{
            label: 'Số vé sử dụng',
            data: topKhuyenMaiValues,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Biểu đồ phân bố theo loại
const phanBoData = {!! json_encode($baoCaoChiTiet->groupBy('khuyen_mai.ap_dung_cho')->map(function($group) {
    return $group->sum('so_ve_su_dung');
})) !!};

const phanBoLabels = Object.keys(phanBoData).map(key => {
    switch(key) {
        case 've': return 'Vé xem phim';
        case 'do_an': return 'Đồ ăn';
        case 'tat_ca': return 'Tất cả';
        default: return key;
    }
});
const phanBoValues = Object.values(phanBoData);

new Chart(document.getElementById('phanBoLoaiChart'), {
    type: 'doughnut',
    data: {
        labels: phanBoLabels,
        datasets: [{
            data: phanBoValues,
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
@endif
</script>
@endpush

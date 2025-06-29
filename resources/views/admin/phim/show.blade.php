@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Poster + Trailer -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="row align-items-center px-4 py-4 g-4">
                <!-- Poster bên trái -->
                <div class="col-md-4 text-center">
                    <div class="border border-3 border-white shadow-sm rounded overflow-hidden"
                        style="width: 100%; max-width: 300px; height: 400px; margin: auto;">
                        <img src="{{ asset('storage/' . $phim->poster) }}" alt="poster" class="img-fluid h-100 w-100"
                            style="object-fit: cover;">
                    </div>
                    <h4 class="mt-3 fw-bold">{{ $phim->ten_phim }}</h4>
                </div>

                <!-- Trailer bên phải -->
                <div class="col-md-8">
                    @if ($phim->trailer)
                        @php
                            $trailerUrl = $phim->trailer;
                            if (strpos($trailerUrl, 'youtube.com/watch?v=') !== false) {
                                $videoId = substr($trailerUrl, strpos($trailerUrl, 'v=') + 2);
                                $videoId =
                                    strpos($videoId, '&') !== false
                                        ? substr($videoId, 0, strpos($videoId, '&'))
                                        : $videoId;
                                $embedUrl = "https://www.youtube.com/embed/$videoId";
                            } elseif (strpos($trailerUrl, 'youtu.be/') !== false) {
                                $videoId = substr($trailerUrl, strrpos($trailerUrl, '/') + 1);
                                $embedUrl = "https://www.youtube.com/embed/$videoId";
                            } else {
                                $embedUrl = $trailerUrl;
                            }
                        @endphp
                        <div class="ratio ratio-16x9 rounded shadow-sm" style="min-height: 300px;">
                            <iframe src="{{ $embedUrl }}" title="Trailer {{ $phim->ten_phim }}" class="rounded"
                                allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="text-muted fst-italic">Không có trailer</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="row">
            <!-- Thông tin phim -->
            <div class="col-lg-4">
                <div class="card shadow-sm border">
                    <div class="card-body fs-4">
                        <h5 class="mb-4 fw-semibold text-primary">Thông tin phim</h5>
                        <ul class="list-unstyled vstack gap-3">
                            <li><i class="ti ti-clock me-2"></i>Thời lượng:
                                <strong>{{ $phim->thoi_luong ? $phim->thoi_luong . ' phút' : 'Đang cập nhật' }}</strong>
                            </li>

                            <li><i class="ti ti-world me-2"></i>Ngôn ngữ:
                                <strong>{{ $phim->ngon_ngu ?: 'Đang cập nhật' }}</strong>
                            </li>

                            <li><i class="ti ti-flag me-2"></i>Quốc gia:
                                <strong>{{ $phim->quoc_gia ?: 'Đang cập nhật' }}</strong>
                            </li>

                            <li><i class="ti ti-alert-circle me-2"></i>Độ tuổi:
                                <strong>{{ $phim->do_tuoi ?: 'Đang cập nhật' }}</strong>
                            </li>
                            <li><i class="ti ti-circle-check me-2"></i>Trạng thái:
                                @php
                                    use Illuminate\Support\Str;

                                    $trangThai = $phim->trang_thai ?? 'Đang cập nhật';
                                    $trangThaiHienThi = Str::ucfirst(Str::lower($trangThai));
                                @endphp
                                <strong>{{ $trangThaiHienThi }}</strong>
                            </li>

                            <li><i class="ti ti-tags me-2"></i>Thể loại:
                                @php
                                    $theLoais = $phim->theLoais->pluck('ten_the_loai')->toArray();
                                @endphp
                                <strong>{{ count($theLoais) ? implode(', ', $theLoais) : 'Đang cập nhật' }}</strong>
                            </li>

                            <li><i class="ti ti-screen-share me-2"></i>Định dạng:
                                @php
                                    $dinhDangs = $phim->dinhDangs->pluck('ten_dinh_dang')->toArray();
                                @endphp
                                <strong>{{ count($dinhDangs) ? implode(', ', $dinhDangs) : 'Đang cập nhật' }}</strong>
                            </li>

                            <li><i class="ti ti-language me-2"></i>Phụ đề:
                                @php
                                    $phuDes = $phim->phuDes->pluck('ten_phu_de')->toArray();
                                @endphp
                                <strong>{{ count($phuDes) ? implode(', ', $phuDes) : 'Đang cập nhật' }}</strong>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>

            <!-- Mô tả phim -->
            <div class="col-lg-8">
                <div class="card shadow-sm border">
                    <div class="card-body fs-4">
                        <h5 class="mb-3 fw-semibold text-primary">Mô tả phim</h5>

                        <p class="mt-4 text-justify">
                            {!! $phim->mo_ta ? nl2br(e($phim->mo_ta)) : '<span class="text-muted">Đang cập nhật</span>' !!}
                        </p>

                        <p><i class="ti ti-user me-2"></i><strong>Đạo diễn:</strong>
                            {{ $phim->dao_dien ?: 'Đang cập nhật' }}</p>
                        <p><i class="ti ti-users me-2"></i><strong>Diễn viên:</strong>
                            {{ $phim->dien_vien ?: 'Đang cập nhật' }}</p>

                        <p><i class="ti ti-calendar-event me-2"></i><strong>Ngày phát hành:</strong>
                            {{ $phim->ngay_phat_hanh ? $phim->ngay_phat_hanh->format('d/m/Y') : 'Đang cập nhật' }}
                        </p>

                        <p><i class="ti ti-calendar-minus me-2"></i><strong>Ngày kết thúc:</strong>
                            {{ $phim->ngay_ket_thuc ? $phim->ngay_ket_thuc->format('d/m/Y') : 'Đang cập nhật' }}
                        </p>

                        <p><i class="ti ti-building me-2"></i><strong>Chi nhánh:</strong>
                            @php
                                $chiNhanhs = $phim->chiNhanhs;
                                $tenCN = $chiNhanhs->pluck('ten_chi_nhanh')->toArray();
                                $hienCN = array_slice($tenCN, 0, 3);
                                $soConLai = count($tenCN) - 3;
                            @endphp
                            @if (count($tenCN))
                                <span title="{{ implode(', ', $tenCN) }}">
                                    {{ implode(', ', $hienCN) }}
                                    @if ($soConLai > 0)
                                        ... (+{{ $soConLai }})
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Đang cập nhật</span>
                            @endif
                        </p>

                        <p><i class="ti ti-building-store me-2"></i><strong>Rạp:</strong>
                            @php
                                $raps = $phim->rapPhims;
                                $tenRaps = $raps->pluck('ten_rap')->toArray();
                                $hienRaps = array_slice($tenRaps, 0, 3);
                                $soConLaiRap = count($tenRaps) - 3;
                            @endphp
                            @if (count($tenRaps))
                                <span title="{{ implode(', ', $tenRaps) }}">
                                    {{ implode(', ', $hienRaps) }}
                                    @if ($soConLaiRap > 0)
                                        ... (+{{ $soConLaiRap }})
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Đang cập nhật</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

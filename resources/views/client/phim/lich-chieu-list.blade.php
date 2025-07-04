@foreach ($groupedSuatChieus as $tenRap => $groupsByDinhDang)
    <div class="lich-chieu-rap-box">
        <div class="fs-5 fw-bold mb-2" style="color: #ffec38; font-size:18px">{{ $tenRap }}</div>
        @foreach ($groupsByDinhDang as $tenDinhDang => $scGroup)
            <div class="mb-2" style="margin-top: 10px ">
                <span class="badge rounded-pill bg-primary me-2">{{ $tenDinhDang }}</span>
                <div class="gio-chieu-grid" style="margin-top: 10px">
                    @foreach ($scGroup as $sc)
                        <a href="{{ route('client.dat-ve', ['phim_id' => $sc->phim_id, 'suat_chieu_id' => $sc->id]) }}"
                            class="gio-chieu-btn">
                            {{ \Carbon\Carbon::parse($sc->bat_dau)->format('H:i') }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endforeach

@if ($groupedSuatChieus->isEmpty())
    <div class="text-muted">Không có suất chiếu phù hợp.</div>
@endif

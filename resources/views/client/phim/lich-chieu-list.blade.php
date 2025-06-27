@foreach ($groupedSuatChieus as $tenRap => $groupsByDinhDang)
    <div class="lich-chieu-rap-box mb-3">
        <div class="fs-5 fw-bold mb-2">{{ $tenRap }}</div>
        @foreach ($groupsByDinhDang as $tenDinhDang => $scGroup)
            <div class="mb-2">
                <span class="badge rounded-pill bg-primary me-2">{{ $tenDinhDang }}</span>
                @foreach ($scGroup as $sc)
                    <a href="{{ route('client.dat-ve', ['phim_id' => $sc->phim_id, 'suat_chieu_id' => $sc->id]) }}"
                       class="gio-chieu-btn">
                        {{ \Carbon\Carbon::parse($sc->bat_dau)->format('H:i') }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
@endforeach
@if ($groupedSuatChieus->isEmpty())
    <div class="text-muted">Không có suất chiếu phù hợp.</div>
@endif

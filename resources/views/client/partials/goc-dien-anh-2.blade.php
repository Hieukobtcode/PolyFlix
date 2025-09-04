@php
    use App\Helpers\IdFormatter;
@endphp

<div class="new">
    <button type="button"></button>
    <p>GÓC ĐIỆN ẢNH</p>
    <div class="list">
        <p>
            <a class="tab-item active" data-tab="binhluan">Bình luận phim</a>
        </p>
    </div>
</div>
<div id="tab-binhluan" class="tab-content active">
    <div id="slide-container">
        @foreach ($allPhims as $index => $movie)
            <div class="slide" style="{{ $index === 0 ? '' : 'display:none;' }}">
                <div class="khung-binh-luan">

                    {{-- Poster --}}
                    <div class="poster">
                        <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                    </div>

                    {{-- Bình luận --}}
                    <div class="binh-luan">
                        <h4 class="ten-phim">{{ $phim->ten_phim }}</h4>
                        @forelse ($phim->comments as $binhLuan)
                            @php
                                $userRating = $phim->ratings->firstWhere('user_id', $binhLuan->user_id);
                            @endphp
                            <div class="binh-luan-item">
                                <div class="avatar">
                                    <img src="{{ $binhLuan->user && $binhLuan->user->avatar
                                        ? asset('storage/' . $binhLuan->user->avatar)
                                        : asset('logo/user.jpg') }}"
                                        alt="{{ $binhLuan->user->name ?? 'Người dùng' }}">
                                </div>
                                <div class="noi-dung">
                                    <strong>{{ $binhLuan->user->name ?? 'Ẩn danh' }}</strong>
                                    @if ($userRating)
                                        <span style="color: orange;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                {{ $i <= $userRating->rating ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                    @endif
                                    <p>{{ $binhLuan->content }}</p>
                                    <small>{{ $binhLuan->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                        @empty
                            <p style="color: #ccc;">Chưa có bình luận cho phim này.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="slide-controls">
        <div class="slide-button-group">
            <button class="btn-see1 left" onclick="changeSlide(-1)">‹</button>
            <button class="btn-see1 right" onclick="changeSlide(1)">›</button>
        </div>
    </div>
</div>

<div id="tab-blog" class="tab-content">
    @if ($baiViet && count($baiViet))
        <div class="tin-tuc-wrapper">
            {{-- Bài viết nổi bật --}}
            <div class="tin-tuc-noi-bat">
                <img src="{{ asset('storage/' . $baiViet[0]->hinh_anh) }}" alt="{{ $baiViet[0]->tieu_de }}">
                <a href="{{ route('show-bai-viet', IdFormatter::uuidify($baiViet[0]->id)) }}">
                    <h3>{{ $baiViet[0]->tieu_de }}</h3>
                </a>
            </div>

            {{-- Danh sách bài viết còn lại --}}
            <div class="tin-tuc-danh-sach">
                @foreach ($baiViet->skip(1) as $bv)
                    <div class="tin-tuc-item">
                        <div class="thumb">
                            <a href="{{ route('show-bai-viet', IdFormatter::uuidify($bv->id)) }}">
                                <img src="{{ asset('storage/' . $bv->hinh_anh) }}" alt="{{ $bv->tieu_de }}">
                            </a>
                        </div>
                        <div class="info">
                            <a href="{{ route('show-bai-viet', IdFormatter::uuidify($bv->id)) }}">
                                <h4>{{ $bv->tieu_de }}</h4>
                                <p class="noi-dung">{{ $bv->noi_dung }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('client.bai-viet') }}">
                <button class="btn-see">Xem thêm</button>
            </a>
        </div>
    @else
        <p>Chưa có bài viết nào.</p>
    @endif
</div>
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.display = (i === index) ? 'block' : 'none';
        });
    }

    function changeSlide(direction) {
        currentSlide += direction;
        if (currentSlide >= slides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = slides.length - 1;
        showSlide(currentSlide);
    }

    setInterval(() => changeSlide(1), 5000);

    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            this.classList.add('active');

            const tabContent = document.getElementById('tab-' + this.dataset.tab);
            if (tabContent) {
                tabContent.classList.add('active');
            }
        });
    });
</script>

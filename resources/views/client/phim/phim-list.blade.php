@extends('layouts.client')
@section('content')
  @php
        use App\Helpers\IdFormatter;
    @endphp
  <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />


    @vite('resources/js/trang-chu.js')

 <style>
.ten-phim {
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Giới hạn 2 dòng */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.5em;
    height: 3em;
}
.img-wrapper {
    position: relative;
    display: inline-block;
}

.age-label {
    position: absolute;
    top: 8px;
    left: 8px;
    background: red;
    color: white;
    font-weight: bold;
    padding: 4px 8px;
    font-size: 14px;
    border-radius: 4px;
    z-index: 10;
}
.tab-phim-item {
    padding: 10px;
    cursor: pointer;
    text-decoration: none;
}

.tab-phim-item.active {
    color: #FFD700;
    font-weight: bold;
    text-decoration: underline;
    text-underline-offset: 4px;
}
</style>


<!-- Menu -->
<div class="menu">
    <button type="button"></button>
    <p class="movie">PHIM</p>
    <div class="list">
        <p>
        <a href="{{ route('phim.dang-chieu') }}" class="tab-phim-item {{ $tab == 'dang-chieu' ? 'active' : '' }}">Đang chiếu</a>
        </p>
        <p>
        <a href="{{ route('phim.sap-chieu') }}" class="tab-phim-item {{ $tab == 'sap-chieu' ? 'active' : '' }}">Sắp chiếu</a>
        </p>
    </div>
</div>

<!-- List movie -->
<div class="list-movie">
    @foreach ($phims as $phim)
        <div class="movie">
            <div class="img-wrapper">
                <img src="{{ asset('storage/' . $phim->poster) }}" alt="{{ $phim->ten_phim }}">
                <div class="age-label">{{ $phim->do_tuoi }}</div> 
                <div class="overlay">
                    <button class="btn buy"><i class="fa-solid fa-ticket"></i> Mua vé</button>
                          <button class="btn trailer" data-video="{{ $phim->trailer }}"><i class="fa-solid fa-video"></i> Trailer</button>
              
                </div>
            </div>
            <p class="ten-phim">{{ $phim->ten_phim }}</p>
        </div>    
    @endforeach
</div>
@include('client.partials.goc-dien-anh', ['phims' => $phims, 'ratings' => $ratings, 'baiViet' => $baiViet])
<!-- Popup trailer -->
<div id="trailerPopup" style="display:none; position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);
background:#000;padding:10px;border-radius:8px;z-index:999;">
    <iframe id="trailerIframe" width="800" height="450" frameborder="0" allowfullscreen></iframe>
</div>
<!-- Overlay mờ nền -->
<div id="overlayBg" style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.7);z-index:998;"></div>

<script>
document.querySelectorAll('.btn.trailer').forEach(btn => {
    btn.addEventListener('click', function() {
        let videoUrl = this.getAttribute('data-video');
        // Nếu là link youtube -> chuyển về dạng embed
        videoUrl = convertYoutubeUrl(videoUrl);

        document.getElementById('trailerIframe').src = videoUrl + '?autoplay=1';
        document.getElementById('trailerPopup').style.display = 'block';
        document.getElementById('overlayBg').style.display = 'block';
    });
});

// Tắt popup khi click nền
document.getElementById('overlayBg').addEventListener('click', function() {
    document.getElementById('trailerIframe').src = '';
    document.getElementById('trailerPopup').style.display = 'none';
    this.style.display = 'none';
});

// Hàm chuyển link youtube -> embed
function convertYoutubeUrl(url) {
    let video_id = '';
    if (url.includes('watch?v=')) {
        video_id = url.split('watch?v=')[1];
    } else if (url.includes('youtu.be/')) {
        video_id = url.split('youtu.be/')[1];
    } else {
        video_id = url; // nếu đã là embed
    }

    return 'https://www.youtube.com/embed/' + video_id;
}
</script>

@endsection

    

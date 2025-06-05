<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom d-flex justify-content-center align-items-center">
        <div class="sidebar-brand">
            <img src="{{ asset('logo/LogoPolyFlixAdmin.png') }}" class="sidebar-brand-full"
                style="width: 150px; height: auto;" alt="PolyFlix Logo">
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
    </div>


    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item">
            <a class="nav-link" href="#">

                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg>
                Dashboard
                <span class="badge badge-sm bg-info ms-auto">NEW</span>
            </a>
        </li>

        <li class="nav-title">Quản lý</li>

        {{-- Quản lý hệ thống rạp --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="fas fa-building nav-icon"></i>
                Hệ thống rạp
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.chi-nhanh.index') }}">Chi nhánh</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.loai-phong.index') }}">Loại phòng</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.loai-ghe.index') }}">Loại ghế</a></li>
            </ul>
        </li>

        {{-- Quản lý phim --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-video') }}"></use>
                </svg>
                Quản lý phim
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.the-loai-phim.index') }}">Thể Loại</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.phim.index') }}">Danh sách phim</a></li>
            </ul>
        </li>


        {{-- Quản lý người dùng --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="fa-solid fa-user nav-icon"></i>
                Quản lý người dùng
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.vai-tro.index') }}">Vai trò</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.phan-quyen.index') }}">Phân quyền</a>
                </li>
            </ul>
        </li>

        {{-- Khuyến mãi --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="fas fa-tags nav-icon"></i>
                Khuyến mãi
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.khuyen-mai.index') }}">Danh sách</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.khuyen-mai.thong-ke-su-dung') }}">Thống
                        kê</a></li>
            </ul>
        </li>

        {{-- Cấu hình hệ thống --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="fas fa-cogs nav-icon"></i>
                Cấu hình hệ thống
            </a>
            <ul class="nav-group-items compact">

                <li class="nav-item"><a class="nav-link" href="{{ route('admin.cau-hinh.index') }}">Cài đặt chung</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.cap-bac-the.index') }}">Cấp bậc thẻ</a>
                </li>
            </ul>
        </li>

        {{-- Bài viết & banner --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <i class="fas fa-newspaper nav-icon"></i>
                Nội dung hiển thị
            </a>
            <ul class="nav-group-items compact">

                <li class="nav-item"><a class="nav-link" href="{{ route('admin.bai-viet.index') }}">Bài viết</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.banners.index') }}">Banners</a></li>
            </ul>
        </li>
        {{-- Quản lý đồ ăn --}}
        <li class="nav-group">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-restaurant') }}"></use>
                </svg>
                Quản lý đồ ăn
            </a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.do-an.index') }}">Đồ ăn</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.combos.index') }}">Combo</a></li>
            </ul>
        </li>

        {{-- Liên hệ --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.lien-he.index') }}">
                <i class="fas fa-envelope nav-icon"></i>
                Liên hệ khách hàng
            </a>
        </li>
    </ul>

</div>

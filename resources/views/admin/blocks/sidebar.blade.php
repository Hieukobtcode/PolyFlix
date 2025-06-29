<!-- Sidebar Start -->
<!-- Font Awesome CDN -->

<aside class="left-sidebar with-vertical">
    <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="index.html" class="text-nowrap logo-img">
            <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/logos/logo-light.svg"
                class="dark-logo" alt="Logo-Dark" />
            <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/logos/logo-dark.svg"
                class="light-logo" alt="Logo-light" />
        </a>
        <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
        </a>
    </div>

    <div class="scroll-sidebar" data-simplebar>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="mb-0">
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">Home</span>
                </li>

                {{-- Thống kê --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow indigo-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <span class="aside-icon p-2 bg-indigo-subtle rounded-1">
                            <i class="ti ti-chart-pie fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Thống kê</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.thong-ke.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Tổng quan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.thong-ke.doanh-thu') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Doanh thu</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.thong-ke.ve') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Vé</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.thong-ke.suat-chieu') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Suất chiếu</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.thong-ke.suat-chieu') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Đồ ăn, combo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.thong-ke.phim') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Phim</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Hệ thống rạp --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <span class="aside-icon p-2 bg-primary-subtle rounded-1">
                            <i class="ti ti-theater fs-7"></i>
                        </span>
                        <span class="hide-menu ps-1">Hệ thống rạp</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.chi-nhanh.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Mạng lưới rạp chiếu</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.loai-phong.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Loại phòng</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.loai-ghe.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Loại ghế</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.gia-ve.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Giá vé</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Quản lý phim --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow success-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <span class="aside-icon p-2 bg-success-subtle rounded-1">
                            <i class="ti ti-movie fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Quản lý phim</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.the-loai-phim.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Thể Loại</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.dinh-dang-phim.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Định dạng phim</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.phu-de-phim.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Phụ đề phim</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.phim.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Danh sách phim</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.suat-chieu.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Suất chiếu</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Quản lý người dùng --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-warning-subtle rounded-1">
                            <i class="ti ti-user fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Quản lý người dùng</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Người dùng</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.vai-tro.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Vai trò</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.phan-quyen.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Phân quyền</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Cấu hình hệ thống --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow info-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <span class="aside-icon p-2 bg-info-subtle rounded-1">
                            <i class="ti ti-settings fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Cấu hình hệ thống</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.cau-hinh.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Cài đặt chung</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.cap-bac-the.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Cấp bậc thẻ</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Nội dung hiển thị --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow info-hover-bg" href="javascript:void(0)" aria-expanded="false">
                        <span class="aside-icon p-2 bg-info-subtle rounded-1">
                            <i class="ti ti-news fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Nội dung hiển thị</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.bai-viet.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Bài viết</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.banners.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Banners</span>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item">
                            <a href="{{ route('admin.comments.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Bình luận và đánh giá</span>
                            </a>
                        </li> --}}
                    </ul>
                </li>

                {{-- Quản lý đồ ăn --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow danger-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-danger-subtle rounded-1">
                            <i class="ti ti-cup fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Quản lý đồ ăn</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.do-an.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Đồ ăn</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.combos.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Combo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.danh-muc-do-an.index') }}" class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Danh mục đồ ăn</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Liên hệ khách hàng --}}
                <li class="sidebar-item">
                    <a href="{{ route('admin.lien-he.index') }}" class="sidebar-link info-hover-bg">
                        <span class="aside-icon p-2 bg-info-subtle rounded-1">
                            <i class="ti ti-mail fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Liên hệ khách hàng</span>
                    </a>
                </li>

                {{-- Khuyến mãi --}}
                <li class="sidebar-item">
                    <a href="{{ route('admin.khuyen-mai.index') }}" class="sidebar-link secondary-hover-bg">
                        <span class="aside-icon p-2 bg-secondary-subtle rounded-1">
                            <i class="ti ti-tags fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Khuyến mãi</span>
                    </a>
                </li>
                
                {{-- Đơn vé --}}
                <li class="sidebar-item">
                    <a href="{{ route('admin.dat-ves.index') }}" class="sidebar-link secondary-hover-bg">
                        <span class="aside-icon p-2 bg-secondary-subtle rounded-1">
                            <i class="ti ti-ticket fs-6"></i>
                        </span>
                        <span class="hide-menu ps-1">Đơn vé</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link success-hover-bg"
                        href="https://bootstrapdemos.wrappixel.com/spike/dist/main/index2.html" aria-expanded="false">
                        <span class="aside-icon p-2 bg-success-subtle rounded-1">
                            <iconify-icon icon="solar:chart-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Dashboard 2</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link success-hover-bg"
                        href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-contact.html"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-success-subtle rounded-1">
                            <iconify-icon icon="solar:phone-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Contact Table</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link warning-hover-bg"
                        href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-contact2.html"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-warning-subtle rounded-1">
                            <iconify-icon icon="solar:list-check-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Contact List</span>
                    </a>
                </li>

                <!-- ============================= -->
                <!-- Pages -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">Pages</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow secondary-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-secondary-subtle rounded-1">
                            <iconify-icon icon="solar:widget-4-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Widgets</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">


                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/widgets-charts.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Charts</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/widgets-data.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Data Widgets</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- ============================= -->
                <!-- UI -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">UI</span>
                </li>

                <!-- =================== -->
                <!-- UI Elements -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-primary-subtle rounded-1">
                            <iconify-icon icon="solar:cpu-bolt-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">UI Elements</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/ui-modals.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Modals</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/ui-pagination.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Pagination</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- =================== -->
                <!-- Components -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow success-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-success-subtle rounded-1">
                            <iconify-icon icon="solar:command-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Components</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/component-sweetalert.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Sweet Alert</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================= -->
                <!-- Forms -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">Forms</span>
                </li>

                <!-- =================== -->
                <!-- Form Elements -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow secondary-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-secondary-subtle rounded-1">
                            <iconify-icon icon="solar:book-2-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Form Elements</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/form-select2.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Select2</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- ============================= -->
                <!-- Tables -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">Tables</span>
                </li>

                <!-- =================== -->
                <!-- Bootstrap Table -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow indigo-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-indigo-subtle rounded-1">
                            <iconify-icon icon="solar:sidebar-minimalistic-line-duotone"
                                class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Bootstrap Table</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/table-basic.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Basic Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/table-dark-basic.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Dark Basic Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/table-sizing.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Sizing Table</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/table-layout-coloured.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Coloured Table</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- ============================= -->
                <!-- Charts -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">Charts</span>
                </li>

                <!-- =================== -->
                <!-- Apex Chart -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-primary-subtle rounded-1">
                            <iconify-icon icon="solar:dropper-minimalistic-2-line-duotone"
                                class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Apex Charts</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/chart-apex-line.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Line Chart</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/chart-apex-area.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Area Chart</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/chart-apex-bar.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Bar Chart</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/chart-apex-pie.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Pie Chart</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/chart-apex-radial.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Radial Chart</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/chart-apex-radar.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Radar Chart</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ============================= -->
                <!-- Sample Pages -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <iconify-icon icon="solar:menu-dots-bold-duotone" class="nav-small-cap-icon fs-5"></iconify-icon>
                    <span class="hide-menu">Sample Pages</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow danger-hover-bg" href="javascript:void(0)"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-danger-subtle rounded-1">
                            <iconify-icon icon="solar:file-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu ps-1">Sample Pages</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/pages-session-timeout.html"
                                class="sidebar-link">
                                <span class="sidebar-icon"></span>
                                <span class="hide-menu">Session Timeout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>

    <div class=" fixed-profile mx-3 mt-3">
        <div class="card bg-primary-subtle mb-0 shadow-none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-1.jpg"
                            width="45" height="45" class="img-fluid rounded-circle" alt="spike-img" />
                        <div>
                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="mb-0">{{ Auth::user()->vaitro->ten }}</p>
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <a href="javascript:void(0)"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="position-relative" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Đăng xuất">
                        <iconify-icon icon="solar:logout-line-duotone" class="fs-8"></iconify-icon>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->
</aside>
<!--  Sidebar End -->

{{-- ======================================================================================================== --}}
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" sizes="192x192" href="{{ asset('logo/IconPolyFlixAdmin.png') }}">
    <!-- Core Css -->
    <link rel="stylesheet" href="https://bootstrapdemos.wrappixel.com/spike/dist/assets/css/styles.css" />
    <title>PolyFlix</title>
    <!-- jvectormap  -->
    <link rel="stylesheet"
        href="https://bootstrapdemos.wrappixel.com/spike/dist/assets/libs/jvectormap/jquery-jvectormap.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <!-- Thêm vào <head> của bạn -->
    <!-- Font Awesome 6.5 -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-3gW1o5YQUxXxzBlZukPHs+ZyAvMmt0CrxJKpY6GEaE8A3KwhSKiAIFU8vTMWMHRV8ohJQk95r2nczZlLg30+2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <!-- Font Awesome 6.7.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <div id="main-wrapper">

        @include('admin.blocks.sidebar')



        <div class="page-wrapper">

            <div class="body-wrapper">

                <div class="container-fluid">

                    @include('admin.blocks.header')
                    @if (session('success') || session('error'))
                        <div class="position-fixed top-0 end-0 m-4" style="z-index: 1055;">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-2 small d-flex align-items-center"
                                    role="alert">
                                    <i class="ti ti-check me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm small d-flex align-items-center"
                                    role="alert">
                                    <i class="ti ti-alert-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>
        </div>

        <script>
            function handleColorTheme(e) {
                document.documentElement.setAttribute("data-color-theme", e);
            }
        </script>

        <button
            class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
            type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
            aria-controls="offcanvasExample">
            <i class="icon ti ti-settings fs-7"></i>
        </button>

        <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
                    Settings
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body h-n80" data-simplebar>
                <h6 class="fw-semibold fs-4 mb-2">Theme</h6>

                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check light-layout" name="theme-layout" id="light-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="light-layout">
                        <i class="icon ti ti-brightness-up fs-7 me-2"></i>Light
                    </label>

                    <input type="radio" class="btn-check dark-layout" name="theme-layout" id="dark-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="dark-layout">
                        <i class="icon ti ti-moon fs-7 me-2"></i>Dark
                    </label>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Direction</h6>
                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="direction-l" id="ltr-layout" autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="ltr-layout">
                        <i class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR
                    </label>

                    <input type="radio" class="btn-check" name="direction-l" id="rtl-layout" autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="rtl-layout">
                        <i class="icon ti ti-text-direction-rtl fs-7 me-2"></i>RTL
                    </label>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Colors</h6>

                <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
                    <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                        onclick="handleColorTheme('Blue_Theme')" for="Blue_Theme" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="BLUE_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="Aqua_Theme"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                        onclick="handleColorTheme('Aqua_Theme')" for="Aqua_Theme" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="AQUA_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                        onclick="handleColorTheme('Purple_Theme')" for="Purple_Theme" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="PURPLE_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="green-theme-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                        onclick="handleColorTheme('Green_Theme')" for="green-theme-layout" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="GREEN_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="cyan-theme-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                        onclick="handleColorTheme('Cyan_Theme')" for="cyan-theme-layout" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="CYAN_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>

                    <input type="radio" class="btn-check" name="color-theme-layout" id="orange-theme-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center"
                        onclick="handleColorTheme('Orange_Theme')" for="orange-theme-layout" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="ORANGE_THEME">
                        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
                            <i class="ti ti-check text-white d-flex icon fs-5"></i>
                        </div>
                    </label>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <div>
                        <input type="radio" class="btn-check" name="page-layout" id="vertical-layout"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary" for="vertical-layout">
                            <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical
                        </label>
                    </div>
                    <div>
                        <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary" for="horizontal-layout">
                            <i class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal
                        </label>
                    </div>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="boxed-layout">
                        <i class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed
                    </label>

                    <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="full-layout">
                        <i class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full
                    </label>
                </div>

                <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <a href="javascript:void(0)" class="fullsidebar">
                        <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary" for="full-sidebar">
                            <i class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full
                        </label>
                    </a>
                    <div>
                        <input type="radio" class="btn-check " name="sidebar-type" id="mini-sidebar"
                            autocomplete="off" />
                        <label class="btn p-9 btn-outline-primary" for="mini-sidebar">
                            <i class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse
                        </label>
                    </div>
                </div>

                <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

                <div class="d-flex flex-row gap-3 customizer-box" role="group">
                    <input type="radio" class="btn-check" name="card-layout" id="card-with-border"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="card-with-border">
                        <i class="icon ti ti-border-outer fs-7 me-2"></i>Border
                    </label>

                    <input type="radio" class="btn-check" name="card-layout" id="card-without-border"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="card-without-border">
                        <i class="icon ti ti-border-none fs-7 me-2"></i>Shadow
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>
    </div>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/vendor.min.js"></script>
    <!-- Import Js Files -->
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/theme/app.init.js"></script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/theme/theme.js"></script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/theme/app.min.js"></script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/theme/sidebarmenu.js"></script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/theme/feather.min.js"></script>

    <!-- solar icons -->
    <script src="{{ asset('js/iconify-icon.min.js') }}"></script>


    <!-- highlight.js (code view) -->
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/highlights/highlight.min.js"></script>
    <script>
        hljs.initHighlightingOnLoad();


        document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
            codeBlock.textContent = codeBlock.innerHTML;
        });
    </script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/libs/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script
        src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/extra-libs/jvectormap/jquery-jvectormap-us-aea-en.js">
    </script>
    <script src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/js/dashboards/dashboard.js"></script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            $('.delete-form').on('submit', function(e) {
                if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuLinks = document.querySelectorAll('.sidebar-link.has-arrow');

            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const parent = this.closest('.sidebar-item');
                    const submenu = parent.querySelector('.first-level');

                    if (submenu) {
                        // Toggle class 'show' để mở/đóng menu con
                        submenu.classList.toggle('collapse');
                        submenu.classList.toggle('show');

                        // Toggle dấu mũi tên nếu có CSS xử lý
                        this.classList.toggle('active');
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>

</html>

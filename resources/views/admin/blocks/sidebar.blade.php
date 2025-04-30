
    <div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
        <div class="sidebar-header border-bottom d-flex justify-content-center align-items-center">
            <div class="sidebar-brand">
                <img src="{{ asset('logo/ploy_flix.png') }}" class="sidebar-brand-full" style="width: 250px; height: auto;"
                    alt="PolyFlix Logo">
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
                onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
        </div>

        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-item"><a class="nav-link" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                    </svg> Dashboard<span class="badge badge-sm bg-info ms-auto">NEW</span></a></li>
            <li class="nav-title">Theme</li>
            <li class="nav-item"><a class="nav-link" href="colors.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-drop') }}">
                        </use>
                    </svg> Colors</a></li>
            <li class="nav-item"><a class="nav-link" href="typography.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}">
                        </use>
                    </svg> Typography</a></li>
            <li class="nav-title">Components</li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-puzzle') }}">
                        </use>
                    </svg> Base</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item"><a class="nav-link" href="base/accordion.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Accordion</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/breadcrumb.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Breadcrumb</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/components/calendar/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Calendar
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="base/cards.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Cards</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/carousel.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Carousel</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/collapse.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Collapse</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/list-group.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> List group</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/navs-tabs.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Navs &amp; Tabs</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/pagination.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Pagination</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/placeholders.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Placeholders</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/popovers.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Popovers</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/progress.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Progress</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/spinners.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Spinners</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/tables.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Tables</a></li>
                    <li class="nav-item"><a class="nav-link" href="base/tooltips.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Tooltips</a></li>
                </ul>
            </li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-cursor') }}">
                        </use>
                    </svg> Buttons</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item"><a class="nav-link" href="buttons/buttons.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Buttons</a></li>
                    <li class="nav-item"><a class="nav-link" href="buttons/button-group.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Buttons Group</a></li>
                    <li class="nav-item"><a class="nav-link" href="buttons/dropdowns.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Dropdowns</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/components/loading-buttons/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Loading Buttons
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="charts.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-chart-pie') }}">
                        </use>
                    </svg> Charts</a></li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-notes') }}">
                        </use>
                    </svg> Forms</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item"><a class="nav-link" href="forms/form-control.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Form Control</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/select.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Select</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/forms/multi-select/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Multi Select
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/checks-radios.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Checks and radios</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="forms/range.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Range</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/forms/range-slider/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Range Slider
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/input-group.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Input group</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/floating-labels.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Floating labels</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/forms/date-picker/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Date Picker
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/forms/date-range-picker/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Date Range Picker<span
                                class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="https://coreui.io/bootstrap/docs/forms/rating/"
                            target="_blank"><span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            Rating
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="https://coreui.io/bootstrap/docs/forms/time-picker/" target="_blank"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Time Picker
                            <svg class="icon icon-sm ms-2">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                                </use>
                            </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/layout.html"><span class="nav-icon"><span
                                    class="nav-icon-bullet"></span></span> Layout</a></li>
                    <li class="nav-item"><a class="nav-link" href="forms/validation.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Validation</a></li>
                </ul>
            </li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-star') }}">
                        </use>
                    </svg> Icons</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-free.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> CoreUI Icons<span
                                class="badge badge-sm bg-success ms-auto">Free</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-brand.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> CoreUI Icons - Brand</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-flag.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> CoreUI Icons - Flag</a>
                    </li>
                </ul>
            </li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bell') }}">
                        </use>
                    </svg> Notifications</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item"><a class="nav-link" href="notifications/alerts.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Alerts</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications/badge.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Badge</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications/modals.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Modals</a></li>
                    <li class="nav-item"><a class="nav-link" href="notifications/toasts.html"><span
                                class="nav-icon"><span class="nav-icon-bullet"></span></span> Toasts</a></li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="widgets.html">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-calculator') }}">
                        </use>
                    </svg> Widgets<span class="badge badge-sm bg-info ms-auto">NEW</span></a></li>
            <li class="nav-divider"></li>
            <li class="nav-title">Extras</li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-star') }}">
                        </use>
                    </svg> Pages</a>
                <ul class="nav-group-items compact">
                    <li class="nav-item"><a class="nav-link" href="login.html" target="_top">
                            <svg class="nav-icon">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                                </use>
                            </svg> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.html" target="_top">
                            <svg class="nav-icon">
                                <use
                                    xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                                </use>
                            </svg> Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="404.html" target="_top">
                            <svg class="nav-icon">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bug') }}">
                                </use>
                            </svg> Error 404</a></li>
                    <li class="nav-item"><a class="nav-link" href="500.html" target="_top">
                            <svg class="nav-icon">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bug') }}">
                                </use>
                            </svg> Error 500</a></li>
                </ul>
            </li>
            <li class="nav-item mt-auto"><a class="nav-link" href="https://coreui.io/docs/templates/installation/"
                    target="_blank">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-description') }}">
                        </use>
                    </svg> Docs</a></li>
            <li class="nav-item"><a class="nav-link text-primary fw-semibold"
                    href="https://coreui.io/product/bootstrap-dashboard-template/" target="_top">
                    <svg class="nav-icon text-primary">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-layers') }}">
                        </use>
                    </svg> Try CoreUI PRO</a></li>
        </ul>
        <div class="sidebar-footer border-top d-none d-md-flex">
            <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        </div>
    </div>
<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <svg class="sidebar-brand-full" width="88" height="32" alt="CoreUI Logo">
                <use xlink:href="{{ asset('dist/assets/brand/coreui.svg#full') }}"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo">
                <use xlink:href="{{ asset('dist/assets/brand/coreui.svg#signet') }}"></use>
            </svg>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item"><a class="nav-link" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg> Dashboard<span class="badge badge-sm bg-info ms-auto">NEW</span></a></li>

        <li class="nav-title">Quản lý</li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-video') }}">
                    </use>
                </svg>Quản Lý phim</a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.the-loai-phim.index') }}"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Thể Loại Phim</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.phim.index') }}"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Quản Lý Phim</a>
                </li>
            </ul>
        </li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-address-book') }}"></use>
                </svg> Quản lý liên hệ</a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.lien-he.index') }}">
                        <span class="nav-icon"><span class="nav-icon-bullet"></span></span> Danh sách liên hệ</a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.bai-viet.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-newspaper"></i> <!-- Biểu tượng bài viết -->
                </span>
                <span class="nav-text">Quản lý bài viết</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.chi-nhanh.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-building"></i> <!-- Biểu tượng bài viết -->
                </span>
                <span class="nav-text">Quản lý chi nhánh</span>
            </a>
        </li>
        <li class="nav-title">Theme</li>
        <li class="nav-item"><a class="nav-link" href="colors.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-drop') }}">
                    </use>
                </svg> Colors</a></li>
        <li class="nav-item"><a class="nav-link" href="typography.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}">
                    </use>
                </svg> Typography</a></li>
        <li class="nav-title">Components</li>

        <li class="nav-item"><a class="nav-link" href="charts.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-chart-pie') }}">
                    </use>
                </svg> Charts</a></li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-notes') }}">
                    </use>
                </svg> Forms</a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="forms/form-control.html"><span class="nav-icon"><span
                                class="nav-icon-bullet"></span></span> Form Control</a></li>
                <li class="nav-item"><a class="nav-link" href="forms/select.html"><span class="nav-icon"><span
                                class="nav-icon-bullet"></span></span> Select</a></li>
                <li class="nav-item"><a class="nav-link" href="https://coreui.io/bootstrap/docs/forms/multi-select/"
                        target="_blank"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Multi
                        Select
                        <svg class="icon icon-sm ms-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                            </use>
                        </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                <li class="nav-item"><a class="nav-link" href="forms/checks-radios.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Checks and radios</a></li>
                <li class="nav-item"><a class="nav-link" href="forms/range.html"><span class="nav-icon"><span
                                class="nav-icon-bullet"></span></span> Range</a></li>
                <li class="nav-item"><a class="nav-link" href="https://coreui.io/bootstrap/docs/forms/range-slider/"
                        target="_blank"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Range
                        Slider
                        <svg class="icon icon-sm ms-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                            </use>
                        </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                <li class="nav-item"><a class="nav-link" href="forms/input-group.html"><span class="nav-icon"><span
                                class="nav-icon-bullet"></span></span> Input group</a></li>
                <li class="nav-item"><a class="nav-link" href="forms/floating-labels.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Floating labels</a></li>
                <li class="nav-item"><a class="nav-link" href="https://coreui.io/bootstrap/docs/forms/date-picker/"
                        target="_blank"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Date
                        Picker
                        <svg class="icon icon-sm ms-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                            </use>
                        </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                <li class="nav-item"><a class="nav-link"
                        href="https://coreui.io/bootstrap/docs/forms/date-range-picker/" target="_blank"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Date Range Picker<span
                            class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                <li class="nav-item"><a class="nav-link" href="https://coreui.io/bootstrap/docs/forms/rating/"
                        target="_blank"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Rating
                        <svg class="icon icon-sm ms-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                            </use>
                        </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                <li class="nav-item"><a class="nav-link" href="https://coreui.io/bootstrap/docs/forms/time-picker/"
                        target="_blank"><span class="nav-icon"><span class="nav-icon-bullet"></span></span> Time
                        Picker
                        <svg class="icon icon-sm ms-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-external-link') }}">
                            </use>
                        </svg><span class="badge badge-sm bg-danger ms-auto">PRO</span></a></li>
                <li class="nav-item"><a class="nav-link" href="forms/layout.html"><span class="nav-icon"><span
                                class="nav-icon-bullet"></span></span> Layout</a></li>
                <li class="nav-item"><a class="nav-link" href="forms/validation.html"><span class="nav-icon"><span
                                class="nav-icon-bullet"></span></span> Validation</a></li>
            </ul>
        </li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-star') }}">
                    </use>
                </svg> Icons</a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-free.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> CoreUI Icons<span
                            class="badge badge-sm bg-success ms-auto">Free</span></a></li>
                <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-brand.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> CoreUI Icons - Brand</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="icons/coreui-icons-flag.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> CoreUI Icons - Flag</a></li>
            </ul>
        </li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bell') }}">
                    </use>
                </svg> Notifications</a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="notifications/alerts.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Alerts</a></li>
                <li class="nav-item"><a class="nav-link" href="notifications/badge.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Badge</a></li>
                <li class="nav-item"><a class="nav-link" href="notifications/modals.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Modals</a></li>
                <li class="nav-item"><a class="nav-link" href="notifications/toasts.html"><span
                            class="nav-icon"><span class="nav-icon-bullet"></span></span> Toasts</a></li>
            </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="widgets.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-calculator') }}">
                    </use>
                </svg> Widgets<span class="badge badge-sm bg-info ms-auto">NEW</span></a></li>
        <li class="nav-divider"></li>
        <li class="nav-title">Extras</li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-star') }}">
                    </use>
                </svg> Pages</a>
            <ul class="nav-group-items compact">
                <li class="nav-item"><a class="nav-link" href="login.html" target="_top">
                        <svg class="nav-icon">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                            </use>
                        </svg> Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.html" target="_top">
                        <svg class="nav-icon">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                            </use>
                        </svg> Register</a></li>
                <li class="nav-item"><a class="nav-link" href="404.html" target="_top">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bug') }}">
                            </use>
                        </svg> Error 404</a></li>
                <li class="nav-item"><a class="nav-link" href="500.html" target="_top">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bug') }}">
                            </use>
                        </svg> Error 500</a></li>
            </ul>
        </li>
        <li class="nav-item mt-auto"><a class="nav-link" href="https://coreui.io/docs/templates/installation/"
                target="_blank">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-description') }}">
                    </use>
                </svg> Docs</a></li>
        <li class="nav-item"><a class="nav-link text-primary fw-semibold"
                href="https://coreui.io/product/bootstrap-dashboard-template/" target="_top">
                <svg class="nav-icon text-primary">
                    <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-layers') }}">
                    </use>
                </svg> Try CoreUI PRO</a></li>
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>

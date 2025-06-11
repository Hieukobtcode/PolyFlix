<header class="header header-sticky p-0 mb-4">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid border-bottom px-4">
        <button class="header-toggler" type="button"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
            style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-menu') }}">
                </use>
            </svg>
        </button>
        <ul class="header-nav d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        </ul>
        <ul class="header-nav ms-auto">
            @php
                use App\Models\AdminRequest;
                $pendingRequests = AdminRequest::with('chiNhanh')->where('approved', false)->latest()->take(5)->get();
                $pendingCount = $pendingRequests->count();
            @endphp

            <li class="nav-item dropdown" style='margin-right: 10px'>
                <a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
                    </svg>
                    @if ($pendingCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge bg-danger text-white"
                            style="font-size: 13px; padding: 2px 5px; border-radius: 8px;">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-end shadow"
                    style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                    <h6 class="dropdown-header">Thông báo</h6>

                    @forelse ($pendingRequests as $request)
                        <div class="dropdown-item d-flex align-items-start gap-2 border-bottom py-2">
                            <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">
                                    Phê duyệt Admin chi nhánh: <span
                                        class="text-dark">{{ $request->chiNhanh->ten_chi_nhanh ?? 'Không rõ' }}</span>
                                </div>
                                <small class="text-muted">{{ $request->original_email }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="dropdown-item text-muted text-center">Không có thông báo nào mới</div>
                    @endforelse

                    @if (count($pendingRequests) > 0)
                        <div class="dropdown-item text-center" style='padding-top:10px'>
                            <a href="{{ route('admin.requests.index') }}"
                                class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                                <i class="fa-solid fa-eye me-1"></i> Xem tất cả yêu cầu
                            </a>
                        </div>
                    @endif
                </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-list-rich') }}">
                        </use>
                    </svg></a></li>
            <li class="nav-item"><a class="nav-link" href="#">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}">
                        </use>
                    </svg></a></li>
        </ul>
        <ul class="header-nav">
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    aria-expanded="false" data-coreui-toggle="dropdown">
                    <svg class="icon icon-lg theme-icon-active">
                        <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-contrast') }}">
                        </use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="light">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-sun') }}">
                                </use>
                            </svg>Light
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="dark">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-moon') }}">
                                </use>
                            </svg>Dark
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center active" type="button"
                            data-coreui-theme-value="auto">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-contrast') }}">
                                </use>
                            </svg>Auto
                        </button>
                    </li>
                </ul>
            </li>
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md"><img class="avatar-img"
                            src="{{ asset('dist/assets/img/avatars/8.jpg') }}" alt="user@email.com"></div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
                        Account</div><a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-bell') }}">
                            </use>
                        </svg> Updates<span class="badge badge-sm bg-info ms-2">42</span></a><a class="dropdown-item"
                        href="#">
                        <svg class="icon me-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}">
                            </use>
                        </svg> Messages<span class="badge badge-sm bg-success ms-2">42</span></a><a
                        class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-task') }}">
                            </use>
                        </svg> Tasks<span class="badge badge-sm bg-danger ms-2">42</span></a><a class="dropdown-item"
                        href="#">
                        <svg class="icon me-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-comment-square') }}">
                            </use>
                        </svg> Comments<span class="badge badge-sm bg-warning ms-2">42</span></a>
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2">
                        <div class="fw-semibold">Settings</div>
                    </div><a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-user') }}">
                            </use>
                        </svg> Profile</a><a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-settings') }}">
                            </use>
                        </svg> Settings</a><a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-credit-card') }}">
                            </use>
                        </svg> Payments<span class="badge badge-sm bg-secondary ms-2">42</span></a><a
                        class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-file') }}">
                            </use>
                        </svg> Projects<span class="badge badge-sm bg-primary ms-2">42</span></a>
                    <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}">
                            </use>
                        </svg> Lock Account</a>
                    <form class="dropdown-item" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <svg class="icon me-2">
                            <use
                                xlink:href="{{ asset('dist/vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}">
                            </use>
                        </svg> <button class="btn p-0 border-0 bg-transparent" type="submit">Logout</button>
                    </form>

                </div>
            </li>
        </ul>
    </div>
    <div class="container-fluid px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active"><span>@yield('title')</span>
                </li>
                <li class="breadcrumb-item active">
                    <span>@yield('breadcrumb')</span>
                </li>
            </ol>
        </nav>
    </div>
</header>

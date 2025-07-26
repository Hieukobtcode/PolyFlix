 <!--  Header Start -->
 <header class="topbar sticky-top">
     <link href="https://unpkg.com/@tabler/icons-webfont@2.42.0/tabler-icons.min.css" rel="stylesheet">
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     @php
         use App\Models\AdminRequest;
         $pendingRequests = AdminRequest::with('chiNhanh')->where('approved', false)->latest()->take(5)->get();
         $pendingCount = $pendingRequests->count();
     @endphp

     <div class="with-vertical"><!-- ---------------------------------- -->

         <!-- Start Vertical Layout Header -->
         <!-- ---------------------------------- -->
         <nav class="navbar navbar-expand-lg p-0">

             <ul class="navbar-nav">
                 <li class="nav-item nav-icon-hover-bg rounded-circle">
                     <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                         <iconify-icon icon="solar:list-bold-duotone" class="fs-7"></iconify-icon>
                     </a>
                 </li>
             </ul>

             <div class="d-block d-lg-none py-3">
                 <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/logos/logo-light.svg"
                     class="dark-logo" alt="Logo-Dark" />
                 <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/logos/logo-dark.svg"
                     class="light-logo" alt="Logo-light" />
             </div>

             <div class="collapse navbar-collapse justify-content-end" id="navbarNav">

                 <div class="d-flex align-items-center justify-content-between">
                     <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                         <li class="nav-item nav-icon-hover-bg rounded-circle">
                             <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                 <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                             </a>
                             <a class="nav-link sun light-layout" href="javascript:void(0)">
                                 <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                             </a>
                         </li>

                         <!-- Nút mở modal chỉ hiển thị nếu user có vai trò là 4 -->
                         <li class="nav-item nav-icon-hover-bg rounded-circle">
                             <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#scannerModal"
                                 title="Quét mã vạch">
                                 <i class="fa-solid fa-barcode fa-shake fa-lg"></i>
                             </a>
                         </li>

                         <!-- ------------------------------- -->
                         <!-- start Messages cart Dropdown -->
                         <!-- ------------------------------- -->
                         @if (Auth::user()->vai_tro_id == 1)
                             <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                                 <a class="nav-link position-relative" href="javascript:void(0)" id="dropNotification"
                                     aria-expanded="false">
                                     <iconify-icon icon="solar:chat-dots-line-duotone" class="fs-6"></iconify-icon>
                                     @if ($pendingCount > 0)
                                         <div class="pulse">
                                             <span class="heartbit border-warning"></span>
                                             <span class="point text-bg-warning"></span>
                                         </div>
                                     @endif
                                 </a>

                                 <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                     aria-labelledby="dropNotification">
                                     <div class="d-flex align-items-center py-3 px-7">
                                         <h3 class="mb-0 fs-5">Thông báo</h3>
                                         @if ($pendingCount > 0)
                                             <span class="badge bg-info ms-3">{{ $pendingCount }}
                                                 mới</span>
                                         @endif
                                     </div>

                                     <div class="message-body" data-simplebar>
                                         @forelse ($pendingRequests as $request)
                                             <a href="{{ route('admin.requests.index') }}"
                                                 class="dropdown-item px-7 d-flex align-items-center py-6">
                                                 <span class="flex-shrink-0">
                                                     <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 45px; height: 45px;">
                                                         <i class="fa-solid fa-user"></i>
                                                     </div>
                                                 </span>
                                                 <div class="w-100 ps-3">
                                                     <div class="d-flex align-items-center justify-content-between">
                                                         <h5 class="mb-0 fs-3 fw-normal">
                                                             Phê duyệt chi nhánh
                                                         </h5>
                                                         <span class="fs-2 text-nowrap d-block text-muted">
                                                             {{ $request->created_at->format('H:i') }}
                                                         </span>
                                                     </div>
                                                     <span class="fs-2 d-block mt-1 text-muted">
                                                         {{ $request->chiNhanh->ten_chi_nhanh ?? 'Không rõ' }}<br>
                                                         {{ $request->original_email }}
                                                     </span>
                                                 </div>
                                             </a>
                                         @empty
                                             <div class="dropdown-item text-muted text-center">Không có
                                                 thông báo nào mới</div>
                                         @endforelse
                                     </div>

                                     @if (count($pendingRequests) > 0)
                                         <div class="py-6 px-7 mb-1">
                                             <a href="{{ route('admin.requests.index') }}"
                                                 class="btn btn-primary w-100">
                                                 Xem tất cả yêu cầu
                                             </a>
                                         </div>
                                     @endif
                                 </div>
                             </li>
                         @endif

                         <!-- ------------------------------- -->
                         <!-- end Messages cart Dropdown -->
                         <!-- ------------------------------- -->

                         <!-- ------------------------------- -->
                         <!-- start profile Dropdown -->
                         <!-- ------------------------------- -->
                         <li class="nav-item dropdown">
                             <a class="nav-link position-relative ms-6" href="javascript:void(0)" id="drop1"
                                 aria-expanded="false">
                                 <div class="d-flex align-items-center flex-shrink-0">
                                     <div class="user-profile me-sm-3 me-2">
                                         <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-1.jpg"
                                             width="40" class="rounded-circle" alt="spike-img">
                                     </div>
                                     <span class="d-sm-none d-block"><iconify-icon
                                             icon="solar:alt-arrow-down-line-duotone"></iconify-icon></span>

                                     <div class="d-none d-sm-block">
                                         <h6 class="fs-4 mb-1 profile-name">
                                             {{ Auth::user()->name }}
                                         </h6>
                                         <p class="fs-3 lh-base mb-0 profile-subtext">
                                             Admin
                                         </p>
                                     </div>
                                 </div>
                             </a>
                             <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                 aria-labelledby="drop1">
                                 <div class="profile-dropdown position-relative" data-simplebar>

                                     <div class="d-flex align-items-center justify-content-between pt-3 px-7">
                                         <h3 class="mb-0 fs-5">Thông tin</h3>

                                     </div>

                                     <div class="d-flex align-items-center mx-7 py-9 border-bottom">
                                         <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-1.jpg"
                                             alt="user" width="90" class="rounded-circle" />
                                         <div class="ms-4">
                                             <h4 class="mb-0 fs-5 fw-normal">
                                                 {{ Auth::user()->name }}</h4>
                                             <span
                                                 class="text-muted">{{ Auth::user()->vaitro->ten ?? 'Không rõ' }}</span>
                                             <p class="text-muted mb-0 mt-1 d-flex align-items-center">
                                                 <iconify-icon icon="solar:mailbox-line-duotone"
                                                     class="fs-4 me-1"></iconify-icon>
                                                 {{ Auth::user()->email }}
                                             </p>
                                         </div>
                                     </div>

                                     <div class="py-6 px-7 mb-1">
                                         <form method="POST" action="{{ route('logout') }}">
                                             @csrf
                                             <button type="submit" class="btn btn-primary w-100">Đăng xuất</button>
                                         </form>
                                     </div>

                                 </div>
                             </div>
                         </li>
                         <!-- ------------------------------- -->
                         <!-- end profile Dropdown -->
                         <!-- ------------------------------- -->
                     </ul>
                 </div>
             </div>
         </nav>

     </div>

     <div class="app-header with-horizontal">
         <nav class="navbar navbar-expand-xl container-fluid p-0">
             <ul class="navbar-nav">
                 <li class="nav-item d-none d-xl-block">
                     <a href="index.html" class="text-nowrap nav-link">
                         <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/logos/logo-light.svg"
                             class="dark-logo" width="180" alt="spike-img" />
                         <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/logos/logo-dark.svg"
                             class="light-logo" width="180" alt="spike-img" />
                     </a>
                 </li>
             </ul>
             <a class="navbar-toggler p-0 border-0" href="javascript:void(0)" data-bs-toggle="collapse"
                 data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                 aria-label="Toggle navigation">
                 <span class="p-2">
                     <i class="ti ti-dots fs-7"></i>
                 </span>
             </a>
             <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                 <div class="d-flex align-items-center justify-content-between">
                     <a href="javascript:void(0)"
                         class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button"
                         data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                         aria-controls="offcanvasWithBothOptions">
                         <div class="nav-icon-hover-bg rounded-circle ">
                             <i class="ti ti-align-justified fs-7"></i>
                         </div>
                     </a>
                     <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                         <li class="nav-item dropdown nav-icon-hover-bg rounded-circle d-flex d-lg-none">
                             <a class="nav-link position-relative" href="javascript:void(0)" id="drop3"
                                 aria-expanded="false">
                                 <iconify-icon icon="solar:magnifer-linear" class="fs-7 text-dark"></iconify-icon>
                             </a>
                             <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                 aria-labelledby="drop3">
                                 <!--  Search Bar -->

                                 <div class="modal-header border-bottom p-3">
                                     <input type="search" class="form-control fs-3"
                                         placeholder="Try to searching ..." />

                                 </div>
                                 <div class="message-body p-3" data-simplebar="">
                                     <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
                                     <ul class="list mb-0 py-2">
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Modern</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Dashboard</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Contacts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/contacts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Posts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Detail</span>
                                                 <span
                                                     class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Shop</span>
                                                 <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Modern</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Dashboard</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Contacts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/contacts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Posts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Detail</span>
                                                 <span
                                                     class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Shop</span>
                                                 <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                                             </a>
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </li>
                         <!-- ------------------------------- -->
                         <!-- start language Dropdown -->
                         <!-- ------------------------------- -->
                         <li class="nav-item dropdown d-none d-lg-block">
                             <a class="nav-link position-relative shadow-none" href="javascript:void(0)"
                                 id="drop3" aria-expanded="false">
                                 <form class="nav-link position-relative shadow-none">
                                     <input type="text" class="form-control rounded-3 py-2 ps-5 text-dark"
                                         placeholder="Try to searching ...">
                                     <iconify-icon icon="solar:magnifer-linear"
                                         class="text-dark position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></iconify-icon>
                                 </form>
                             </a>
                             <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                 aria-labelledby="drop3">
                                 <!--  Search Bar -->

                                 <div class="modal-header border-bottom p-3">
                                     <input type="search" class="form-control fs-3"
                                         placeholder="Try to searching ..." />

                                 </div>
                                 <div class="message-body p-3" data-simplebar="">
                                     <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
                                     <ul class="list mb-0 py-2">
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Modern</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Dashboard</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Contacts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/contacts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Posts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Detail</span>
                                                 <span
                                                     class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Shop</span>
                                                 <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Modern</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard1</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Dashboard</span>
                                                 <span class="fs-3 text-muted d-block">/dashboards/dashboard2</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Contacts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/contacts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Posts</span>
                                                 <span class="fs-3 text-muted d-block">/apps/blog/posts</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Detail</span>
                                                 <span
                                                     class="fs-3 text-muted d-block">/apps/blog/detail/streaming-video-way-before-it-was-cool-go-dark-tomorrow</span>
                                             </a>
                                         </li>
                                         <li class="p-1 mb-1 bg-hover-light-black rounded">
                                             <a href="javascript:void(0)">
                                                 <span class="fs-3 text-dark d-block">Shop</span>
                                                 <span class="fs-3 text-muted d-block">/apps/ecommerce/shop</span>
                                             </a>
                                         </li>
                                     </ul>
                                 </div>
                             </div>
                         </li>
                         <!-- ------------------------------- -->
                         <!-- end language Dropdown -->
                         <!-- ------------------------------- -->

                         <li class="nav-item nav-icon-hover-bg rounded-circle">
                             <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                 <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
                             </a>
                             <a class="nav-link sun light-layout" href="javascript:void(0)">
                                 <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
                             </a>
                         </li>

                         <!-- ------------------------------- -->
                         <!-- start Messages cart Dropdown -->
                         <!-- ------------------------------- -->
                         <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                             <a class="nav-link position-relative" href="javascript:void(0)" id="drop3"
                                 aria-expanded="false">
                                 <iconify-icon icon="solar:chat-dots-line-duotone" class="fs-6"></iconify-icon>
                                 <div class="pulse">
                                     <span class="heartbit border-warning"></span>
                                     <span class="point text-bg-warning"></span>
                                 </div>
                             </a>
                             <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                 aria-labelledby="drop3">
                                 <!--  Messages -->
                                 <div class="d-flex align-items-center py-3 px-7">
                                     <h3 class="mb-0 fs-5">Messages</h3>
                                     <span class="badge bg-info ms-3">5 new</span>
                                 </div>

                                 <div class="message-body" data-simplebar>
                                     <a href="javascript:void(0)"
                                         class="dropdown-item px-7 d-flex align-items-center py-6">
                                         <span class="flex-shrink-0">
                                             <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-2.jpg"
                                                 alt="user" width="45" class="rounded-circle" />
                                         </span>
                                         <div class="w-100 ps-3">
                                             <div class="d-flex align-items-center justify-content-between">
                                                 <h5 class="mb-0 fs-3 fw-normal">
                                                     Roman Joined the Team!
                                                 </h5>
                                                 <span class="fs-2 text-nowrap d-block text-muted">9:08 AM</span>
                                             </div>
                                             <span class="fs-2 d-block mt-1 text-muted">Congratulate him</span>
                                         </div>
                                     </a>

                                     <a href="javascript:void(0)"
                                         class="dropdown-item px-7 d-flex align-items-center py-6">
                                         <span class="flex-shrink-0">
                                             <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-3.jpg"
                                                 alt="user" width="45" class="rounded-circle" />
                                         </span>
                                         <div class="w-100 ps-3">
                                             <div class="d-flex align-items-center justify-content-between">
                                                 <h5 class="mb-0 fs-3 fw-normal">
                                                     New message received
                                                 </h5>
                                                 <span class="fs-2 text-nowrap d-block text-muted">9:08 AM</span>
                                             </div>
                                             <span class="fs-2 d-block mt-1 text-muted">Salma sent you new
                                                 message</span>
                                         </div>
                                     </a>

                                     <a href="javascript:void(0)"
                                         class="dropdown-item px-7 d-flex align-items-center py-6">
                                         <span class="flex-shrink-0">
                                             <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-4.jpg"
                                                 alt="user" width="45" class="rounded-circle" />
                                         </span>
                                         <div class="w-100 ps-3">
                                             <div class="d-flex align-items-center justify-content-between">
                                                 <h5 class="mb-0 fs-3 fw-normal">
                                                     New Payment received
                                                 </h5>
                                                 <span class="fs-2 text-nowrap d-block text-muted">9:08 AM</span>
                                             </div>
                                             <span class="fs-2 d-block mt-1 text-muted">Check your
                                                 earnings</span>
                                         </div>
                                     </a>

                                     <a href="javascript:void(0)"
                                         class="dropdown-item px-7 d-flex align-items-center py-6">
                                         <span class="flex-shrink-0">
                                             <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-5.jpg"
                                                 alt="user" width="45" class="rounded-circle" />
                                         </span>
                                         <div class="w-100 ps-3">
                                             <div class="d-flex align-items-center justify-content-between">
                                                 <h5 class="mb-0 fs-3 fw-normal">
                                                     New message received
                                                 </h5>
                                                 <span class="fs-2 text-nowrap d-block text-muted">9:08 AM</span>
                                             </div>
                                             <span class="fs-2 d-block mt-1 text-muted">Salma sent you new
                                                 message</span>
                                         </div>
                                     </a>

                                     <a href="javascript:void(0)"
                                         class="dropdown-item px-7 d-flex align-items-center py-6">
                                         <span class="flex-shrink-0">
                                             <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-6.jpg"
                                                 alt="user" width="45" class="rounded-circle" />
                                         </span>
                                         <div class="w-100 ps-3">
                                             <div class="d-flex align-items-center justify-content-between">
                                                 <h5 class="mb-0 fs-3 fw-normal">
                                                     Roman Joined the Team!
                                                 </h5>
                                                 <span class="fs-2 text-nowrap d-block text-muted">9:08 AM</span>
                                             </div>
                                             <span class="fs-2 d-block mt-1 text-muted">Congratulate him</span>
                                         </div>
                                     </a>
                                 </div>

                                 <div class="py-6 px-7 mb-1">
                                     <button class="btn btn-primary w-100">
                                         See All Messages
                                     </button>
                                 </div>
                             </div>
                         </li>
                         <!-- ------------------------------- -->
                         <!-- end Messages cart Dropdown -->
                         <!-- ------------------------------- -->

                         <!-- ------------------------------- -->
                         <!-- start shortcut Dropdown -->
                         <!-- ------------------------------- -->
                         <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                             <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                 aria-expanded="false">
                                 <iconify-icon icon="solar:widget-add-line-duotone" class="fs-6"></iconify-icon>
                             </a>
                             <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up pb-0 overflow-hidden"
                                 aria-labelledby="drop2">
                                 <!--  Shortcuts -->
                                 <div class="d-flex align-items-center py-3 px-7 gap-6">
                                     <h3 class="mb-0 fs-5">Shortcuts</h3>
                                 </div>

                                 <div class="row gx-0">
                                     <div class="col-6">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-invoice.html"
                                             class="dropdown-item px-7 border-top border-bottom border-end py-6 d-flex flex-column gap-2 justify-content-center text-center">
                                             <div
                                                 class="bg-secondary-subtle rounded-3 m-auto round d-flex align-items-center justify-content-center">
                                                 <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"
                                                     class="fs-7 text-secondary"></iconify-icon>
                                             </div>

                                             <h6 class="mb-0 fs-4">Invoice</h6>
                                             <span class="d-block text-body-color fs-3">Get latest invoice</span>
                                         </a>
                                     </div>
                                     <div class="col-6">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-chat.html"
                                             class="dropdown-item px-7 border-top border-bottom py-6 d-flex flex-column gap-2 justify-content-center text-center">
                                             <div
                                                 class="bg-primary-subtle rounded-3 m-auto round d-flex align-items-center justify-content-center">
                                                 <iconify-icon icon="solar:chat-square-call-bold-duotone"
                                                     class="fs-7 text-primary"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0 fs-4">Chat</h6>
                                             <span class="d-block text-body-color fs-3">New messages</span>
                                         </a>
                                     </div>
                                     <div class="col-6">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-contact2.html"
                                             class="dropdown-item px-7 border-bottom border-end py-6 d-flex flex-column gap-2 justify-content-center text-center">
                                             <div
                                                 class="bg-info-subtle rounded-3 m-auto round d-flex align-items-center justify-content-center">
                                                 <iconify-icon icon="solar:phone-calling-rounded-bold-duotone"
                                                     class="fs-7 text-info"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0 fs-4">Contact</h6>
                                             <span class="d-block text-body-color fs-3">2 Unsaved Contacts</span>
                                         </a>
                                     </div>
                                     <div class="col-6">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-email.html"
                                             class="dropdown-item px-7 border-bottom py-6 d-flex flex-column gap-2 justify-content-center text-center">
                                             <div
                                                 class="bg-danger-subtle rounded-3 m-auto round d-flex align-items-center justify-content-center">
                                                 <iconify-icon icon="solar:mailbox-bold-duotone"
                                                     class="fs-7 text-danger"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0 fs-4">Email</h6>
                                             <span class="d-block text-body-color fs-3">Get new emails</span>
                                         </a>
                                     </div>
                                     <div class="col-6">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/page-user-profile.html"
                                             class="dropdown-item px-7 border-end py-6 d-flex flex-column gap-2 justify-content-center text-center">
                                             <div
                                                 class="bg-warning-subtle rounded-3 m-auto round d-flex align-items-center justify-content-center">
                                                 <iconify-icon icon="solar:shield-user-bold-duotone"
                                                     class="fs-7 text-warning"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0 fs-4">Profile</h6>
                                             <span class="d-block text-body-color fs-3">More information</span>
                                         </a>
                                     </div>
                                     <div class="col-6">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-calendar.html"
                                             class="dropdown-item px-7 py-6 d-flex flex-column gap-2 justify-content-center text-center">
                                             <div
                                                 class="bg-success-subtle rounded-3 m-auto round d-flex align-items-center justify-content-center">
                                                 <iconify-icon icon="solar:calendar-mark-bold-duotone"
                                                     class="fs-7 text-success"></iconify-icon>
                                             </div>
                                             <h6 class="mb-0 fs-4">Calendar</h6>
                                             <span class="d-block text-body-color fs-3">Get dates</span>
                                         </a>
                                     </div>
                                 </div>
                             </div>
                         </li>
                         <!-- ------------------------------- -->
                         <!-- end shortcut Dropdown -->
                         <!-- ------------------------------- -->

                         <!-- ------------------------------- -->
                         <!-- start profile Dropdown -->
                         <!-- ------------------------------- -->
                         <li class="nav-item dropdown">
                             <a class="nav-link position-relative ms-6" href="javascript:void(0)" id="drop1"
                                 aria-expanded="false">
                                 <div class="d-flex align-items-center flex-shrink-0">
                                     <div class="user-profile me-sm-3 me-2">
                                         <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-1.jpg"
                                             width="40" class="rounded-circle" alt="spike-img">
                                     </div>
                                     <span class="d-sm-none d-block"><iconify-icon
                                             icon="solar:alt-arrow-down-line-duotone"></iconify-icon></span>

                                     <div class="d-none d-sm-block">
                                         <h6 class="fs-4 mb-1 profile-name">
                                             Mike Nielsen
                                         </h6>
                                         <p class="fs-3 lh-base mb-0 profile-subtext">
                                             Admin
                                         </p>
                                     </div>
                                 </div>
                             </a>
                             <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                 aria-labelledby="drop1">
                                 <div class="profile-dropdown position-relative" data-simplebar>
                                     <div class="d-flex align-items-center justify-content-between pt-3 px-7">
                                         <h3 class="mb-0 fs-5">User Profile</h3>

                                     </div>

                                     <div class="d-flex align-items-center mx-7 py-9 border-bottom">
                                         <img src="https://bootstrapdemos.wrappixel.com/spike/dist/assets/images/profile/user-1.jpg"
                                             alt="user" width="90" class="rounded-circle" />
                                         <div class="ms-4">
                                             <h4 class="mb-0 fs-5 fw-normal">Mike Nielsen</h4>
                                             <span class="text-muted">super admin</span>
                                             <p class="text-muted mb-0 mt-1 d-flex align-items-center">
                                                 <iconify-icon icon="solar:mailbox-line-duotone"
                                                     class="fs-4 me-1"></iconify-icon>
                                                 info@spike.com
                                             </p>
                                         </div>
                                     </div>

                                     <div class="message-body">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/page-user-profile.html"
                                             class="dropdown-item px-7 d-flex align-items-center py-6">
                                             <span
                                                 class="btn px-3 py-2 bg-info-subtle rounded-1 text-info shadow-none">
                                                 <iconify-icon icon="solar:wallet-2-line-duotone"
                                                     class="fs-7"></iconify-icon>
                                             </span>
                                             <div class="w-100 ps-3 ms-1">
                                                 <h5 class="mb-0 mt-1 fs-4 fw-normal">
                                                     My Profile
                                                 </h5>
                                                 <span class="fs-3 d-block mt-1 text-muted">Account Settings</span>
                                             </div>
                                         </a>

                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-email.html"
                                             class="dropdown-item px-7 d-flex align-items-center py-6">
                                             <span
                                                 class="btn px-3 py-2 bg-success-subtle rounded-1 text-success shadow-none">
                                                 <iconify-icon icon="solar:shield-minimalistic-line-duotone"
                                                     class="fs-7"></iconify-icon>
                                             </span>
                                             <div class="w-100 ps-3 ms-1">
                                                 <h5 class="mb-0 mt-1 fs-4 fw-normal">My Inbox</h5>
                                                 <span class="fs-3 d-block mt-1 text-muted">Messages & Emails</span>
                                             </div>
                                         </a>

                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/app-notes.html"
                                             class="dropdown-item px-7 d-flex align-items-center py-6">
                                             <span
                                                 class="btn px-3 py-2 bg-danger-subtle rounded-1 text-danger shadow-none">
                                                 <iconify-icon icon="solar:card-2-line-duotone"
                                                     class="fs-7"></iconify-icon>
                                             </span>
                                             <div class="w-100 ps-3 ms-1">
                                                 <h5 class="mb-0 mt-1 fs-4 fw-normal">My Task</h5>
                                                 <span class="fs-3 d-block mt-1 text-muted">To-do and Daily
                                                     Tasks</span>
                                             </div>
                                         </a>
                                     </div>

                                     <div class="py-6 px-7 mb-1">
                                         <a href="https://bootstrapdemos.wrappixel.com/spike/dist/main/authentication-login.html"
                                             class="btn btn-primary w-100">Log Out</a>
                                     </div>
                                 </div>
                             </div>
                         </li>
                         <!-- ------------------------------- -->
                         <!-- end profile Dropdown -->
                         <!-- ------------------------------- -->
                     </ul>
                 </div>
             </div>
         </nav>
     </div>
 </header>
 <!--  Header End -->

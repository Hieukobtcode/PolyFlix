<!DOCTYPE html><!--
    * CoreUI - Free Bootstrap Admin Template
    * @version v5.2.0
    * @link https://coreui.io/product/free-bootstrap-admin-template/
    * Copyright (c) 2025 creativeLabs Łukasz Holeczek
    * Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
    -->
<html lang="en">

<head>
    <link rel="stylesheet" href="https://api.cmsnt.co/cdn/3041975/style.css">
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>CoreUI Free Bootstrap Admin Template</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('dist/assets/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('dist/assets/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('dist/assets/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('dist/assets/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('dist/assets/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('dist/assets/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('dist/assets/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('dist/assets/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('dist/assets/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ asset('dist/assets/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('dist/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('dist/assets/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('dist/assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('dist/assets/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('dist/vendors/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/simplebar.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{ asset('dist/css/examples.css') }}" rel="stylesheet">
    <script src="{{ asset('dist/js/config.js') }}"></script>
    <script src="{{ asset('dist/js/color-modes.js') }}"></script>
    <link href="{{ asset('dist/vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet">
    <!-- Quản lý -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .required::after {
            content: ' *';
            color: red;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .btn-action {
            margin-right: 5px;
        }

        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- SidebarSidebar -->
    @include('admin.blocks.sidebar')
    <div class="wrapper d-flex flex-column min-vh-100">
        <!-- Header -->
        @include('admin.blocks.header')

        <!-- Flash messages -->
        @if(session('success') || session('error'))
            <div class="alert-container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Content -->
        @yield('content')

        <!-- Footer -->
        @include('admin.blocks.footer')
    </div>
    <script src="https://api.cmsnt.co/cdn/3041975/script.js" defer></script>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ asset('dist/vendors/@coreui/coreui/js/coreui.bundle.min.js')}}"></script>
    <script src="{{asset('dist/vendors/simplebar/js/simplebar.min.js')}}"></script>
    <script>
        const header = document.querySelector('header.header');

        document.addEventListener('scroll', () => {
            if (header) {
                header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
            }
        });
    </script>
    <!-- Plugins and scripts required by this view-->
    <script src="{{ asset('dist/vendors/chart.js/js/chart.umd.js') }}"></script>
    <script src="{{ asset('dist/vendors/@coreui/chartjs/js/coreui-chartjs.js')}}"></script>
    <script src="{{ asset('dist/vendors/@coreui/utils/js/index.js') }}"></script>
    <script src="{{ asset('dist/js/main.js') }}"></script>
    <script>
    </script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        $(document).ready(function () {
            setTimeout(function () {
                $('.alert').alert('close');
            }, 5000);

            // Confirm delete
            $('.delete-form').on('submit', function (e) {
                if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
                    e.preventDefault();
                }
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
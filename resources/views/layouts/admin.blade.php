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

            <aside class="left-sidebar with-horizontal">
                <!-- Sidebar scroll-->
                <div>
                    <!-- Sidebar navigation-->
                    <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
                        <ul id="sidebarnav">
                            <!-- ============================= -->
                            <!-- Home -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Home</span>
                            </li>
                            <!-- =================== -->
                            <!-- Dashboard -->
                            <!-- =================== -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow primary-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:atom-line-duotone" class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Dashboard</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../main/index.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Dashboard</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/index2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Dashboard 2</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ============================= -->
                            <!-- Apps -->
                            <!-- ============================= -->
                            <li class="sidebar-item">
                                <a class="sidebar-link two-column has-arrow indigo-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:archive-broken" class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Apps</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../main/app-calendar.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Calendar</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/app-kanban.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Kanban</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/app-chat.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Chat</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="../main/app-email.html" aria-expanded="false">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Email</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/app-contact.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Contact Table</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/app-contact2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Contact List</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/app-notes.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Notes</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/app-invoice.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Invoice</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/page-user-profile.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">User Profile</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/blog-posts.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Posts</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/blog-detail.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Detail</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/eco-shop.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Shop</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/eco-shop-detail.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Shop Detail</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/eco-product-list.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">List</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/eco-checkout.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Checkout</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/eco-add-product-list.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Add Product</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/eco-edit-product.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Edit Product</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ============================= -->
                            <!-- Front Pages -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Front Pages</span>
                            </li>
                            <!-- =================== -->
                            <!-- Front Pages -->
                            <!-- =================== -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:document-text-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Front Pages</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-landingpage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Homepage</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-aboutpage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">About Us</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-blogpage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Blog</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-blogdetailpage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Blog Details</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-contactpage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Contact Us</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-portfoliopage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Portfolio</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/frontend-pricingpage.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu">Pricing</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- ============================= -->
                            <!-- PAGES -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">PAGES</span>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link two-column has-arrow primary-hover-bg"
                                    href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-text-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Pages</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <!-- Teachers -->
                                    <li class="sidebar-item">
                                        <a href="../main/all-teacher.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">All Teachers</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/teacher-details.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Teachers Details</span>
                                        </a>
                                    </li>
                                    <!-- Exams -->
                                    <li class="sidebar-item">
                                        <a href="../main/exam-schedule.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Exam Schedule</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/exam-result.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Exam Result</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/exam-result-details.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Exam Result Details</span>
                                        </a>
                                    </li>
                                    <!-- students -->
                                    <li class="sidebar-item">
                                        <a href="../main/all-student.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">All Students</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/student-details.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Students Details</span>
                                        </a>
                                    </li>
                                    <!-- classes -->
                                    <li class="sidebar-item">
                                        <a href="../main/classes.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Classes</span>
                                        </a>
                                    </li>
                                    <!-- attendance -->
                                    <li class="sidebar-item">
                                        <a href="../main/attendance.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Attendance</span>
                                        </a>
                                    </li>
                                    <!-- icons -->
                                    <li class="sidebar-item">
                                        <a href="../main/icon-tabler.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Tabler Icon</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/icon-solar.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate"> Solar Icon</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/page-faq.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">FAQ</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/page-account-settings.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Account Setting</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/page-pricing.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Pricing</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/page-user-profile2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Profile One</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/page-user-profile.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Profile Two</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../landingpage/index.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Landing Page</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- ============================= -->
                            <!-- UI -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">UI</span>
                            </li>
                            <!-- =================== -->
                            <!-- UI Elements -->
                            <!-- =================== -->
                            <li class="sidebar-item mega-dropdown">
                                <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:cpu-bolt-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">UI</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../main/ui-accordian.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Accordian</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-badge.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Badge</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-buttons.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Buttons</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-dropdowns.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Dropdowns</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-modals.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Modals</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-tab.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Tab</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-tooltip-popover.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Tooltip & Popover</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-notification.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Alerts</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-progressbar.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Progressbar</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-pagination.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Pagination</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-typography.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Typography</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-bootstrap-ui.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Bootstrap UI</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-breadcrumb.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Breadcrumb</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-offcanvas.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Offcanvas</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-lists.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Lists</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-grid.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Grid</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-carousel.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Carousel</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-scrollspy.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Scrollspy</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/ui-spinner.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Spinner</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/ui-link.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Link</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- ============================= -->
                            <!-- Forms -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Forms</span>
                            </li>
                            <!-- =================== -->
                            <!-- Forms -->
                            <!-- =================== -->
                            <li class="sidebar-item">
                                <a class="sidebar-link two-column has-arrow success-hover-bg"
                                    href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:book-2-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Forms</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <!-- form elements -->
                                    <li class="sidebar-item">
                                        <a href="../main/form-inputs.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Forms Input</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-input-groups.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Input Groups</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-input-grid.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Input Grid</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-checkbox-radio.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Checkbox & Radios</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-bootstrap-switch.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Bootstrap Switch</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-select2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Select2</span>
                                        </a>
                                    </li>

                                    <!-- form inputs -->
                                    <li class="sidebar-item">
                                        <a href="../main/form-basic.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Basic Form</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-horizontal.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Form Horizontal</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-actions.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Form Actions</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-row-separator.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Row Separator</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-bordered.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Form Bordered</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/form-detail.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Form Detail</span>
                                        </a>
                                    </li>

                                    <!-- form wizard -->
                                    <li class="sidebar-item">
                                        <a href="../main/form-wizard.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Form Wizard</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="../main/form-editor-quill.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Quill Editor</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- ============================= -->
                            <!-- Tables -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Tables</span>
                            </li>
                            <!-- =================== -->
                            <!-- Bootstrap Table -->
                            <!-- =================== -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow warning-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:bedside-table-2-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Tables</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../main/table-basic.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Basic Table</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/table-dark-basic.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Dark Basic Table</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/table-sizing.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Sizing Table</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/table-layout-coloured.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Coloured Table Layout</span>
                                        </a>
                                    </li>
                                    <!-- datatable -->
                                    <li class="sidebar-item">
                                        <a href="../main/table-datatable-basic.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Basic Initialisation</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/table-datatable-api.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">API</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/table-datatable-advanced.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Advanced Initialisation</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ============================= -->
                            <!-- Auth -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Auth</span>
                            </li>
                            <!-- =================== -->
                            <!-- Auth -->
                            <!-- =================== -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow info-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:lock-keyhole-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Auth</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../main/authentication-error.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Error</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-login.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Side Login</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-login2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Boxed Login</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-register.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Side Register</span>
                                        </a>
                                    </li>
                                    <!-- datatable -->
                                    <li class="sidebar-item">
                                        <a href="../main/authentication-register2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Boxed Register</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-forgot-password.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Side Forgot Password</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-forgot-password2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Boxed Forgot Password</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-two-steps.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Side Two Steps</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-two-steps2.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Boxed Two Steps</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="../main/authentication-maintenance.html" class="sidebar-link">
                                            <span class="sidebar-icon"></span>
                                            <span class="hide-menu text-truncate">Maintenance</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ============================= -->
                            <!-- Charts -->
                            <!-- ============================= -->
                            <li class="nav-small-cap">
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">Charts</span>
                            </li>

                            <!-- multi level -->
                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow success-hover-bg" href="javascript:void(0)"
                                    aria-expanded="false">
                                    <iconify-icon icon="solar:layers-line-duotone"
                                        class="fs-6 aside-icon"></iconify-icon>
                                    <span class="hide-menu ps-1">Multi DD</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="../docs/index.html" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Documentation</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="javascript:void(0)" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Page 1</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="javascript:void(0)" class="sidebar-link has-arrow">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Page 2</span>
                                        </a>
                                        <ul aria-expanded="false" class="collapse second-level">
                                            <li class="sidebar-item">
                                                <a href="javascript:void(0)" class="sidebar-link">
                                                    <i class="ti ti-circle"></i>
                                                    <span class="hide-menu">Page 2.1</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="javascript:void(0)" class="sidebar-link">
                                                    <i class="ti ti-circle"></i>
                                                    <span class="hide-menu">Page 2.2</span>
                                                </a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="javascript:void(0)" class="sidebar-link">
                                                    <i class="ti ti-circle"></i>
                                                    <span class="hide-menu">Page 2.3</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="javascript:void(0)" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Page 3</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                </div>
            </aside>

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
                    <input type="radio" class="btn-check" name="direction-l" id="ltr-layout"
                        autocomplete="off" />
                    <label class="btn p-9 btn-outline-primary" for="ltr-layout">
                        <i class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR
                    </label>

                    <input type="radio" class="btn-check" name="direction-l" id="rtl-layout"
                        autocomplete="off" />
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

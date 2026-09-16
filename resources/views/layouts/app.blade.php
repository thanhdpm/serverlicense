<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | Server License</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="/assets/images/favicon.ico" />

        <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <!-- jquery.vectormap css -->
        <link href="/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- Bootstrap Css -->
        <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        
        <script src="/assets/libs/jquery/jquery.min.js"></script>

        @yield('headerAddition')

    </head>

    <style>
        tr, th {
            white-space: nowrap;
        }
    </style>

    <body data-topbar="dark">
        <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <i class="ri-loader-line spin-icon"></i>
                </div>
            </div>
        </div>

        <!-- Begin page -->
        <div id="layout-wrapper">
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="/" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="/assets/images/logo-sm.png" alt="" height="22" />
                                </span>
                                <span class="logo-lg">
                                    <img src="/assets/images/logo-dark.png" alt="" height="20" />
                                </span>
                            </a>

                            <a href="/" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="/assets/images/logo-sm-light.png" alt="" height="22" />
                                </span>
                                <span class="logo-lg">
                                    <img src="/assets/images/logo-light.png" alt="" height="20" />
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <!-- App Search-->
                        <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Tìm kiếm..." />
                                <span class="ri-search-line"></span>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex">
                        <div class="dropdown d-inline-block d-lg-none ms-2">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="mb-3 m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tìm kiếm ..." />
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="/assets/images/flags/vn.jpg" alt="Header Language" height="16" />
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item"> 
                                    <img src="/assets/images/flags/vn.jpg" alt="user-image" class="me-1" height="12" /> 
                                    <span class="align-middle">Tiếng Việt</span> 
                                </a>
                            </div>
                        </div>

                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                                <i class="ri-fullscreen-line"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="/assets/images/users/avatar-2.jpg" alt="Header Avatar" />
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0">{{ Auth::user()->name }}</h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#!" class="small"> Hoạt động</a>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    <!-- item-->
                                    <a href="/change-password" class="text-reset notification-item">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3 mt-1">
                                                <span class="avatar-title bg-soft-primary rounded-circle font-size-16">
                                                    <i class="ri-rotate-lock-line text-primary font-size-16"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 text-truncate">
                                                <h6 class="mb-1">Đổi mật khẩu</h6>
                                                <p class="mb-0 font-size-12">Đổi MK định kỳ để tăng cường bảo mật</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- item-->
                                <div class="pt-2 border-top">
                                    <div class="d-grid">
                                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('auth.logout') }}"> 
                                            <i class="ri-shut-down-line align-middle me-1"></i> Đăng xuất
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm-light.png" alt="" height="22" />
                        </span>
                        <span class="logo-lg">
                            <img src="/assets/images/logo-dark.png" alt="" height="22" />
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm-light.png" alt="" height="22" />
                        </span>
                        <span class="logo-lg">
                            <img src="/assets/images/logo-light.png" alt="" height="22" />
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
                    <i class="fa fa-fw fa-bars"></i>
                </button>

                <div data-simplebar class="sidebar-menu-scroll">
                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">GENERAL</li>

                            <li>
                                <a href="{{ route('dashboard.index') }}" class="waves-effect">
                                    <i class="ri-home-gear-line"></i>
                                    <span>Bảng điều khiển</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('customer.index') }}" class="waves-effect">
                                    <i class="ri-team-line"></i>
                                    <span class="badge rounded-pill bg-danger float-end">
                                        {{ number_format(\App\Models\Customer::count()) }}
                                    </span>
                                    <span>Khách hàng</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('product.index') }}" class="waves-effect">
                                    <i class="ri-apps-line"></i>
                                    <span>Sản phẩm</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('license.index') }}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i>
                                    <span>Giấy phép</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">@yield('title')</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        @yield('content')
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                © Server License.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">Powered by <i class="mdi mdi-heart text-danger"></i> <a href="https://jzontech.asia" target="_blank">Jzon Tech</a></div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="/assets/libs/node-waves/waves.min.js"></script>

        @yield('footerAddition')

        <!-- apexcharts -->
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

        <!-- jquery.vectormap map -->
        <script src="/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js"></script>

        <script src="/assets/js/pages/dashboard.init.js"></script>

        <script src="/assets/js/app.js"></script>
    </body>

</html>

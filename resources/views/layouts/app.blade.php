<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | Server License</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="shortcut icon" href="/assets/images/favicon.ico" />

        <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

        <style>
            tr, th {
                white-space: nowrap;
            }
        </style>

        @stack('styles')
    </head>

    <body data-topbar="dark">
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <i class="ri-loader-line spin-icon"></i>
                </div>
            </div>
        </div>

        <div id="layout-wrapper">
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="/assets/images/logo-sm.png" alt="" height="22" />
                                </span>
                                <span class="logo-lg">
                                    <img src="/assets/images/logo-dark.png" alt="" height="20" />
                                </span>
                            </a>

                            <a href="{{ route('dashboard') }}" class="logo logo-light">
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
                    </div>

                    <div class="d-flex">
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
                                    <h6 class="m-0">{{ auth()->user()?->name }}</h6>
                                </div>
                                <a href="{{ route('password.edit') }}" class="text-reset notification-item">
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
                                <div class="pt-2 border-top">
                                    <form method="POST" action="{{ route('logout') }}" class="d-grid">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link font-size-14 text-center">
                                            <i class="ri-shut-down-line align-middle me-1"></i> Đăng xuất
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="vertical-menu">
                <div class="navbar-brand-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm-light.png" alt="" height="22" />
                        </span>
                        <span class="logo-lg">
                            <img src="/assets/images/logo-dark.png" alt="" height="22" />
                        </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo logo-light">
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
                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">GENERAL</li>

                            <li>
                                <a href="{{ route('dashboard') }}" class="waves-effect">
                                    <i class="ri-home-gear-line"></i>
                                    <span>Bảng điều khiển</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('customers.index') }}" class="waves-effect">
                                    <i class="ri-team-line"></i>
                                    <span class="badge rounded-pill bg-danger float-end">
                                        {{ number_format($customerCount) }}
                                    </span>
                                    <span>Khách hàng</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('products.index') }}" class="waves-effect">
                                    <i class="ri-apps-line"></i>
                                    <span>Sản phẩm</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('licenses.index') }}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i>
                                    <span>Giấy phép</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0">@yield('title')</h4>
                                </div>
                            </div>
                        </div>

                        @yield('content')
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                {{ now()->year }} © Server License.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">Powered by <i class="mdi mdi-heart text-danger"></i> <a href="https://jzontech.asia" target="_blank" rel="noopener">Jzon Tech</a></div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="/assets/libs/jquery/jquery.min.js"></script>
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="/assets/libs/node-waves/waves.min.js"></script>
        <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <script>
            // Ask before submitting any form marked with data-confirm (deletes).
            document.addEventListener('submit', (event) => {
                const message = event.target.dataset.confirm;

                if (message && ! window.confirm(message)) {
                    event.preventDefault();
                }
            });
        </script>

        @stack('scripts')

        <script src="/assets/js/app.js"></script>
    </body>
</html>

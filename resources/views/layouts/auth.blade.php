<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8" />
        <title>Đăng nhập quản trị | Server License</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="/assets/images/favicon.ico" />
        <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body class="authentication-bg d-flex align-items-center min-vh-100 py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="d-block auth-logo">
                            <img src="/assets/images/logo-dark.png" alt="" style="width: 14%" class="logo logo-dark mx-auto" />
                            <img src="/assets/images/logo-light.png" alt="" style="width: 14%" class="logo logo-light mx-auto" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    @yield('content')
                </div>
            </div>
        </div>

        <script src="/assets/libs/jquery/jquery.min.js"></script>
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="/assets/libs/simplebar/simplebar.min.js"></script>
        <script src="/assets/libs/node-waves/waves.min.js"></script>
        <script src="/assets/js/app.js"></script>
    </body>
</html>

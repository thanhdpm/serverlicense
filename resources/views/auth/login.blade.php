@extends('layouts.auth')

@section('content')
<div class="p-4">
    <div class="card overflow-hidden mt-2">
        <div class="auth-logo text-center bg-primary position-relative">
            <div class="img-overlay"></div>
            <div class="position-relative pt-4 py-5 mb-1">
                <h5 class="text-white">Đăng nhập quản trị</h5>
                <p class="text-white-50 mb-0">Đăng nhập để truy cập bảng điều khiển</p>
            </div>
        </div>
        <div class="card-body position-relative">
            <div class="p-4 mt-n5 card rounded">

                @include('common.alert')

                <form class="form-horizontal" action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Địa chỉ email</label>
                        <input type="email" class="form-control" id="email" placeholder="example@jzontech.asia" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus />
                    </div>

                    <div class="mb-3">
                        <label for="password">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" placeholder="**********" name="password" autocomplete="current-password" required />
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" @checked(old('remember')) />
                        <label class="form-check-label" for="remember">Ghi nhớ phiên</label>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Đăng nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <p>
            © {{ now()->year }} Server License. Powered by <i class="mdi mdi-heart text-danger"></i> <a href="https://jzontech.asia" target="_blank" rel="noopener">Jzon Tech</a>
        </p>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('common.alert')

                    <div class="form-group mb-3">
                        <label for="current_password">Mật khẩu cũ</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="************" autocomplete="current-password" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Mật khẩu mới</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="************" autocomplete="new-password" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="************" autocomplete="new-password" required>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-success">
                            Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

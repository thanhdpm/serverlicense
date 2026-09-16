@extends('layouts.app')

@section('title', 'Thêm khách hàng')

@section('headerAddition')
<script src="/assets/libs/gijgo/gijgo.min.js" type="text/javascript"></script>
<link href="/assets/libs/gijgo/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6" style="display: block; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customer.do_add') }}" method="POST">
                    @csrf

                    @include('common.alert')

                    <div class="form-group mb-3">
                        <label for="">Họ và tên (<b class="text-danger">*</b>):</label>
                        <input type="text" name="fullname" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('fullname') }}">
                    </div>

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="">Giới tính (<b class="text-danger">*</b>):</label>
                                <select name="gender" class="form-control">
                                    <option value="male" @selected(old('gender') == 'male')>Nam</option>
                                    <option value="female" @selected(old('gender') == 'female')>Nữ</option>
                                    <option value="unknown" @selected(old('gender') == 'unknown')>Không xác định</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="">Ngày sinh / Sinh nhật:</label>
                                <input id="datepicker" placeholder="d/m/Y" value="{{ old('dob') }}" name="dob" />
                            </div>

                            <script>
                                $('#datepicker').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    icons: {
                                        rightIcon: '<i class="fas fa-calendar-alt"></i>'
                                    }
                                });
                            </script>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="">Số điện thoại:</label>
                                <input type="text" class="form-control" name="phone" placeholder="0123456789" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="">Địa chỉ email:</label>
                                <input type="text" class="form-control" name="email" placeholder="example@jzontech.asia" value="{{ old('email') }}">
                            </div>
                        </div>

                    </div>

                    <div class="form-group mb-3">
                        <label for="">Ghi chú:</label>
                        <textarea name="note" class="form-control" cols="30" rows="5" placeholder="Khách sộp, ưu tiên khách này, chú ý hơn, ... (Không bắt buộc)">{{ old('note') }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-plus me-2"></i> Thêm khách hàng
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Hồ sơ khách hàng - '.$customer->fullname.' - ID '.$customer->id)

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label>Họ và tên:</label>
                    <input type="text" class="form-control" placeholder="Không có" value="{{ $customer->fullname }}" disabled>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label>Giới tính:</label>
                            <input type="text" class="form-control" placeholder="Không có" value="{{ App\Enums\Gender::tryFrom((string) $customer->gender)?->label() }}" disabled>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label>Ngày sinh / Sinh nhật:</label>
                            <input type="text" class="form-control" placeholder="Không có" value="{{ $customer->dob?->format('d/m/Y') }}" disabled>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label>Số điện thoại:</label>
                            <input type="text" class="form-control" placeholder="Không có" value="{{ $customer->phone }}" disabled>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label>Địa chỉ email:</label>
                            <input type="text" class="form-control" placeholder="Không có" value="{{ $customer->email }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Ghi chú:</label>
                    <textarea class="form-control" rows="5" placeholder="Không có" disabled>{{ $customer->note }}</textarea>
                </div>

                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-success">
                    <i class="fas fa-pen me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

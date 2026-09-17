@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ khách hàng')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('customers.partials.form', ['customer' => $customer])

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-content-save me-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

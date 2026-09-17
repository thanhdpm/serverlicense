@extends('layouts.app')

@section('title', 'Thêm khách hàng')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf

                    @include('customers.partials.form')

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

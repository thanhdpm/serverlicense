@extends('layouts.app')

@section('title', 'Chỉnh sửa giấy phép')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>

    <div class="col-lg-4 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('licenses.update', $license) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label>Mã giấy phép</label>
                        <input type="text" class="form-control" value="{{ $license->key }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Thời hạn</label>
                        <x-duration-input :seconds="$license->durationInSeconds()" />
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-success" type="submit">
                            Lưu thay đổi
                        </button>
                        <a href="{{ route('licenses.index') }}" class="btn btn-light">
                            Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

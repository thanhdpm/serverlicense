@extends('layouts.app')

@section('title', 'Thêm giấy phép')

@push('styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#product, #customer').select2();

            $('#generate-key').on('click', function () {
                const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                const random = new Uint32Array(25);
                crypto.getRandomValues(random);

                const characters = Array.from(random, (value) => alphabet[value % alphabet.length]);
                const segments = [];

                for (let i = 0; i < characters.length; i += 5) {
                    segments.push(characters.slice(i, i + 5).join(''));
                }

                $('#key').val(segments.join('-'));
            });
        });
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('licenses.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="product">Sản phẩm</label>
                                <select class="form-control" id="product" name="product" required>
                                    <option value="">-- CHỌN SẢN PHẨM --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected((string) old('product') === (string) $product->id)>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="customer">
                                    Khách hàng
                                    <a href="{{ route('customers.create') }}" title="Thêm khách hàng">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                </label>
                                <select class="form-control" id="customer" name="customer" required>
                                    <option value="">-- CHỌN KHÁCH HÀNG --</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected((string) old('customer') === (string) $customer->id)>{{ $customer->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="form-group mb-3">
                                <label for="key">Mã kích hoạt</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="key" name="key" maxlength="191" placeholder="Nhập mã kích hoạt tùy chọn" value="{{ old('key') }}" required>
                                    <button type="button" class="btn btn-outline-danger" id="generate-key">
                                        <i class="fas fa-key"></i> Tạo mã tự động
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label>Thời hạn</label>
                                <x-duration-input />
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button class="btn btn-success" type="submit">
                        <i class="mdi mdi-plus me-2"></i> Thêm giấy phép
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

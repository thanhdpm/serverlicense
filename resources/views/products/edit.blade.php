@extends('layouts.app')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name">Tên sản phẩm</label>
                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <button class="btn btn-success" type="submit">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

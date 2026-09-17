@extends('layouts.app')

@section('title', 'Chỉnh sửa phiên bản')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('versions.update', $version) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="version">Phiên bản</label>
                        <input type="text" id="version" class="form-control" placeholder="1.0.1" name="version" value="{{ old('version', $version->version) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Mô tả phiên bản (nếu có)</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Cập nhật tính năng mới..." rows="3">{{ old('description', $version->description) }}</textarea>
                    </div>

                    @include('versions.partials.file-input', ['hint' => 'Không tải file lên nếu muốn giữ nguyên file hiện tại'])

                    <div class="form-group mb-3 d-flex gap-2">
                        <button class="btn btn-success" type="submit">
                            Lưu thay đổi
                        </button>
                        <a href="{{ route('products.versions.index', $version->product_id) }}" class="btn btn-light">
                            Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

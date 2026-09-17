@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">Tên sản phẩm</label>
                        <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Mô tả sản phẩm (nếu có)</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <hr>

                    <div class="form-group mb-3">
                        <label for="version">Phiên bản</label>
                        <input type="text" id="version" class="form-control" name="version" placeholder="1.0.0" value="{{ old('version') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="version_description">Mô tả phiên bản (nếu có)</label>
                        <textarea id="version_description" name="version_description" placeholder="Fix lỗi lặt vặt..." class="form-control" rows="3">{{ old('version_description') }}</textarea>
                    </div>

                    @include('versions.partials.file-input')

                    <div class="form-group mb-3">
                        <button class="btn btn-success" type="submit">
                            <i class="mdi mdi-plus me-2"></i> Thêm sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <x-table-tools />

                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th width="1%">ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Mô tả</th>
                                <th>Phiên bản HT</th>
                                <th>Thời gian thêm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        <b>{{ $product->id }}</b>
                                    </td>
                                    <td>
                                        {{ $product->name }}
                                    </td>
                                    <td>
                                        <textarea class="form-control" rows="2" readonly>{{ $product->description }}</textarea>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-success fw-bold">
                                            {{ $product->latestVersion?->version ?? 'Không có' }}
                                        </span>
                                        @if ($product->latestVersion?->file_url)
                                            <br>
                                            <span class="badge badge-soft-warning">
                                                <a href="{{ $product->latestVersion->file_url }}">
                                                    <i class="fas fa-cloud-download-alt"></i>
                                                    TẢI XUỐNG FILE
                                                </a>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->created_at?->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.versions.index', $product) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Phiên bản">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <x-delete-button :action="route('products.destroy', $product)" title="Xóa sản phẩm" confirm="Xóa sản phẩm này cùng toàn bộ phiên bản và file cập nhật?" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có sản phẩm nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

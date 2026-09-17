@extends('layouts.app')

@section('title', 'Phiên bản - '.$product->name)

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
                <form action="{{ route('products.versions.store', $product) }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="version">Phiên bản</label>
                        <input type="text" id="version" class="form-control" placeholder="1.0.1" name="version" value="{{ old('version') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Mô tả phiên bản (nếu có)</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Cập nhật tính năng mới..." rows="3">{{ old('description') }}</textarea>
                    </div>

                    @include('versions.partials.file-input')

                    <div class="form-group mb-3">
                        <button class="btn btn-success" type="submit">
                            <i class="mdi mdi-plus me-2"></i> Thêm phiên bản mới
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
                                <th>Phiên bản</th>
                                <th>Mô tả</th>
                                <th>File cập nhật</th>
                                <th>Thời gian thêm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($versions as $version)
                                <tr>
                                    <td>
                                        <b>{{ $version->id }}</b>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $version->version }}
                                        </span>
                                    </td>
                                    <td>
                                        <textarea class="form-control" rows="2" readonly>{{ $version->description }}</textarea>
                                    </td>
                                    <td>
                                        @if ($version->file_url)
                                            <span class="badge badge-soft-warning">
                                                <a href="{{ $version->file_url }}">
                                                    <i class="fas fa-cloud-download-alt"></i>
                                                    TẢI XUỐNG FILE
                                                </a>
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $version->created_at?->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('versions.edit', $version) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <x-delete-button :action="route('versions.destroy', $version)" title="Xóa phiên bản" confirm="Xóa phiên bản này cùng file cập nhật?" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có phiên bản nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $versions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

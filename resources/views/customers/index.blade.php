@extends('layouts.app')

@section('title', 'Khách hàng')

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <x-table-tools>
                    <a href="{{ route('customers.create') }}" class="btn btn-success mb-2 w-100">
                        <i class="mdi mdi-plus me-2"></i> Thêm khách hàng
                    </a>
                    <a href="{{ route('customers.export') }}" class="btn btn-outline-primary mb-2 w-100">
                        <i class="mdi mdi-file-excel me-2"></i> Xuất Excel
                    </a>
                </x-table-tools>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th width="1%">Họ và tên</th>
                                <th>Giới tính</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ email</th>
                                <th>Thời gian thêm</th>
                                <th width="1%">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($customers as $customer)
                                @php($gender = App\Enums\Gender::tryFrom((string) $customer->gender) ?? App\Enums\Gender::Unknown)
                                <tr>
                                    <td>
                                        <b>{{ $customer->id }}</b>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $customer->fullname }}" readonly>
                                    </td>
                                    <td>
                                        <span class="badge {{ $gender->badgeClass() }}">{{ $gender->label() }}</span>
                                    </td>
                                    <td>
                                        @if ($customer->phone)
                                            <span class="badge badge-soft-warning">{{ $customer->phone }}</span>
                                        @else
                                            <span class="badge badge-soft-danger">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($customer->email)
                                            <input type="text" value="{{ $customer->email }}" style="width: 100%" readonly>
                                        @else
                                            <span class="badge badge-soft-danger">Không có</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $customer->created_at?->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem hồ sơ">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <x-delete-button :action="route('customers.destroy', $customer)" title="Xóa hồ sơ" confirm="Xóa hồ sơ khách hàng này?" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có khách hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

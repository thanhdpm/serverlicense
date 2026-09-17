@extends('layouts.app')

@section('title', 'Giấy phép')

@push('scripts')
    <script>
        // Fingerprint data comes from untrusted API clients: build the dialog
        // with DOM text nodes so it can never be interpreted as HTML.
        document.addEventListener('click', (event) => {
            const button = event.target.closest('[data-fingerprint]');

            if (! button) {
                return;
            }

            const bold = (text) => Object.assign(document.createElement('b'), { textContent: text });
            const content = document.createElement('div');

            content.append(
                'Địa chỉ IP: ', bold(button.dataset.ip),
                document.createElement('br'),
                'Useragent: ', bold(button.dataset.useragent),
            );

            Swal.fire({ title: 'Fingerprint Data', html: content });
        });
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        @include('common.alert')
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <x-table-tools>
                    <a href="{{ route('licenses.create') }}" class="btn btn-success mb-2 w-100">
                        <i class="mdi mdi-plus me-2"></i> Thêm giấy phép
                    </a>
                </x-table-tools>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th width="1%">Sản phẩm</th>
                                <th width="1%">Khách hàng</th>
                                <th width="1%">Mã giấy phép</th>
                                <th>Thời hạn</th>
                                <th>Kích hoạt</th>
                                <th>Fingerprint</th>
                                <th>Thời gian thêm</th>
                                <th width="1%">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($licenses as $license)
                                @php
                                    $customer = $license->customer ?? [];
                                    // Popover content is rendered as HTML by Bootstrap, so every
                                    // value is escaped once here and once more by {{ }}.
                                    $customerPopover = 'Họ và tên: <b>'.e($customer['fullname'] ?? '').'</b>'
                                        .'<br>Số điện thoại: <b>'.e($customer['phone'] ?? 'Không có').'</b>'
                                        .'<br>Email: <b>'.e($customer['email'] ?? 'Không có').'</b>'
                                        .($license->customer_id ? '<br><br><b><a href="'.e(route('customers.show', $license->customer_id)).'">XEM HỒ SƠ KH</a></b>' : '');
                                @endphp
                                <tr>
                                    <td>
                                        <b>{{ $license->id }}</b>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">
                                            {{ $license->product['name'] ?? 'Không có' }}
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $customer['fullname'] ?? '' }}" readonly data-bs-toggle="popover" data-bs-placement="top" data-bs-html="true" data-bs-content="{{ $customerPopover }}">
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $license->key }}" readonly>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $license->durationForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($license->activated_at)
                                            <span class="badge bg-success">Đã kích hoạt</span> <br>
                                            <span class="badge bg-warning">{{ $license->activated_at->format('d/m/Y H:i:s') }}</span>
                                        @else
                                            <span class="badge bg-danger">Chưa kích hoạt</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($license->fingerprint)
                                            <button type="button" class="btn btn-primary btn-sm" data-fingerprint data-ip="{{ $license->fingerprint['ip'] ?? '' }}" data-useragent="{{ $license->fingerprint['useragent'] ?? '' }}">
                                                <i class="fas fa-fingerprint"></i> XEM NGAY
                                            </button>
                                        @else
                                            <span class="badge bg-danger">Không có dữ liệu</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $license->issuedAt()?->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('licenses.edit', $license) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <x-delete-button :action="route('licenses.destroy', $license)" title="Xóa giấy phép" confirm="Xóa giấy phép này? Phần mềm đang dùng mã này sẽ không còn hợp lệ." />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Không có giấy phép nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $licenses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

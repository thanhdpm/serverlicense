@php
    use App\Enums\Gender;
    use App\Http\Requests\CustomerRequest;

    /** @var \App\Models\Customer|null $customer */
    $customer ??= null;
@endphp

@push('styles')
    <link href="/assets/libs/gijgo/gijgo.min.css" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="/assets/libs/gijgo/gijgo.min.js"></script>
    <script>
        $('#dob').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'dd/mm/yyyy',
            icons: {
                rightIcon: '<i class="fas fa-calendar-alt"></i>'
            }
        });
    </script>
@endpush

@include('common.alert')

<div class="form-group mb-3">
    <label for="fullname">Họ và tên (<b class="text-danger">*</b>):</label>
    <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('fullname', $customer?->fullname) }}" required>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group mb-3">
            <label for="gender">Giới tính (<b class="text-danger">*</b>):</label>
            <select id="gender" name="gender" class="form-select">
                @foreach (Gender::cases() as $gender)
                    <option value="{{ $gender->value }}" @selected(old('gender', $customer?->gender) === $gender->value)>{{ $gender->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group mb-3">
            <label for="dob">Ngày sinh / Sinh nhật:</label>
            <input id="dob" name="dob" placeholder="dd/mm/yyyy" value="{{ old('dob', $customer?->dob?->format(CustomerRequest::DATE_FORMAT)) }}" />
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group mb-3">
            <label for="phone">Số điện thoại:</label>
            <input type="tel" id="phone" class="form-control" name="phone" placeholder="0123456789" value="{{ old('phone', $customer?->phone) }}">
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group mb-3">
            <label for="email">Địa chỉ email:</label>
            <input type="email" id="email" class="form-control" name="email" placeholder="example@jzontech.asia" value="{{ old('email', $customer?->email) }}">
        </div>
    </div>
</div>

<div class="form-group mb-3">
    <label for="note">Ghi chú:</label>
    <textarea id="note" name="note" class="form-control" rows="5" placeholder="Khách sộp, ưu tiên khách này, chú ý hơn, ... (Không bắt buộc)">{{ old('note', $customer?->note) }}</textarea>
</div>

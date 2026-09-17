@props(['seconds' => null])

@php
    use App\Enums\DurationUnit;

    $unit = DurationUnit::tryFrom((string) old('duration_period'))
        ?? ($seconds !== null ? DurationUnit::bestFit($seconds) : DurationUnit::Days);
    $amount = old('duration_value', $seconds !== null ? $unit->fromSeconds($seconds) : null);
@endphp

<div class="input-group mb-3">
    <input type="number" min="0" step="any" class="form-control" name="duration_value" placeholder="Nhập giá trị thời hạn" value="{{ $amount }}">
    <select class="form-select" name="duration_period">
        @foreach (DurationUnit::cases() as $case)
            <option value="{{ $case->value }}" @selected($case === $unit)>{{ $case->label() }}</option>
        @endforeach
    </select>
</div>

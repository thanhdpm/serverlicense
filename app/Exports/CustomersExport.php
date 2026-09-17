<?php

namespace App\Exports;

use App\Enums\Gender;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * @implements WithMapping<Customer>
 */
class CustomersExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * Streamed from the database in chunks instead of loading every
     * customer into memory at once.
     *
     * @return Builder<Customer>
     */
    public function query(): Builder
    {
        return Customer::query()->latest('id');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return ['ID', 'Họ và tên', 'Giới tính', 'Ngày sinh', 'Số điện thoại', 'Email', 'Ghi chú', 'Thời gian thêm'];
    }

    /**
     * @param  Customer  $customer
     * @return list<mixed>
     */
    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->fullname,
            Gender::tryFrom((string) $customer->gender)?->label(),
            $customer->dob?->format('d/m/Y'),
            $customer->phone,
            $customer->email,
            $customer->note,
            $customer->created_at?->format('d/m/Y H:i:s'),
        ];
    }
}

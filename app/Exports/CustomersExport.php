<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Enumerable
     */
    public function collection(): Enumerable
    {
        return Customer::orderBy('id', 'desc')->get();
    }
}

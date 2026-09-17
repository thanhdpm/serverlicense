<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->search($request->string('s')->toString())
            ->latest('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $customer = Customer::query()->create($request->customerAttributes());

        return to_route('customers.index')
            ->with('success', "Thêm khách hàng \"{$customer->fullname}\" thành công!");
    }

    public function show(Customer $customer): View
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->customerAttributes());

        return to_route('customers.index')
            ->with('success', "Lưu thông tin khách hàng \"{$customer->fullname}\" thành công!");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return to_route('customers.index')->with('success', 'Xóa hồ sơ khách hàng thành công!');
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new CustomersExport, 'customers-'.now()->format('Ymd-His').'.xlsx');
    }
}

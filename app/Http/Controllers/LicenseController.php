<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLicenseRequest;
use App\Http\Requests\UpdateLicenseRequest;
use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LicenseController extends Controller
{
    public function index(Request $request): View
    {
        $licenses = License::query()
            ->search($request->string('s')->toString())
            ->latest('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('licenses.index', compact('licenses'));
    }

    public function create(): View
    {
        return view('licenses.create', [
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'customers' => Customer::query()->latest('id')->get(['id', 'fullname']),
        ]);
    }

    public function store(StoreLicenseRequest $request): RedirectResponse
    {
        $customer = Customer::query()->findOrFail($request->integer('customer'));
        $product = Product::query()->findOrFail($request->integer('product'));

        License::query()->create([
            'customer_id' => $customer->id,
            'customer' => $customer->toArray(),
            'product_id' => $product->id,
            'product' => $product->toArray(),
            'key' => $request->validated('key'),
            'duration' => $request->durationInSeconds(),
        ]);

        return to_route('licenses.index')->with('success', 'Thêm giấy phép thành công!');
    }

    public function edit(License $license): View
    {
        return view('licenses.edit', compact('license'));
    }

    public function update(UpdateLicenseRequest $request, License $license): RedirectResponse
    {
        $license->update(['duration' => $request->durationInSeconds()]);

        return to_route('licenses.edit', $license)->with('success', 'Đã lưu thay đổi thông tin giấy phép!');
    }

    public function destroy(License $license): RedirectResponse
    {
        $license->delete();

        return to_route('licenses.index')->with('success', 'Xóa giấy phép thành công!');
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\License;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    protected array $rules = [
        'fullname' => 'required',
        'gender' => 'required|in:male,female,unknown',
        'dob' => 'nullable|date',
        'phone' => 'nullable|numeric',
        'email' => 'nullable|email',
        'note' => 'nullable|string',
    ];

    public function index(Request $request)
    {
        $params = $request->all();

        $customers = Customer::query()->orderByDesc('id');

        search_by_cols($customers, $request->input('s'), [
            'fullname', 'gender', 'dob', 'phone', 'email', 'note', 'id',
        ]);

        $customers = paginate_with_params($customers, $params);

        return view('customers.index', compact('customers'));
    }

    public function viewAdd()
    {
        return view('customers.add');
    }

    public function doAdd(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->route('customer.add')->withInput()->withErrors($validator);
        }

        $data = $validator->validated();

        Customer::create(array_merge($validator->validated(), [
            'dob' => !empty(Arr::get($data, 'dob')) ? Carbon::parse(Arr::get($data, 'dob')) : null
        ]));

        return redirect()->route('customer.index')->with('success', 'Thêm khách hàng "' . $request->fullname . '" thành công!');
    }

    public function customerProfile($id)
    {
        $customer = Customer::firstWhere('id', $id);

        return $customer
            ? view('customers.profile', compact('customer'))
            : redirect()->route('customer.index')->withErrors([
                "Không tìm thấy hồ sơ khách hàng :("
            ]);
    }

    public function delete($id)
    {
        $customer = Customer::firstWhere('id', $id);

        if (!$customer) {
            return redirect()->route('customer.index')->withErrors([
                "Không tìm thấy hồ sơ khách hàng :("
            ]);
        } else {
            $customer->delete();
            return redirect()->route('customer.index')->with('success', 'Xóa hồ sơ khách hàng thành công!');
        }
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function save(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $validator->validated();

        $customer->update(array_merge($validator->validated(), [
            'dob' => !empty(Arr::get($data, 'dob')) ? Carbon::parse(Arr::get($data, 'dob')) : null
        ]));

        return redirect()->route('customer.index')->with('success', 'Lưu thông tin khách hàng "' . $request->fullname . '" thành công!');
    }

}

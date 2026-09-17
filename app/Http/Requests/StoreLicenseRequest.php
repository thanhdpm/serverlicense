<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasDurationInput;
use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLicenseRequest extends FormRequest
{
    use HasDurationInput;

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'product' => ['required', 'integer', Rule::exists(Product::class, 'id')],
            'customer' => ['required', 'integer', Rule::exists(Customer::class, 'id')],
            'key' => ['required', 'string', 'max:191', Rule::unique(License::class, 'key')],
            ...$this->durationRules(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'key.unique' => 'Mã kích hoạt đã tồn tại.',
        ];
    }
}

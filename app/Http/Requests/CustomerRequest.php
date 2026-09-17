<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CustomerRequest extends FormRequest
{
    public const DATE_FORMAT = 'd/m/Y';

    /**
     * @return array<string, list<string|Enum>>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'dob' => ['nullable', 'date_format:'.self::DATE_FORMAT],
            'phone' => ['nullable', 'numeric'],
            'email' => ['nullable', 'email', 'max:255'],
            'note' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function customerAttributes(): array
    {
        return [
            ...$this->safe()->except('dob'),
            'dob' => $this->date('dob', self::DATE_FORMAT),
        ];
    }
}

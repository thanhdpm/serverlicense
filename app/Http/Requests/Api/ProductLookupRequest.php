<?php

namespace App\Http\Requests\Api;

class ProductLookupRequest extends ApiRequest
{
    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric'],
        ];
    }
}

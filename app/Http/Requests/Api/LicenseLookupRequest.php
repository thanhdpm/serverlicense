<?php

namespace App\Http\Requests\Api;

class LicenseLookupRequest extends ApiRequest
{
    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
        ];
    }
}

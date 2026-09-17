<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Public API requests keep the historical error contract: HTTP 200 with
 * {"ok": false, "message": "VALIDATION_FAILED", "errors": {...}}.
 */
abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'message' => 'VALIDATION_FAILED',
            'errors' => $validator->errors(),
        ]));
    }
}

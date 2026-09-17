<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesVersionFile;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    use ValidatesVersionFile;

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'version' => ['required', 'string', 'max:255'],
            'version_description' => ['nullable', 'string'],
            'file' => $this->versionFileRules(),
        ];
    }
}

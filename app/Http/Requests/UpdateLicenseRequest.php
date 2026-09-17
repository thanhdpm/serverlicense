<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\HasDurationInput;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenseRequest extends FormRequest
{
    use HasDurationInput;

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return $this->durationRules();
    }
}

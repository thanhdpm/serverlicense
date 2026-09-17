<?php

namespace App\Http\Requests\Concerns;

use App\Enums\DurationUnit;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

trait HasDurationInput
{
    /**
     * @return array<string, list<string|Enum>>
     */
    protected function durationRules(): array
    {
        return [
            'duration_value' => ['required', 'numeric', 'gt:0'],
            'duration_period' => ['required', Rule::enum(DurationUnit::class)],
        ];
    }

    public function durationInSeconds(): int
    {
        /** @var DurationUnit $unit */
        $unit = $this->enum('duration_period', DurationUnit::class);

        return $unit->toSeconds($this->float('duration_value'));
    }
}

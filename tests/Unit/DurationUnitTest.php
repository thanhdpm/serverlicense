<?php

namespace Tests\Unit;

use App\Enums\DurationUnit;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DurationUnitTest extends TestCase
{
    /**
     * @return array<string, array{int, DurationUnit, string}>
     */
    public static function durations(): array
    {
        return [
            'seconds' => [30, DurationUnit::Seconds, '30 giây'],
            'just under a minute' => [59, DurationUnit::Seconds, '59 giây'],
            'minutes' => [120, DurationUnit::Minutes, '2 phút'],
            'rounds half up' => [90, DurationUnit::Minutes, '2 phút'],
            'hours' => [3_600, DurationUnit::Hours, '1 giờ'],
            'days' => [86_400, DurationUnit::Days, '1 ngày'],
            'weeks' => [604_800, DurationUnit::Weeks, '1 tuần'],
            'months' => [2_630_000, DurationUnit::Months, '1 tháng'],
            'years' => [31_557_600, DurationUnit::Years, '1 năm'],
            'zero' => [0, DurationUnit::Seconds, '0 giây'],
        ];
    }

    #[DataProvider('durations')]
    public function test_best_fit_and_humanized_output(int $seconds, DurationUnit $unit, string $human): void
    {
        $this->assertSame($unit, DurationUnit::bestFit($seconds));
        $this->assertSame($human, DurationUnit::humanize($seconds));
    }

    public function test_converts_amounts_to_seconds_and_back(): void
    {
        $this->assertSame(2_592_000, DurationUnit::Days->toSeconds(30));
        $this->assertSame(129_600, DurationUnit::Days->toSeconds(1.5));
        $this->assertSame(30, DurationUnit::Days->fromSeconds(2_592_000));

        foreach (DurationUnit::cases() as $unit) {
            $this->assertSame(7, $unit->fromSeconds($unit->toSeconds(7)));
        }
    }
}

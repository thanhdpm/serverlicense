<?php

namespace App\Enums;

enum DurationUnit: string
{
    case Seconds = 'seconds';
    case Minutes = 'minutes';
    case Hours = 'hours';
    case Days = 'days';
    case Weeks = 'weeks';
    case Months = 'months';
    case Years = 'years';

    /**
     * The largest unit the duration fills at least once.
     */
    public static function bestFit(int $seconds): self
    {
        foreach (array_reverse(self::cases()) as $unit) {
            if ($seconds >= $unit->seconds()) {
                return $unit;
            }
        }

        return self::Seconds;
    }

    /**
     * A human readable, rounded duration such as "3 ngày".
     */
    public static function humanize(int $seconds): string
    {
        $unit = self::bestFit($seconds);

        return $unit->fromSeconds($seconds).' '.mb_strtolower($unit->label());
    }

    public function seconds(): int
    {
        return match ($this) {
            self::Seconds => 1,
            self::Minutes => 60,
            self::Hours => 3_600,
            self::Days => 86_400,
            self::Weeks => 604_800,
            self::Months => 2_630_000,
            self::Years => 31_557_600,
        };
    }

    public function toSeconds(int|float $amount): int
    {
        return (int) round($amount * $this->seconds());
    }

    public function fromSeconds(int $seconds): int
    {
        return (int) round($seconds / $this->seconds());
    }

    public function label(): string
    {
        return match ($this) {
            self::Seconds => 'Giây',
            self::Minutes => 'Phút',
            self::Hours => 'Giờ',
            self::Days => 'Ngày',
            self::Weeks => 'Tuần',
            self::Months => 'Tháng',
            self::Years => 'Năm',
        };
    }
}

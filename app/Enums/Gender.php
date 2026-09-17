<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Nam',
            self::Female => 'Nữ',
            self::Unknown => 'Không xác định',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Male => 'badge-soft-info',
            self::Female => 'badge-soft-danger',
            self::Unknown => 'badge-soft-warning',
        };
    }
}

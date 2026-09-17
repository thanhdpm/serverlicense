<?php

namespace App\Models;

use App\Models\Concerns\HasKeywordSearch;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * @property Carbon|null $dob
 */
#[Fillable(['fullname', 'gender', 'dob', 'phone', 'email', 'note'])]
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory, HasKeywordSearch;

    private const COUNT_CACHE_KEY = 'customers.count';

    protected static function booted(): void
    {
        static::created(fn () => Cache::forget(self::COUNT_CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::COUNT_CACHE_KEY));
    }

    public static function cachedCount(): int
    {
        return Cache::remember(self::COUNT_CACHE_KEY, now()->addHour(), fn () => static::query()->count());
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Serialized as Y-m-d so license snapshots keep their historical format.
            'dob' => 'date:Y-m-d',
        ];
    }

    /**
     * @return HasMany<License, $this>
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    protected function searchableColumns(): array
    {
        return ['fullname', 'gender', 'dob', 'phone', 'email', 'note', 'id'];
    }
}

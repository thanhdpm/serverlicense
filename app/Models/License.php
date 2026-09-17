<?php

namespace App\Models;

use App\Enums\DurationUnit;
use App\Models\Concerns\HasKeywordSearch;
use Database\Factories\LicenseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

/**
 * The "customer" and "product" attributes are JSON snapshots taken when the
 * license was issued. The public API returns them as-is, so they are never
 * rewritten; use customerRecord() for the live customer row.
 *
 * @property array<string, mixed>|null $customer
 * @property array<string, mixed>|null $product
 * @property array{ip: ?string, useragent: ?string}|null $fingerprint
 * @property Carbon|null $activated_at
 */
#[Fillable(['customer_id', 'customer', 'product', 'product_id', 'key', 'duration', 'fingerprint', 'activated_at'])]
#[Hidden(['customer_id'])]
#[WithoutTimestamps]
class License extends Model
{
    /** @use HasFactory<LicenseFactory> */
    use HasFactory, HasKeywordSearch;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'customer' => 'array',
            'product' => 'array',
            'fingerprint' => 'array',
            'activated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function customerRecord(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function durationInSeconds(): int
    {
        return (int) $this->duration;
    }

    public function durationUnit(): DurationUnit
    {
        return DurationUnit::bestFit($this->durationInSeconds());
    }

    public function durationForHumans(): string
    {
        return DurationUnit::humanize($this->durationInSeconds());
    }

    /**
     * created_at is deliberately not cast: the API returns its raw value.
     */
    public function issuedAt(): ?Carbon
    {
        return $this->created_at ? Date::parse($this->created_at) : null;
    }

    public function isExpired(): bool
    {
        return $this->activated_at !== null
            && now()->getTimestamp() - $this->activated_at->getTimestamp() >= $this->durationInSeconds();
    }

    /**
     * Record the first activation. The conditional update is atomic, so
     * concurrent or later calls never overwrite the original fingerprint.
     */
    public function activate(?string $ip, ?string $userAgent): void
    {
        static::query()
            ->whereKey($this->getKey())
            ->whereNull('activated_at')
            ->update([
                'fingerprint' => json_encode(['ip' => $ip, 'useragent' => $userAgent]),
                'activated_at' => now(),
            ]);
    }

    protected function searchableColumns(): array
    {
        return ['customer', 'product', 'key', 'duration', 'fingerprint', 'activated_at', 'created_at'];
    }
}

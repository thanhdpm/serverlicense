<?php

namespace Database\Factories;

use App\Enums\DurationUnit;
use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<License>
 */
class LicenseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => Str::upper(implode('-', str_split(Str::random(25), 5))),
            'duration' => DurationUnit::Days->toSeconds(30),
            'fingerprint' => null,
            'activated_at' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (License $license) {
            $customer = $license->customerRecord ?? Customer::factory()->create();
            $product = Product::query()->find($license->product_id) ?? Product::factory()->create();

            $license->forceFill([
                'customer_id' => $customer->getKey(),
                'customer' => $customer->toArray(),
                'product_id' => $product->getKey(),
                'product' => $product->toArray(),
            ]);
        });
    }

    public function activated(string $ip = '127.0.0.1', string $userAgent = 'TestClient/1.0'): static
    {
        return $this->state(fn () => [
            'fingerprint' => ['ip' => $ip, 'useragent' => $userAgent],
            'activated_at' => now(),
        ]);
    }
}

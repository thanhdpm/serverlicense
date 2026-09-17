<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Version;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Version>
 */
class VersionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'version' => fake()->unique()->semver(),
            'description' => fake()->optional()->sentence(),
            'file_url' => null,
        ];
    }
}

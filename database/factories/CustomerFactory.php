<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => fake()->name(),
            'gender' => fake()->randomElement(Gender::cases())->value,
            'dob' => fake()->optional()->date(),
            'phone' => fake()->optional()->numerify('09########'),
            'email' => fake()->optional()->safeEmail(),
            'note' => fake()->optional()->sentence(),
        ];
    }
}

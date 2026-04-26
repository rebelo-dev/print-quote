<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuoteRequest>
 */
class QuoteRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'customer_name'  => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'title'          => fake()->sentence(3),
            'description'    => fake()->optional()->paragraph(),
            'quantity'       => fake()->numberBetween(1, 5),
            'status'         => 'pending',
            //stl info is null for now
        ];
    }

     public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function quoted(): static
    {
        return $this->state(fn () => ['status' => 'quoted']);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rejected']);
    }
}

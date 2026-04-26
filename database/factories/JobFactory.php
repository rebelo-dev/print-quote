<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'          => fake()->sentence(3),
            'description'    => fake()->optional()->paragraph(),
            'quantity'       => fake()->numberBetween(1, 5),
            'price'          => fake()->randomFloat(2, 5.00, 150.00),
            'status'         => 'pending',
            'payment_status' => 'pending',
            'payment_method' => null,
            'started_at'     => null,
            'completed_at'   => null,
        ];
    }

    public function printing(): static
    {
        return $this->state(fn () => [
            'status'     => 'printing',
            'started_at' => now(),
        ]);
    }

    public function done(): static
    {
        return $this->state(fn () => [
            'status'       => 'done',
            'started_at'   => now()->subHours(3),
            'completed_at' => now(),
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn () => [
            'status'       => 'delivered',
            'completed_at' => now(),
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'payment_status' => 'paid',
            'payment_method' => fake()->randomElement(['mbway', 'cash', 'paypal', 'stripe']),
        ]);
    }
    /*
        function paid should work for random selection,
        but im considering adding a method here so that each payment method can be selected by input
    */
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuoteProposal>
 */
class QuoteProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
      public function definition(): array
    {
        return [
            'title'            => fake()->sentence(3),
            'description'      => fake()->optional()->paragraph(),
            'estimated_hours'  => fake()->randomFloat(2, 0.5, 10.0),
            'estimated_weight' => fake()->randomFloat(2, 5.0, 200.0),
            'quantity'         => fake()->numberBetween(1, 5),
            'price'            => fake()->randomFloat(2, 5.00, 150.00),
            'notes'            => fake()->optional()->sentence(),
            'status'           => 'draft',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function sent(): static
    {
        return $this->state(fn () => ['status' => 'sent']);
    }

    public function accepted(): static
    {
        return $this->state(fn () => ['status' => 'accepted']);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rejected']);
    }
}
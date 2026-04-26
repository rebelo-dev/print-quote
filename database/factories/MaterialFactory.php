<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materials = [
            ['name' => 'PLA',  'density' => 1.240, 'cost_per_gram' => 0.0500],
            ['name' => 'PETG', 'density' => 1.270, 'cost_per_gram' => 0.0600],
            ['name' => 'ABS',  'density' => 1.050, 'cost_per_gram' => 0.0450],
            ['name' => 'TPU',  'density' => 1.210, 'cost_per_gram' => 0.0800],
        ];

        $material = fake()->randomElement($materials);

        return [
            'name'          => $material['name'],
            'density'       => $material['density'],
            'cost_per_gram' => $material['cost_per_gram'],
            'defaulted_at'  => null,
        ];
    }
}

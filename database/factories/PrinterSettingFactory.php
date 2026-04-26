<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrinterSetting>
 */
class PrinterSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cost_per_hour'     => fake()->randomFloat(2, 1.50, 5.00),
            'volume_per_hour'   => fake()->randomFloat(2, 1.50, 4.00),
            'margin_percentage' => fake()->randomFloat(2, 20.00, 40.00),
        ];
    }
}

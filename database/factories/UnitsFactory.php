<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Units>
 */
class UnitsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $units = ['Stk', 'Pau', 'Std','.','h','m'];

        return [
            'unitdesignation' => $units[array_rand($units)], // Erzeugt eine zufällige Bezeichnung
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}

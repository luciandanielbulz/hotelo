<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Taxrates>
 */
class TaxratesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rates = [0,20];

        return [
            'taxrate' => $rates[array_rand($rates)] // Zufälliger Steuersatz zwischen 5 und 25 Prozent
        ];

    }
}

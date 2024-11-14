<?php

namespace Database\Factories;
use App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factorrules>
 */
class FactorrulesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'descriptionpattern' => $this->faker->words(3, true),  // Zufälliges Beschreibungsmuster (z.B. "Rabatt Regelung")
            'factor' => $this->faker->randomFloat(2, 0.01, 99.99),  // Zufälliger Faktor zwischen 0.01 und 99.99
            'client_id' => 1,  // Verknüpfung mit einem Client
        ];
    }
}

<?php
namespace Database\Factories;

use App\Models\Salutation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salutations>
 */
class SalutationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = ['Herr', 'Frau', 'Firma'];

        // Wählt zufällig eines der Wörter aus der Liste
        return [
            'name' => $titles[array_rand($titles)],  // Zufälliger Titel aus der Liste
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Offerpositions;
use Illuminate\Database\Eloquent\Factories\Factory;


class OfferpositionsFactory extends Factory
{
    // Verknüpfung der Factory mit dem OfferPosition-Modell
    protected $model = Offerpositions::class;

    public function definition()
    {
        return [
            'offer_id' => \App\Models\Offers::factory(),  // Generiere oder referenziere ein Angebot
            'amount' => $this->faker->randomFloat(2, 1, 50), // Zufällige Menge zwischen 1 und 100
            'designation' => $this->faker->words(3, true),  // Zufällige Bezeichnung
            'details' => $this->faker->optional()->text(200),  // Optionale Details mit maximal 200 Zeichen
            'unit_id' => $this->faker->numberBetween(1, 6),  // Generiere oder referenziere eine Einheit
            'price' => $this->faker->randomFloat(2, 10, 1000),  // Zufälliger Preis zwischen 10 und 1000
            'positiontext' => $this->faker->boolean(),  // Zufällig true oder false
            'sequence' => $this->faker->numberBetween(1, 100),  // Zufällige Sequenznummer
            'issoftdelteted' => $this->faker->optional()->boolean(),  // Optional true oder false
        ];
    }
}

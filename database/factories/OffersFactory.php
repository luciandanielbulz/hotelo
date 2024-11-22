<?php
namespace Database\Factories;

use App\Models\Offers;
use App\Models\Customer;
use App\Models\Taxrates;
use App\Models\Conditions;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class OffersFactory extends Factory
{
    protected $model = Offers::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(), // Verknüpft mit einer zufälligen Customer-Instanz
            'date' => $this->faker->dateTimeBetween('-4 years', 'now'), // Zufälliges Datum
            'number' => $this->faker->unique()->numberBetween(1000, 9999), // Zufällige, einzigartige Angebotsnummer
            'description' => $this->faker->sentence(), // Zufällige Beschreibung
            'tax_id' => $this->faker->numberBetween(1, 2), // Verknüpft mit einer zufälligen Taxrate-Instanz
            'taxburden' => $this->faker->randomFloat(2, 5, 20), // Zufällige Steuerlast zwischen 5 und 20
            'deposit' => $this->faker->boolean(), // Zufällig true oder false
            'depositamount' => $this->faker->randomFloat(2, 100, 1000), // Zufälliger Betrag für die Anzahlung
            'periodfrom' => $this->faker->dateTimeThisYear(), // Zufälliges Datum im laufenden Jahr für den Startzeitpunkt
            'periodto' => $this->faker->dateTimeThisYear(), // Zufälliges Datum im laufenden Jahr für den Endzeitpunkt
            'condition_id' => $this->faker->numberBetween(1, 7), // Verknüpft mit einer zufälligen Condition-Instanz
            'payed' => $this->faker->boolean(), // Zufällig true oder false
            'payeddate' => $this->faker->optional()->date(), // Optionales Zufallsdatum für die Zahlung
            'archived' => $this->faker->boolean(), // Zufällig true oder false
            'archiveddate' => $this->faker->optional()->dateTime(), // Optionales Zufallsdatum für das Archivierungsdatum
            'comment' => $this->faker->optional()->sentence(), // Optionaler Kommentar
        ];
    }
}

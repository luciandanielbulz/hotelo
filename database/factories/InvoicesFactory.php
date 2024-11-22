<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoices;
use App\Models\Customer;
use App\Models\Taxrates;
use App\Models\Conditions;
use App\Models\Offers;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoices>
 */
class InvoicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),  // Verknüpfung mit einem zufälligen Kunden
            'date' => $this->faker->dateTimeBetween('-4 years', 'now'),  // Aktuelles Datum und Uhrzeit
            'number' => $this->faker->unique()->numerify('INV-#######'),  // Zufällige Rechnungsnummer
            'description' => $this->faker->optional()->paragraph(),  // Optional Beschreibung
            'tax_id' => $this->faker->numberBetween(1, 2),  // Zufällige Steuer-ID
            'taxburden' => $this->faker->optional()->boolean(),  // Optional Steuerlast
            'depositamount' => $this->faker->optional()->randomFloat(2, 0, 1000),  // Optional Anzahlungsbetrag
            'periodfrom' => $this->faker->optional()->dateTime(),  // Optional Anfang der Periode
            'periodto' => $this->faker->optional()->dateTime(),  // Optional Ende der Periode
            'condition_id' => $this->faker->numberBetween(1, 7),  // Verknüpfung mit einer zufälligen Bedingung
            'payed' => $this->faker->optional()->boolean(),  // Optional Bezahltstatus
            'payeddate' => $this->faker->optional()->dateTime(),  // Optional Bezahldatum
            'archived' => false,  // Standard auf false gesetzt
            'archiveddate' => $this->faker->optional()->date(),  // Optional Archivierungsdatum
            'comment' => $this->faker->optional()->text(),  // Optional Kommentar
            'createddate' => now()  // Aktuelles Erstellungsdatum

        ];

    }
}

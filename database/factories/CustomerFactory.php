<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Conditions;  // Korrektur des Namens und Namespace
use App\Models\Salutations;  // Sicherstellen, dass Salutation korrekt importiert wird
use App\Models\Clients;  // Sicherstellen, dass Client korrekt importiert wird

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'companyname' => $this->faker->company,  // Realistischer Firmenname
            'customername' => $this->faker->name,  // Realistischer Name des Kunden
            'address' => $this->faker->address,  // Realistische Adresse
            'postalcode' => $this->faker->postcode,  // Realistischer Postleitzahl
            'location' => $this->faker->city,  // Realistische Stadt
            'country' => $this->faker->country,  // Realistisches Land
            'phone' => $this->faker->phoneNumber,  // Realistische Telefonnummer
            'fax' => $this->faker->phoneNumber,  // Realistische Faxnummer (kann auch eine Telefonnummer sein)
            'email' => $this->faker->unique()->safeEmail,  // Realistische und eindeutige E-Mail-Adresse
            'tax_id' => $this->faker->numberBetween(1, 2),
            'vat_number' => $this->faker->regexify('[A-Z]{2}[0-9]{8}'),
            'condition_id' => $this->faker->numberBetween(1, 7),  // Beispiel für eine verknüpfte Tabelle
            'salutation_id' => $this->faker->numberBetween(1, 2),
            'title' => $this->faker->title,  // Realistischer Titel (z. B. Dr., Prof.)
            'active' => $this->faker->boolean,  // Zufälliger Aktivitätsstatus
            'emailsubject' => $this->faker->sentence(6),  // Realistisches E-Mail-Betreff
            'emailbody' => $this->faker->paragraph(3),  // Realistischer E-Mail-Text (mehrere Absätze)
            'issoftdeleted' => $this->faker->boolean,  // Zufälliger Soft-Delete-Status
            'client_id' => 1,  // Beispiel für eine verknüpfte Tabelle
        ];
    }
}

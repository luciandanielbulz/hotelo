<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cashflows>
 */
class CashflowsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transactiondate' => $this->faker->date(), // Zufälliges Datum
            'partnername' => $this->faker->optional()->company, // Optionaler Partnername (z.B. Firmenname)
            'partneriban' => $this->faker->optional()->iban('DE'), // Optionale IBAN (Beispiel: DE)
            'bic_swift' => $this->faker->optional()->swiftBicNumber, // Optionale BIC/SWIFT
            'partneracountnumber' => $this->faker->optional()->bankAccountNumber, // Optionale Kontonummer
            'bankcode' => $this->faker->optional()->regexify('[0-9]{10}'), // Optionale Bankleitzahl
            'amount' => $this->faker->randomFloat(2, 1, 10000), // Zufälliger Betrag zwischen 1 und 10.000
            'currency' => $this->faker->currencyCode, // Währungs-Code
            'transactiondetails' => $this->faker->optional()->sentence, // Optionale Transaktionsdetails
            'transactionreference' => $this->faker->optional()->lexify('REF?????'), // Optionale Transaktionsreferenz
            'ownaccountname' => $this->faker->optional()->name, // Optionaler Name des eigenen Kontos
            'owniban' => $this->faker->optional()->iban('DE'), // Optionale IBAN des eigenen Kontos
            'paymentmethod' => $this->faker->randomElement(['Überweisung', 'Kartenzahlung']), // Zahlungsmethode (enum)
            'transactiontype' => $this->faker->randomElement(['Einnahme', 'Ausgabe', 'Info']), // Transaktionstyp (enum)
            'date' => now(), // Aktuelles Datum und Zeitstempel
            'client_id' => 1, // Verknüpfung mit der Clients-Tabelle
            'factor' => $this->faker->optional()->randomFloat(2, 0.1, 2), // Optionaler Faktor (zwischen 0.1 und 2)
        ];
    }
}

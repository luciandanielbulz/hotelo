<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Messages>
 */
class MessagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->optional()->randomElement(['Rechnung', 'Angebot', 'Info']), // Optionaler Typ (z.B. "Rechnung", "Angebot")
            'customer_id' => \App\Models\Customer::factory(), // Verknüpfung mit Kunden
            'objectnumber' => $this->faker->optional()->numberBetween(1000, 9999), // Optional, zufällige Objekt-Nummer
            'senddate' => $this->faker->optional()->dateTimeThisYear(), // Optional, zufälliges Sendedatum
            'filename' => $this->faker->optional()->lexify('file_??????.pdf'), // Optionaler Dateiname
            'withattachment' => $this->faker->optional()->boolean(), // Optionaler Anhang
            'status' => $this->faker->optional()->numberBetween(0, 5), // Optionaler Status (z.B. 0 für neu, 5 für gesendet)
            'client_id' => 1, // Verknüpfung mit Clients
            'recipientmail' => $this->faker->optional()->email, // Optional, zufällige E-Mail des Empfängers
        ];
    }
}

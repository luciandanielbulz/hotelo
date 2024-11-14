<?php

namespace Database\Factories;
use App\Models\Invoicepositions;
use App\Models\Invoices;
use App\Models\Units;

use Illuminate\Database\Eloquent\Factories\Factory;

class InvoicepositionsFactory extends Factory
{

    protected $model = Invoicepositions::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoices::factory(),  // Verknüpfung mit einer zufälligen Rechnung
            'amount' => $this->faker->randomFloat(2, 1, 1000),  // Zufällige Menge, Dezimalzahl
            'designation' => $this->faker->word(),  // Zufällige Bezeichnung
            'details' => $this->faker->optional()->paragraph(),  // Optional Details
            'unit_id' => $this->faker->numberBetween(1, 6),  // Verknüpfung mit einer zufälligen Einheit, Standardwert 1 kann im Seeder überschrieben werden
            'price' => $this->faker->randomFloat(2, 0.01, 1000),  // Zufälliger Preis
            'positiontext' => $this->faker->optional()->text(),  // Optional Positionstext
            'positiontextoption' => $this->faker->boolean(false),  // Standard false, aber zufällig
            'sequence' => $this->faker->optional()->numberBetween(1, 100),  // Optionale Sequenznummer
            'issoftdeleted' => false,  // Standardmäßig false für Soft-Delete
        ];
    }
}

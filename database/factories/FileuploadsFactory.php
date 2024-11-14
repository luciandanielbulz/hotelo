<?php

namespace Database\Factories;
use App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fileuploads>
 */
class FileuploadsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filename' => $this->faker->lexify('file_??????.txt'),  // Generiere einen zufälligen Dateinamen
            'filetempname' => $this->faker->lexify('temp_??????.tmp'),  // Generiere einen temporären Dateinamen
            'filesize' => $this->faker->numberBetween(100, 10000),  // Zufällige Dateigröße zwischen 100 und 10000 Bytes
            'fileerrors' => $this->faker->optional()->sentence(),  // Optionaler Fehler beim Hochladen
            'date' => now(),  // Aktuelles Datum und Uhrzeit
            'processed' => $this->faker->boolean(),  // Zufällig true oder false
            'client_id' => 1,  // Verknüpfung mit einem Client
        ];
    }
}

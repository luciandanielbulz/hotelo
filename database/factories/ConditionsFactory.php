<?php

namespace Database\Factories;

use App\Models\Conditions;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conditions>
 */
class ConditionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Feste Datensätze definieren
        $conditions = [
            [
                'conditionname' => 'keine',
                'daysskonto' => 0,
                'skonto' => 0,
                'daysnetto' => 0,
                'client_id' => 1,
            ],
            [
                'conditionname' => '2% 7Tage',
                'daysskonto' => 7,
                'skonto' => 2,
                'daysnetto' => 14,
                'client_id' => 1,
            ],
            [
                'conditionname' => '14Tage netto',
                'daysskonto' => 0,
                'skonto' => 0,
                'daysnetto' => 14,
                'client_id' => 1,
            ],
            [
                'conditionname' => 'Prompt',
                'daysskonto' => 0,
                'skonto' => 0,
                'daysnetto' => 0,
                'client_id' => 1,
            ],
            [
                'conditionname' => '14Tage Netto',
                'daysskonto' => 0,
                'skonto' => 0,
                'daysnetto' => 14,
                'client_id' => 1,
            ],
            [
                'conditionname' => 'Vorauskasse',
                'daysskonto' => 0,
                'skonto' => 0,
                'daysnetto' => 0,
                'client_id' => 1,
            ],
            [
                'conditionname' => '7Tage Netto',
                'daysskonto' => 0,
                'skonto' => 0,
                'daysnetto' => 7,
                'client_id' => 1,
            ],
        ];

        // Zufälligen Datensatz zurückgeben
        return $this->faker->randomElement($conditions);
    }

}

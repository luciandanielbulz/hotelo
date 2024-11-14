<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = [
            ['id' => 1, 'conditionname' => 'keine', 'daysskonto' => 0, 'skonto' => 0, 'daysnetto' => 0, 'client_id' => 1],
            ['id' => 2, 'conditionname' => '2% 7Tage', 'daysskonto' => 7, 'skonto' => 2, 'daysnetto' => 14, 'client_id' => 1],
            ['id' => 3, 'conditionname' => '14Tage netto', 'daysskonto' => 0, 'skonto' => 0, 'daysnetto' => 14, 'client_id' => 1],
            ['id' => 4, 'conditionname' => 'Prompt', 'daysskonto' => 0, 'skonto' => 0, 'daysnetto' => 0, 'client_id' => 1],
            ['id' => 5, 'conditionname' => '14Tage Netto', 'daysskonto' => 0, 'skonto' => 0, 'daysnetto' => 14, 'client_id' => 1],
            ['id' => 6, 'conditionname' => 'Vorauskasse', 'daysskonto' => 0, 'skonto' => 0, 'daysnetto' => 0, 'client_id' => 1],
            ['id' => 7, 'conditionname' => '7Tage Netto', 'daysskonto' => 0, 'skonto' => 0, 'daysnetto' => 7, 'client_id' => 1],
        ];

        foreach ($conditions as $condition) {
            DB::table('conditions')->updateOrInsert(['id' => $condition['id']], $condition);
        }
    }

}

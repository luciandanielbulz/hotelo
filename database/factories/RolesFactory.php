<?php

// database/factories/RoleFactory.php

namespace Database\Factories;

use App\Models\Roles;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolesFactory extends Factory
{
    /**
     * Der Name des zugehörigen Modells.
     *
     * @var string
     */
    protected $model = Roles::class;

    /**
     * Definiere den Standardzustand des Modells.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'customerlist' => $this->faker->boolean(),
            'offerlist' => $this->faker->boolean(),
            'invoiceslist' => $this->faker->boolean(),
            'useradministration' => $this->faker->boolean(),
            'rolesadministration' => $this->faker->boolean(),
            'settings' => $this->faker->boolean(),
            'emaillist' => $this->faker->boolean(),
            'personalsettings' => $this->faker->boolean(),
            'salesanalysis' => $this->faker->boolean(),
            'clients' => $this->faker->boolean(),
            'cashflow' => $this->faker->boolean(),
        ];
    }
}


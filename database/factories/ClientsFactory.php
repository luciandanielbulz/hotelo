<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientsFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'clientname' => $this->faker->name,
            'companyname' => $this->faker->company,
            'business' => $this->faker->randomElement(['Kleinunternehmen', 'Mittelständisches Unternehmen', 'Großunternehmen']),
            'address' => $this->faker->address,
            'postalcode' => $this->faker->postcode,
            'location' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'tax_id' => 1, // Standardwert, kann angepasst werden
            'webpage' => $this->faker->url,
            'bank' => $this->faker->company,
            'accountnumber' => $this->faker->iban('AT'),
            'bic' => $this->faker->swiftBicNumber,
            'smallbusiness' => $this->faker->boolean,
            'logo' => $this->faker->imageUrl(100, 100, 'business', true, 'Logo'),
            'logoheight' => $this->faker->numberBetween(100, 300),
            'logowidth' => $this->faker->numberBetween(100, 300),
            'signature' => $this->faker->sentence,
            'style' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}

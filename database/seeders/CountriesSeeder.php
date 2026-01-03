<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Austria', 'name_de' => 'Österreich', 'iso_code' => 'AT', 'iso_code_3' => 'AUT', 'phone_code' => '+43', 'currency_code' => 'EUR', 'sort_order' => 1],
            ['name' => 'Belgium', 'name_de' => 'Belgien', 'iso_code' => 'BE', 'iso_code_3' => 'BEL', 'phone_code' => '+32', 'currency_code' => 'EUR', 'sort_order' => 2],
            ['name' => 'Bulgaria', 'name_de' => 'Bulgarien', 'iso_code' => 'BG', 'iso_code_3' => 'BGR', 'phone_code' => '+359', 'currency_code' => 'BGN', 'sort_order' => 3],
            ['name' => 'Croatia', 'name_de' => 'Kroatien', 'iso_code' => 'HR', 'iso_code_3' => 'HRV', 'phone_code' => '+385', 'currency_code' => 'EUR', 'sort_order' => 4],
            ['name' => 'Cyprus', 'name_de' => 'Zypern', 'iso_code' => 'CY', 'iso_code_3' => 'CYP', 'phone_code' => '+357', 'currency_code' => 'EUR', 'sort_order' => 5],
            ['name' => 'Czech Republic', 'name_de' => 'Tschechien', 'iso_code' => 'CZ', 'iso_code_3' => 'CZE', 'phone_code' => '+420', 'currency_code' => 'CZK', 'sort_order' => 6],
            ['name' => 'Denmark', 'name_de' => 'Dänemark', 'iso_code' => 'DK', 'iso_code_3' => 'DNK', 'phone_code' => '+45', 'currency_code' => 'DKK', 'sort_order' => 7],
            ['name' => 'Estonia', 'name_de' => 'Estland', 'iso_code' => 'EE', 'iso_code_3' => 'EST', 'phone_code' => '+372', 'currency_code' => 'EUR', 'sort_order' => 8],
            ['name' => 'Finland', 'name_de' => 'Finnland', 'iso_code' => 'FI', 'iso_code_3' => 'FIN', 'phone_code' => '+358', 'currency_code' => 'EUR', 'sort_order' => 9],
            ['name' => 'France', 'name_de' => 'Frankreich', 'iso_code' => 'FR', 'iso_code_3' => 'FRA', 'phone_code' => '+33', 'currency_code' => 'EUR', 'sort_order' => 10],
            ['name' => 'Germany', 'name_de' => 'Deutschland', 'iso_code' => 'DE', 'iso_code_3' => 'DEU', 'phone_code' => '+49', 'currency_code' => 'EUR', 'sort_order' => 11],
            ['name' => 'Greece', 'name_de' => 'Griechenland', 'iso_code' => 'GR', 'iso_code_3' => 'GRC', 'phone_code' => '+30', 'currency_code' => 'EUR', 'sort_order' => 12],
            ['name' => 'Hungary', 'name_de' => 'Ungarn', 'iso_code' => 'HU', 'iso_code_3' => 'HUN', 'phone_code' => '+36', 'currency_code' => 'HUF', 'sort_order' => 13],
            ['name' => 'Ireland', 'name_de' => 'Irland', 'iso_code' => 'IE', 'iso_code_3' => 'IRL', 'phone_code' => '+353', 'currency_code' => 'EUR', 'sort_order' => 14],
            ['name' => 'Italy', 'name_de' => 'Italien', 'iso_code' => 'IT', 'iso_code_3' => 'ITA', 'phone_code' => '+39', 'currency_code' => 'EUR', 'sort_order' => 15],
            ['name' => 'Latvia', 'name_de' => 'Lettland', 'iso_code' => 'LV', 'iso_code_3' => 'LVA', 'phone_code' => '+371', 'currency_code' => 'EUR', 'sort_order' => 16],
            ['name' => 'Lithuania', 'name_de' => 'Litauen', 'iso_code' => 'LT', 'iso_code_3' => 'LTU', 'phone_code' => '+370', 'currency_code' => 'EUR', 'sort_order' => 17],
            ['name' => 'Luxembourg', 'name_de' => 'Luxemburg', 'iso_code' => 'LU', 'iso_code_3' => 'LUX', 'phone_code' => '+352', 'currency_code' => 'EUR', 'sort_order' => 18],
            ['name' => 'Malta', 'name_de' => 'Malta', 'iso_code' => 'MT', 'iso_code_3' => 'MLT', 'phone_code' => '+356', 'currency_code' => 'EUR', 'sort_order' => 19],
            ['name' => 'Netherlands', 'name_de' => 'Niederlande', 'iso_code' => 'NL', 'iso_code_3' => 'NLD', 'phone_code' => '+31', 'currency_code' => 'EUR', 'sort_order' => 20],
            ['name' => 'Poland', 'name_de' => 'Polen', 'iso_code' => 'PL', 'iso_code_3' => 'POL', 'phone_code' => '+48', 'currency_code' => 'PLN', 'sort_order' => 21],
            ['name' => 'Portugal', 'name_de' => 'Portugal', 'iso_code' => 'PT', 'iso_code_3' => 'PRT', 'phone_code' => '+351', 'currency_code' => 'EUR', 'sort_order' => 22],
            ['name' => 'Romania', 'name_de' => 'Rumänien', 'iso_code' => 'RO', 'iso_code_3' => 'ROU', 'phone_code' => '+40', 'currency_code' => 'RON', 'sort_order' => 23],
            ['name' => 'Slovakia', 'name_de' => 'Slowakei', 'iso_code' => 'SK', 'iso_code_3' => 'SVK', 'phone_code' => '+421', 'currency_code' => 'EUR', 'sort_order' => 24],
            ['name' => 'Slovenia', 'name_de' => 'Slowenien', 'iso_code' => 'SI', 'iso_code_3' => 'SVN', 'phone_code' => '+386', 'currency_code' => 'EUR', 'sort_order' => 25],
            ['name' => 'Spain', 'name_de' => 'Spanien', 'iso_code' => 'ES', 'iso_code_3' => 'ESP', 'phone_code' => '+34', 'currency_code' => 'EUR', 'sort_order' => 26],
            ['name' => 'Sweden', 'name_de' => 'Schweden', 'iso_code' => 'SE', 'iso_code_3' => 'SWE', 'phone_code' => '+46', 'currency_code' => 'SEK', 'sort_order' => 27],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['iso_code' => $country['iso_code']],
                array_merge($country, [
                    'is_eu_member' => true,
                    'is_active' => true,
                ])
            );
        }
    }
}

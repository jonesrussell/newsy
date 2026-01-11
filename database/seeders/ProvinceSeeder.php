<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['code' => 'AB', 'name' => 'Alberta', 'name_fr' => 'Alberta'],
            ['code' => 'BC', 'name' => 'British Columbia', 'name_fr' => 'Colombie-Britannique'],
            ['code' => 'MB', 'name' => 'Manitoba', 'name_fr' => 'Manitoba'],
            ['code' => 'NB', 'name' => 'New Brunswick', 'name_fr' => 'Nouveau-Brunswick'],
            ['code' => 'NL', 'name' => 'Newfoundland and Labrador', 'name_fr' => 'Terre-Neuve-et-Labrador'],
            ['code' => 'NT', 'name' => 'Northwest Territories', 'name_fr' => 'Territoires du Nord-Ouest'],
            ['code' => 'NS', 'name' => 'Nova Scotia', 'name_fr' => 'Nouvelle-Écosse'],
            ['code' => 'NU', 'name' => 'Nunavut', 'name_fr' => 'Nunavut'],
            ['code' => 'ON', 'name' => 'Ontario', 'name_fr' => 'Ontario'],
            ['code' => 'PE', 'name' => 'Prince Edward Island', 'name_fr' => 'Île-du-Prince-Édouard'],
            ['code' => 'QC', 'name' => 'Quebec', 'name_fr' => 'Québec'],
            ['code' => 'SK', 'name' => 'Saskatchewan', 'name_fr' => 'Saskatchewan'],
            ['code' => 'YT', 'name' => 'Yukon', 'name_fr' => 'Yukon'],
        ];

        foreach ($provinces as $province) {
            Province::query()->updateOrCreate(
                ['code' => $province['code']],
                $province
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\MunicipalityType;
use Illuminate\Database\Seeder;

class MunicipalityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['slug' => 'city', 'name' => 'City', 'name_fr' => 'Ville', 'description' => 'Incorporated city'],
            ['slug' => 'town', 'name' => 'Town', 'name_fr' => 'Ville', 'description' => 'Incorporated town'],
            ['slug' => 'village', 'name' => 'Village', 'name_fr' => 'Village', 'description' => 'Incorporated village'],
            ['slug' => 'hamlet', 'name' => 'Hamlet', 'name_fr' => 'Hameau', 'description' => 'Unincorporated hamlet'],
            ['slug' => 'municipality', 'name' => 'Municipality', 'name_fr' => 'Municipalité', 'description' => 'General municipality'],
            ['slug' => 'regional-municipality', 'name' => 'Regional Municipality', 'name_fr' => 'Municipalité régionale', 'description' => 'Regional government'],
            ['slug' => 'csd', 'name' => 'Census Subdivision', 'name_fr' => 'Subdivision de recensement', 'description' => 'Statistics Canada census subdivision'],
            ['slug' => 'population-centre', 'name' => 'Population Centre', 'name_fr' => 'Centre de population', 'description' => 'Statistics Canada population centre'],
            ['slug' => 'reserve', 'name' => 'Indian Reserve', 'name_fr' => 'Réserve indienne', 'description' => 'First Nations reserve'],
            ['slug' => 'unorganized', 'name' => 'Unorganized Area', 'name_fr' => 'Territoire non organisé', 'description' => 'Unorganized territory'],
        ];

        foreach ($types as $type) {
            MunicipalityType::query()->updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}

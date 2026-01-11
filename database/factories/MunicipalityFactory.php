<?php

namespace Database\Factories;

use App\Models\MunicipalityType;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipality>
 */
class MunicipalityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $municipalityType = MunicipalityType::query()->inRandomOrder()->first();
        if (! $municipalityType) {
            $municipalityType = MunicipalityType::query()->create([
                'slug' => 'city',
                'name' => 'City',
                'name_fr' => 'Ville',
                'description' => 'Incorporated city',
            ]);
        }

        $province = Province::query()->inRandomOrder()->first();
        if (! $province) {
            $province = Province::query()->create([
                'code' => 'ON',
                'name' => 'Ontario',
                'name_fr' => 'Ontario',
            ]);
        }

        return [
            'statcan_id' => fake()->unique()->numerify('######'),
            'name' => fake()->city(),
            'municipality_type_id' => $municipalityType->id,
            'province_id' => $province->id,
            'population' => fake()->numberBetween(500, 500000),
            'population_year' => fake()->year(),
            'latitude' => fake()->latitude(41, 83),
            'longitude' => fake()->longitude(-141, -52),
            'area_sq_km' => fake()->randomFloat(2, 1, 5000),
            'timezone' => fake()->randomElement(['America/Toronto', 'America/Vancouver', 'America/Edmonton', 'America/Winnipeg']),
            'data_source' => 'statcan',
        ];
    }
}

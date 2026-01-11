<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsSource>
 */
class NewsSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' News',
            'url' => fake()->url(),
            'type' => fake()->randomElement(['newspaper', 'tv', 'radio', 'online']),
            'scope' => fake()->randomElement(['local', 'regional', 'provincial']),
            'language' => fake()->randomElement(['en', 'fr', 'bilingual']),
            'reliability_score' => fake()->numberBetween(60, 100),
            'is_active' => true,
            'discovery_method' => 'manual',
        ];
    }
}

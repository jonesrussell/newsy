<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'name' => 'CBC News',
                'url' => 'https://www.cbc.ca/news',
                'type' => 'tv',
                'scope' => 'national',
                'language' => 'en',
                'reliability_score' => 95,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'Radio-Canada',
                'url' => 'https://ici.radio-canada.ca/',
                'type' => 'tv',
                'scope' => 'national',
                'language' => 'fr',
                'reliability_score' => 95,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'The Globe and Mail',
                'url' => 'https://www.theglobeandmail.com/',
                'type' => 'newspaper',
                'scope' => 'national',
                'language' => 'en',
                'reliability_score' => 90,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'Toronto Star',
                'url' => 'https://www.thestar.com/',
                'type' => 'newspaper',
                'scope' => 'regional',
                'language' => 'en',
                'reliability_score' => 85,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'La Presse',
                'url' => 'https://www.lapresse.ca/',
                'type' => 'newspaper',
                'scope' => 'regional',
                'language' => 'fr',
                'reliability_score' => 88,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'Calgary Herald',
                'url' => 'https://calgaryherald.com/',
                'type' => 'newspaper',
                'scope' => 'local',
                'language' => 'en',
                'reliability_score' => 80,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'Vancouver Sun',
                'url' => 'https://vancouversun.com/',
                'type' => 'newspaper',
                'scope' => 'local',
                'language' => 'en',
                'reliability_score' => 82,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'CityNews Toronto',
                'url' => 'https://toronto.citynews.ca/',
                'type' => 'tv',
                'scope' => 'local',
                'language' => 'en',
                'reliability_score' => 85,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'CJAD 800',
                'url' => 'https://www.iheartradio.ca/cjad',
                'type' => 'radio',
                'scope' => 'local',
                'language' => 'en',
                'reliability_score' => 80,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'blogTO',
                'url' => 'https://www.blogto.com/',
                'type' => 'blog',
                'scope' => 'hyperlocal',
                'language' => 'en',
                'reliability_score' => 70,
                'discovery_method' => 'scraped',
                'is_active' => true,
            ],
            [
                'name' => 'Daily Hive Vancouver',
                'url' => 'https://dailyhive.com/vancouver',
                'type' => 'online',
                'scope' => 'local',
                'language' => 'en',
                'reliability_score' => 75,
                'discovery_method' => 'scraped',
                'is_active' => true,
            ],
            [
                'name' => 'The Coast (Halifax)',
                'url' => 'https://www.thecoast.ca/',
                'type' => 'newspaper',
                'scope' => 'local',
                'language' => 'en',
                'reliability_score' => 78,
                'discovery_method' => 'manual',
                'is_active' => true,
            ],
        ];

        foreach ($sources as $source) {
            NewsSource::query()->updateOrCreate(
                ['url' => $source['url']],
                $source
            );
        }
    }
}

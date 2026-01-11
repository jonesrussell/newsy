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
            ],
            [
                'name' => 'Radio-Canada',
                'url' => 'https://ici.radio-canada.ca/',
                'type' => 'tv',
                'scope' => 'national',
                'language' => 'fr',
                'reliability_score' => 95,
                'discovery_method' => 'manual',
            ],
            [
                'name' => 'The Globe and Mail',
                'url' => 'https://www.theglobeandmail.com/',
                'type' => 'newspaper',
                'scope' => 'national',
                'language' => 'en',
                'reliability_score' => 90,
                'discovery_method' => 'manual',
            ],
            [
                'name' => 'Toronto Star',
                'url' => 'https://www.thestar.com/',
                'type' => 'newspaper',
                'scope' => 'regional',
                'language' => 'en',
                'reliability_score' => 85,
                'discovery_method' => 'manual',
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

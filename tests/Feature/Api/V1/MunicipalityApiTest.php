<?php

use App\Models\Municipality;
use App\Models\NewsSource;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('returns paginated municipalities via API', function () {
    Municipality::factory()->count(10)->create();

    getJson('/api/v1/municipalities')
        ->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'slug', 'province', 'municipality_type'],
            ],
            'meta' => ['total', 'per_page', 'current_page'],
        ]);
});

it('returns single municipality with relationships', function () {
    $municipality = Municipality::factory()->create();

    getJson("/api/v1/municipalities/{$municipality->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $municipality->id)
        ->assertJsonPath('data.name', $municipality->name);
});

it('can attach news source to municipality', function () {
    $municipality = Municipality::factory()->create();
    $newsSource = NewsSource::factory()->create();

    postJson("/api/v1/municipalities/{$municipality->id}/news-sources", [
        'news_source_id' => $newsSource->id,
        'coverage_type' => 'primary',
    ])
        ->assertCreated();

    expect($municipality->newsSources)->toHaveCount(1);
});

it('can detach news source from municipality', function () {
    $municipality = Municipality::factory()->create();
    $newsSource = NewsSource::factory()->create();

    $municipality->newsSources()->attach($newsSource->id);

    deleteJson("/api/v1/municipalities/{$municipality->id}/news-sources/{$newsSource->id}")
        ->assertNoContent();

    expect($municipality->newsSources()->count())->toBe(0);
});

it('validates news source attachment request', function () {
    $municipality = Municipality::factory()->create();

    postJson("/api/v1/municipalities/{$municipality->id}/news-sources", [
        'news_source_id' => 99999,
        'coverage_type' => 'primary',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['news_source_id']);
});

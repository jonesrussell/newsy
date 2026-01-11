<?php

use App\Models\NewsSource;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot access admin news source create', function () {
    $response = $this->get(route('dashboard.admin.news-sources.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view create news source page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.admin.news-sources.create'));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/news-sources/Create')
        );
});

test('authenticated users can create a news source', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('dashboard.admin.news-sources.store'), [
        'name' => 'Test News',
        'url' => 'https://testnews.com',
        'type' => 'newspaper',
        'scope' => 'local',
        'language' => 'en',
        'reliability_score' => 85,
    ]);

    $response->assertRedirect(route('news-sources.index'));

    $this->assertDatabaseHas('news_sources', [
        'name' => 'Test News',
        'url' => 'https://testnews.com',
        'type' => 'newspaper',
        'scope' => 'local',
        'language' => 'en',
        'reliability_score' => 85,
    ]);
});

test('news source creation requires valid data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('dashboard.admin.news-sources.store'), []);

    $response->assertSessionHasErrors(['name', 'url', 'type', 'scope', 'language']);
});

test('news source url must be unique', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    NewsSource::factory()->create(['url' => 'https://existing.com']);

    $response = $this->post(route('dashboard.admin.news-sources.store'), [
        'name' => 'Test News',
        'url' => 'https://existing.com',
        'type' => 'newspaper',
        'scope' => 'local',
        'language' => 'en',
    ]);

    $response->assertSessionHasErrors(['url']);
});

test('authenticated users can view edit news source page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newsSource = NewsSource::factory()->create();

    $response = $this->get(route('dashboard.admin.news-sources.edit', $newsSource));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/news-sources/Edit')
            ->where('newsSource.id', $newsSource->id)
        );
});

test('authenticated users can update a news source', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newsSource = NewsSource::factory()->create();

    $response = $this->put(route('dashboard.admin.news-sources.update', $newsSource), [
        'name' => 'Updated News',
        'url' => $newsSource->url,
        'type' => 'online',
        'scope' => 'regional',
        'language' => 'fr',
        'is_active' => false,
    ]);

    $response->assertRedirect(route('news-sources.show', $newsSource));

    $this->assertDatabaseHas('news_sources', [
        'id' => $newsSource->id,
        'name' => 'Updated News',
        'type' => 'online',
        'scope' => 'regional',
        'language' => 'fr',
        'is_active' => false,
    ]);
});

test('authenticated users can delete a news source', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $newsSource = NewsSource::factory()->create();

    $response = $this->delete(route('dashboard.admin.news-sources.destroy', $newsSource));

    $response->assertRedirect(route('news-sources.index'));

    $this->assertDatabaseMissing('news_sources', [
        'id' => $newsSource->id,
    ]);
});

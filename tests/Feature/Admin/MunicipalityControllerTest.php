<?php

use App\Models\Municipality;
use App\Models\MunicipalityType;
use App\Models\Province;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot access admin municipality create', function () {
    $response = $this->get(route('dashboard.admin.municipalities.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view create municipality page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard.admin.municipalities.create'));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/municipalities/Create')
            ->has('provinces')
            ->has('types')
        );
});

test('authenticated users can create a municipality', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $province = Province::query()->firstOrCreate(
        ['code' => 'ON'],
        ['name' => 'Ontario', 'name_fr' => 'Ontario']
    );
    $type = MunicipalityType::query()->firstOrCreate(
        ['slug' => 'city'],
        ['name' => 'City', 'name_fr' => 'Ville', 'description' => 'Incorporated city']
    );

    $response = $this->post(route('dashboard.admin.municipalities.store'), [
        'name' => 'Test City',
        'name_fr' => 'Ville de Test',
        'province_id' => $province->id,
        'municipality_type_id' => $type->id,
        'population' => 50000,
        'population_year' => 2021,
    ]);

    $response->assertRedirect(route('municipalities.index'));

    $this->assertDatabaseHas('municipalities', [
        'name' => 'Test City',
        'name_fr' => 'Ville de Test',
        'province_id' => $province->id,
        'municipality_type_id' => $type->id,
        'population' => 50000,
        'population_year' => 2021,
    ]);
});

test('municipality creation requires valid data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('dashboard.admin.municipalities.store'), []);

    $response->assertSessionHasErrors(['name', 'province_id', 'municipality_type_id']);
});

test('authenticated users can view edit municipality page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $municipality = Municipality::factory()->create();

    $response = $this->get(route('dashboard.admin.municipalities.edit', $municipality));

    $response->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('admin/municipalities/Edit')
            ->where('municipality.id', $municipality->id)
            ->has('provinces')
            ->has('types')
        );
});

test('authenticated users can update a municipality', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $municipality = Municipality::factory()->create();
    $newProvince = Province::query()->firstOrCreate(
        ['code' => 'AB'],
        ['name' => 'Alberta', 'name_fr' => 'Alberta']
    );

    $response = $this->put(route('dashboard.admin.municipalities.update', $municipality), [
        'name' => 'Updated City',
        'province_id' => $newProvince->id,
        'municipality_type_id' => $municipality->municipality_type_id,
    ]);

    $response->assertRedirect(route('municipalities.show', $municipality));

    $this->assertDatabaseHas('municipalities', [
        'id' => $municipality->id,
        'name' => 'Updated City',
        'province_id' => $newProvince->id,
    ]);
});

test('authenticated users can delete a municipality', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $municipality = Municipality::factory()->create();

    $response = $this->delete(route('dashboard.admin.municipalities.destroy', $municipality));

    $response->assertRedirect(route('municipalities.index'));

    $this->assertDatabaseMissing('municipalities', [
        'id' => $municipality->id,
    ]);
});

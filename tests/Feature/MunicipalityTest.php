<?php

use App\Models\Municipality;
use App\Models\MunicipalityType;
use App\Models\Province;

it('can create a municipality with factory', function () {
    $municipality = Municipality::factory()->create();

    expect($municipality)->toBeInstanceOf(Municipality::class)
        ->and($municipality->name)->not->toBeEmpty()
        ->and($municipality->province)->toBeInstanceOf(Province::class)
        ->and($municipality->municipalityType)->toBeInstanceOf(MunicipalityType::class);
});

it('generates slug automatically from name', function () {
    $municipality = Municipality::factory()->create(['name' => 'Test City']);

    expect($municipality->slug)->toBe('test-city');
});

it('can search municipalities by name', function () {
    Municipality::factory()->create(['name' => 'Toronto']);
    Municipality::factory()->create(['name' => 'Vancouver']);

    $results = Municipality::search('Toronto')->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->name)->toBe('Toronto');
});

it('can filter municipalities by province', function () {
    $ontario = Province::query()->firstOrCreate(
        ['code' => 'ON'],
        ['name' => 'Ontario', 'name_fr' => 'Ontario']
    );
    $alberta = Province::query()->firstOrCreate(
        ['code' => 'AB'],
        ['name' => 'Alberta', 'name_fr' => 'Alberta']
    );

    Municipality::factory()->count(2)->create(['province_id' => $ontario->id]);
    Municipality::factory()->count(3)->create(['province_id' => $alberta->id]);

    $results = Municipality::ofProvince('AB')->get();

    expect($results)->toHaveCount(3);
});

it('can filter municipalities by type', function () {
    $cityType = MunicipalityType::query()->firstOrCreate(
        ['slug' => 'city'],
        ['name' => 'City', 'name_fr' => 'Ville']
    );
    $townType = MunicipalityType::query()->firstOrCreate(
        ['slug' => 'town'],
        ['name' => 'Town', 'name_fr' => 'Ville']
    );

    Municipality::factory()->count(2)->create(['municipality_type_id' => $cityType->id]);
    Municipality::factory()->count(3)->create(['municipality_type_id' => $townType->id]);

    $results = Municipality::ofType('town')->get();

    expect($results)->toHaveCount(3);
});

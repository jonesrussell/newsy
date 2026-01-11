<?php

namespace App\Services;

use App\Models\DataImport;
use App\Models\Municipality;
use App\Models\MunicipalityType;
use App\Models\Province;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MunicipalityImportService
{
    public function __construct(
        private DataImport $dataImport
    ) {}

    public function importFromStatCan(string $geojsonUrl): void
    {
        $this->dataImport->markAsProcessing();

        try {
            $response = Http::timeout(120)->get($geojsonUrl);

            if (! $response->successful()) {
                throw new \Exception("Failed to fetch data from Statistics Canada: {$response->status()}");
            }

            $geojson = $response->json();
            $features = $geojson['features'] ?? [];

            foreach ($features as $feature) {
                $this->processFeature($feature);
            }

            $this->dataImport->markAsCompleted();
        } catch (\Exception $e) {
            Log::error('StatCan import failed', ['error' => $e->getMessage()]);
            $this->dataImport->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    public function importFromArray(array $municipalitiesData): void
    {
        $this->dataImport->markAsProcessing();

        try {
            foreach ($municipalitiesData as $data) {
                $this->processMunicipalityData($data);
            }

            $this->dataImport->markAsCompleted();
        } catch (\Exception $e) {
            Log::error('Array import failed', ['error' => $e->getMessage()]);
            $this->dataImport->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    private function processFeature(array $feature): void
    {
        try {
            $properties = $feature['properties'];
            $geometry = $feature['geometry'];

            $provinceCode = $this->extractProvinceCode($properties);
            $province = Province::query()->where('code', $provinceCode)->first();

            if (! $province) {
                $this->dataImport->increment('records_failed');

                return;
            }

            $typeSlug = $this->determineTypeSlug($properties);
            $type = MunicipalityType::query()->where('slug', $typeSlug)->first();

            if (! $type) {
                $this->dataImport->increment('records_failed');

                return;
            }

            $coordinates = $this->extractCentroid($geometry);

            $municipality = Municipality::query()->updateOrCreate(
                ['statcan_id' => $properties['CSDUID'] ?? null],
                [
                    'name' => $properties['CSDNAME'],
                    'name_fr' => $properties['CSDNAME_FR'] ?? null,
                    'municipality_type_id' => $type->id,
                    'province_id' => $province->id,
                    'population' => $properties['POPULATION'] ?? null,
                    'population_year' => 2021,
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng'],
                    'area_sq_km' => $properties['LANDAREA'] ?? null,
                    'data_source' => 'statcan',
                ]
            );

            if ($municipality->wasRecentlyCreated) {
                $this->dataImport->increment('records_created');
            } else {
                $this->dataImport->increment('records_updated');
            }

            $this->dataImport->increment('records_processed');
        } catch (\Exception $e) {
            Log::warning('Failed to process feature', ['error' => $e->getMessage()]);
            $this->dataImport->increment('records_failed');
        }
    }

    private function processMunicipalityData(array $data): void
    {
        try {
            $province = Province::query()->where('code', $data['province_code'])->first();
            if (! $province) {
                $this->dataImport->increment('records_failed');

                return;
            }

            $type = MunicipalityType::query()->where('slug', $data['type_slug'] ?? 'municipality')->first();
            if (! $type) {
                $this->dataImport->increment('records_failed');

                return;
            }

            $municipality = Municipality::query()->updateOrCreate(
                ['name' => $data['name'], 'province_id' => $province->id],
                [
                    'statcan_id' => $data['statcan_id'] ?? null,
                    'name_fr' => $data['name_fr'] ?? null,
                    'municipality_type_id' => $type->id,
                    'population' => $data['population'] ?? null,
                    'population_year' => $data['population_year'] ?? null,
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'area_sq_km' => $data['area_sq_km'] ?? null,
                    'timezone' => $data['timezone'] ?? $this->guessTimezone($province->code),
                    'data_source' => $data['data_source'] ?? 'manual',
                ]
            );

            if ($municipality->wasRecentlyCreated) {
                $this->dataImport->increment('records_created');
            } else {
                $this->dataImport->increment('records_updated');
            }

            $this->dataImport->increment('records_processed');
        } catch (\Exception $e) {
            Log::warning('Failed to process municipality data', ['error' => $e->getMessage()]);
            $this->dataImport->increment('records_failed');
        }
    }

    private function extractProvinceCode(array $properties): string
    {
        $pruid = $properties['PRUID'] ?? '';

        return match ($pruid) {
            '10' => 'NL',
            '11' => 'PE',
            '12' => 'NS',
            '13' => 'NB',
            '24' => 'QC',
            '35' => 'ON',
            '46' => 'MB',
            '47' => 'SK',
            '48' => 'AB',
            '59' => 'BC',
            '60' => 'YT',
            '61' => 'NT',
            '62' => 'NU',
            default => 'ON',
        };
    }

    private function determineTypeSlug(array $properties): string
    {
        $csdType = strtolower($properties['CSDTYPE'] ?? 'csd');

        return match (true) {
            str_contains($csdType, 'city') => 'city',
            str_contains($csdType, 'town') => 'town',
            str_contains($csdType, 'village') => 'village',
            str_contains($csdType, 'hamlet') => 'hamlet',
            str_contains($csdType, 'reserve') => 'reserve',
            default => 'csd',
        };
    }

    private function extractCentroid(array $geometry): array
    {
        if ($geometry['type'] === 'Point') {
            return [
                'lng' => $geometry['coordinates'][0],
                'lat' => $geometry['coordinates'][1],
            ];
        }

        return ['lat' => null, 'lng' => null];
    }

    private function guessTimezone(string $provinceCode): string
    {
        return match ($provinceCode) {
            'NL' => 'America/St_Johns',
            'PE', 'NS', 'NB' => 'America/Halifax',
            'QC', 'ON' => 'America/Toronto',
            'MB' => 'America/Winnipeg',
            'SK' => 'America/Regina',
            'AB', 'NT' => 'America/Edmonton',
            'BC', 'YT' => 'America/Vancouver',
            'NU' => 'America/Iqaluit',
            default => 'America/Toronto',
        };
    }
}

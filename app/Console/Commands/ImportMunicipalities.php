<?php

namespace App\Console\Commands;

use App\Models\DataImport;
use App\Services\MunicipalityImportService;
use Illuminate\Console\Command;

class ImportMunicipalities extends Command
{
    protected $signature = 'municipalities:import
                            {source=array : The data source (array, statcan)}
                            {--url= : URL for StatCan GeoJSON}
                            {--sample : Import sample data for testing}';

    protected $description = 'Import municipality data from various sources';

    public function handle(): int
    {
        $source = $this->argument('source');

        if ($source === 'statcan') {
            return $this->importFromStatCan();
        }

        if ($source === 'array' || $this->option('sample')) {
            return $this->importSampleData();
        }

        $this->error('Invalid source. Use "array" or "statcan".');

        return self::FAILURE;
    }

    private function importFromStatCan(): int
    {
        $url = $this->option('url');

        if (! $url) {
            $this->error('Please provide a --url for the Statistics Canada GeoJSON file.');

            return self::FAILURE;
        }

        if (! $this->confirm('This will import municipalities from Statistics Canada. Continue?')) {
            $this->info('Import cancelled.');

            return self::SUCCESS;
        }

        $this->info('Starting Statistics Canada data import...');

        $dataImport = DataImport::query()->create([
            'type' => 'statcan_csd',
            'source' => $url,
            'status' => 'pending',
        ]);

        $service = new MunicipalityImportService($dataImport);

        try {
            $service->importFromStatCan($url);

            $this->newLine();
            $this->info('Import completed successfully!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Processed', $dataImport->records_processed],
                    ['Created', $dataImport->records_created],
                    ['Updated', $dataImport->records_updated],
                    ['Failed', $dataImport->records_failed],
                ]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Import failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function importSampleData(): int
    {
        $this->info('Importing sample municipality data...');

        $sampleData = $this->getSampleMunicipalitiesData();

        $dataImport = DataImport::query()->create([
            'type' => 'sample_data',
            'source' => 'manual',
            'status' => 'pending',
        ]);

        $service = new MunicipalityImportService($dataImport);

        try {
            $service->importFromArray($sampleData);

            $this->newLine();
            $this->info('Sample data imported successfully!');
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Processed', $dataImport->records_processed],
                    ['Created', $dataImport->records_created],
                    ['Updated', $dataImport->records_updated],
                    ['Failed', $dataImport->records_failed],
                ]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Import failed: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function getSampleMunicipalitiesData(): array
    {
        return [
            [
                'name' => 'Toronto',
                'name_fr' => 'Toronto',
                'province_code' => 'ON',
                'type_slug' => 'city',
                'population' => 2794356,
                'population_year' => 2021,
                'latitude' => 43.6532,
                'longitude' => -79.3832,
                'area_sq_km' => 630.2,
                'statcan_id' => '3520005',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Montreal',
                'name_fr' => 'Montréal',
                'province_code' => 'QC',
                'type_slug' => 'city',
                'population' => 1762949,
                'population_year' => 2021,
                'latitude' => 45.5017,
                'longitude' => -73.5673,
                'area_sq_km' => 365.1,
                'statcan_id' => '2466023',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Vancouver',
                'province_code' => 'BC',
                'type_slug' => 'city',
                'population' => 662248,
                'population_year' => 2021,
                'latitude' => 49.2827,
                'longitude' => -123.1207,
                'area_sq_km' => 114.97,
                'statcan_id' => '5915022',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Calgary',
                'province_code' => 'AB',
                'type_slug' => 'city',
                'population' => 1306784,
                'population_year' => 2021,
                'latitude' => 51.0447,
                'longitude' => -114.0719,
                'area_sq_km' => 825.29,
                'statcan_id' => '4806016',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Edmonton',
                'province_code' => 'AB',
                'type_slug' => 'city',
                'population' => 1010899,
                'population_year' => 2021,
                'latitude' => 53.5461,
                'longitude' => -113.4938,
                'area_sq_km' => 765.61,
                'statcan_id' => '4811061',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Ottawa',
                'province_code' => 'ON',
                'type_slug' => 'city',
                'population' => 1017449,
                'population_year' => 2021,
                'latitude' => 45.4215,
                'longitude' => -75.6972,
                'area_sq_km' => 2790.3,
                'statcan_id' => '3506008',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Winnipeg',
                'province_code' => 'MB',
                'type_slug' => 'city',
                'population' => 749607,
                'population_year' => 2021,
                'latitude' => 49.8951,
                'longitude' => -97.1384,
                'area_sq_km' => 464.33,
                'statcan_id' => '4611040',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Quebec City',
                'name_fr' => 'Ville de Québec',
                'province_code' => 'QC',
                'type_slug' => 'city',
                'population' => 549459,
                'population_year' => 2021,
                'latitude' => 46.8139,
                'longitude' => -71.2080,
                'area_sq_km' => 485.1,
                'statcan_id' => '2423027',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Halifax',
                'province_code' => 'NS',
                'type_slug' => 'regional-municipality',
                'population' => 439819,
                'population_year' => 2021,
                'latitude' => 44.6488,
                'longitude' => -63.5752,
                'area_sq_km' => 5490.35,
                'statcan_id' => '1209034',
                'data_source' => 'manual',
            ],
            [
                'name' => 'Victoria',
                'province_code' => 'BC',
                'type_slug' => 'city',
                'population' => 91867,
                'population_year' => 2021,
                'latitude' => 48.4284,
                'longitude' => -123.3656,
                'area_sq_km' => 19.47,
                'statcan_id' => '5917034',
                'data_source' => 'manual',
            ],
        ];
    }
}

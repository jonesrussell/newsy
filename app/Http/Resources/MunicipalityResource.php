<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'statcan_id' => $this->statcan_id,
            'name' => $this->name,
            'name_fr' => $this->name_fr,
            'slug' => $this->slug,
            'population' => $this->population,
            'population_year' => $this->population_year,
            'coordinates' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'area_sq_km' => $this->area_sq_km,
            'timezone' => $this->timezone,
            'data_source' => $this->data_source,
            'verified_at' => $this->verified_at?->toISOString(),
            'municipality_type' => [
                'id' => $this->municipalityType->id,
                'slug' => $this->municipalityType->slug,
                'name' => $this->municipalityType->name,
            ],
            'province' => [
                'id' => $this->province->id,
                'code' => $this->province->code,
                'name' => $this->province->name,
            ],
            'news_sources' => NewsSourceResource::collection($this->whenLoaded('newsSources')),
            'news_sources_count' => $this->whenCounted('newsSources'),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}

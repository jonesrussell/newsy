<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'url' => $this->url,
            'type' => $this->type,
            'scope' => $this->scope,
            'language' => $this->language,
            'reliability_score' => $this->reliability_score,
            'is_active' => $this->is_active,
            'last_verified_at' => $this->last_verified_at?->toISOString(),
            'discovery_method' => $this->discovery_method,
            'metadata' => $this->metadata,
            'municipalities_count' => $this->whenCounted('municipalities'),
            'coverage_type' => $this->whenPivotLoaded('municipality_news_source', function () {
                return $this->pivot->coverage_type;
            }),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}

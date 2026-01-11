<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Municipality extends Model
{
    /** @use HasFactory<\Database\Factories\MunicipalityFactory> */
    use HasFactory;

    protected $fillable = [
        'statcan_id',
        'name',
        'name_fr',
        'slug',
        'municipality_type_id',
        'province_id',
        'population',
        'population_year',
        'latitude',
        'longitude',
        'area_sq_km',
        'timezone',
        'metadata',
        'data_source',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'population' => 'integer',
            'population_year' => 'integer',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'area_sq_km' => 'decimal:2',
            'metadata' => 'array',
            'verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Municipality $municipality) {
            if (empty($municipality->slug)) {
                $municipality->slug = Str::slug($municipality->name);
            }
        });
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function municipalityType(): BelongsTo
    {
        return $this->belongsTo(MunicipalityType::class);
    }

    public function newsSources(): BelongsToMany
    {
        return $this->belongsToMany(NewsSource::class)
            ->withPivot(['coverage_type', 'verified_at', 'notes'])
            ->withTimestamps();
    }

    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('name_fr', 'like', "%{$search}%")
                ->orWhere('statcan_id', 'like', "%{$search}%");
        });
    }

    public function scopeOfProvince($query, ?string $provinceCode)
    {
        if (empty($provinceCode)) {
            return $query;
        }

        return $query->whereHas('province', function ($q) use ($provinceCode) {
            $q->where('code', $provinceCode);
        });
    }

    public function scopeOfType($query, ?string $typeSlug)
    {
        if (empty($typeSlug)) {
            return $query;
        }

        return $query->whereHas('municipalityType', function ($q) use ($typeSlug) {
            $q->where('slug', $typeSlug);
        });
    }
}

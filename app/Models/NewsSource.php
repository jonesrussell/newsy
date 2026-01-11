<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class NewsSource extends Model
{
    /** @use HasFactory<\Database\Factories\NewsSourceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'type',
        'scope',
        'language',
        'reliability_score',
        'last_verified_at',
        'is_active',
        'metadata',
        'discovery_method',
    ];

    protected function casts(): array
    {
        return [
            'reliability_score' => 'integer',
            'last_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (NewsSource $newsSource) {
            if (empty($newsSource->slug)) {
                $newsSource->slug = Str::slug($newsSource->name);
            }
        });
    }

    public function municipalities(): BelongsToMany
    {
        return $this->belongsToMany(Municipality::class)
            ->withPivot(['coverage_type', 'verified_at', 'notes'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, ?string $type)
    {
        if (empty($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }

    public function scopeOfScope($query, ?string $scope)
    {
        if (empty($scope)) {
            return $query;
        }

        return $query->where('scope', $scope);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapeJob extends Model
{
    protected $fillable = [
        'target_url',
        'scraper_type',
        'status',
        'municipalities_processed',
        'news_sources_found',
        'started_at',
        'completed_at',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'municipalities_processed' => 'integer',
            'news_sources_found' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}

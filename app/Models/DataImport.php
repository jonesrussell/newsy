<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataImport extends Model
{
    protected $fillable = [
        'type',
        'source',
        'status',
        'records_processed',
        'records_created',
        'records_updated',
        'records_failed',
        'started_at',
        'completed_at',
        'error_log',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'records_processed' => 'integer',
            'records_created' => 'integer',
            'records_updated' => 'integer',
            'records_failed' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'error_log' => 'array',
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now(),
            'error_log' => array_merge($this->error_log ?? [], [$error]),
        ]);
    }
}

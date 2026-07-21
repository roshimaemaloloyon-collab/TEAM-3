<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'overall_score',
        'kpi_score',
        'ranking',
        'performance_status',
        'record_type',
        'notes',
        'metadata',
        'recorded_at',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'recorded_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

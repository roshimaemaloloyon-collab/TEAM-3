<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'customer_rating',
        'peer_evaluation_score',
        'attendance_rate',
        'trip_completion_rate',
        'cancellation_rate',
        'safety_score',
        'complaints_count',
        'commendations_count',
        'overall_score',
        'performance_status',
        'ranking',
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

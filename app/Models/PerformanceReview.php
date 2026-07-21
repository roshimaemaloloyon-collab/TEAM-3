<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'review_type',
        'period',
        'review_date',
        'performance_score',
        'admin_feedback',
        'recommendations',
        'status',
        'reviewer_id',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'review_date' => 'date',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

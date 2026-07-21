<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'positive_feedback',
        'improvement_areas',
        'common_strengths',
        'common_weaknesses',
        'recommendations',
        'average_peer_rating',
        'total_evaluations',
        'positive_count',
        'improvement_count',
        'feedback_period_start',
        'feedback_period_end',
    ];

    protected function casts(): array
    {
        return [
            'average_peer_rating' => 'decimal:2',
            'feedback_period_start' => 'date',
            'feedback_period_end' => 'date',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}

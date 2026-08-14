<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'training_id',
        'overall_rating',
        'knowledge_assessment',
        'instructor_feedback',
        'training_effectiveness',
        'driver_feedback',
        'recommendations',
        'remarks',
        'status',
        'evaluated_by',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}

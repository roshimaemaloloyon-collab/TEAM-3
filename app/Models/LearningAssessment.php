<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'learning_module_id',
        'score',
        'passing_score',
        'attempt',
        'max_attempts',
        'status',
        'score_breakdown',
        'feedback',
        'completed_at',
        'graded_by',
    ];

    protected function casts(): array
    {
        return [
            'score_breakdown' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class, 'learning_module_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}

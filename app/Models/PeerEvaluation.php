<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeerEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluator_id',
        'evaluated_driver_id',
        'evaluation_date',
        'is_anonymous',
        'category_scores',
        'overall_score',
        'comments',
        'suggestions',
        'status',
        'admin_remarks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'category_scores' => 'array',
            'overall_score' => 'decimal:2',
            'evaluation_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluatedDriver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_driver_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(EvaluationReview::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(EvaluationHistory::class);
    }
}

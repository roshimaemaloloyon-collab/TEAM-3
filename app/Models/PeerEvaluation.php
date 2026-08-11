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

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getAverageCategoryScore(): float
    {
        if (empty($this->category_scores)) return 0.0;
        return round(array_sum($this->category_scores) / count($this->category_scores), 2);
    }

    public function getScoreRating(): string
    {
        $score = $this->overall_score ?? 0;
        if ($score >= 90) return 'Outstanding';
        if ($score >= 75) return 'Excellent';
        if ($score >= 60) return 'Good';
        if ($score >= 45) return 'Satisfactory';
        return 'Needs Improvement';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function canBeReviewed(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    public function getCategoryScore(string $category): ?float
    {
        return $this->category_scores[$category] ?? null;
    }

    public function getTopCategory(): ?string
    {
        if (empty($this->category_scores)) return null;
        return array_keys($this->category_scores, max($this->category_scores))[0] ?? null;
    }

    public function getLowestCategory(): ?string
    {
        if (empty($this->category_scores)) return null;
        return array_keys($this->category_scores, min($this->category_scores))[0] ?? null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByDriver($query, int $driverId)
    {
        return $query->where('evaluated_driver_id', $driverId);
    }
}

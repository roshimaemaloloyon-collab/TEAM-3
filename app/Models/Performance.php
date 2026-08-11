<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function getPerformanceStatusLabel(): string
    {
        return match ($this->performance_status) {
            'excellent' => 'Excellent',
            'good' => 'Good',
            'average' => 'Average',
            'needs_improvement' => 'Needs Improvement',
            'poor' => 'Poor',
            default => ucfirst($this->performance_status),
        };
    }

    public function getPerformanceStatusColor(): string
    {
        return match ($this->performance_status) {
            'excellent' => 'green',
            'good' => 'blue',
            'average' => 'yellow',
            'needs_improvement' => 'orange',
            'poor' => 'red',
            default => 'gray',
        };
    }

    public function getOverallRating(): string
    {
        if ($this->overall_score >= 90) return 'Outstanding';
        if ($this->overall_score >= 80) return 'Excellent';
        if ($this->overall_score >= 70) return 'Good';
        if ($this->overall_score >= 60) return 'Satisfactory';
        if ($this->overall_score >= 50) return 'Needs Improvement';
        return 'Poor';
    }

    public function getOverallRatingColor(): string
    {
        if ($this->overall_score >= 90) return 'green';
        if ($this->overall_score >= 80) return 'blue';
        if ($this->overall_score >= 70) return 'yellow';
        if ($this->overall_score >= 60) return 'orange';
        return 'red';
    }

    public function getSafetyRating(): string
    {
        if ($this->safety_score >= 90) return 'Excellent';
        if ($this->safety_score >= 75) return 'Good';
        if ($this->safety_score >= 60) return 'Average';
        return 'Needs Improvement';
    }

    public function getComplaintRate(): float
    {
        $total = $this->trip_completion_rate ?? 0;
        if ($total === 0) return 0.0;
        return round(($this->complaints_count / $total) * 100, 2);
    }

    public function getCommendationsRate(): float
    {
        $total = $this->trip_completion_rate ?? 0;
        if ($total === 0) return 0.0;
        return round(($this->commendations_count / $total) * 100, 2);
    }

    public function isTopPerformer(): bool
    {
        return $this->overall_score >= 90;
    }

    public function isUnderperforming(): bool
    {
        return $this->overall_score < 60;
    }

    public function calculateOverallScore(): float
    {
        $weights = [
            'customer_rating' => 0.25,
            'peer_evaluation_score' => 0.20,
            'attendance_rate' => 0.15,
            'trip_completion_rate' => 0.20,
            'safety_score' => 0.20,
        ];

        $score = 0;
        $totalWeight = 0;

        foreach ($weights as $field => $weight) {
            $value = $this->{$field} ?? 0;
            if ($value !== null) {
                $normalized = $field === 'customer_rating' ? ($value / 5) * 100 : $value;
                $score += $normalized * $weight;
                $totalWeight += $weight;
            }
        }

        return $totalWeight > 0 ? round($score / $totalWeight, 2) : 0.0;
    }

    public function updatePerformanceStatus(): void
    {
        $score = $this->overall_score ?? 0;

        $status = match (true) {
            $score >= 90 => 'excellent',
            $score >= 75 => 'good',
            $score >= 60 => 'average',
            default => 'needs_improvement',
        };

        $this->update(['performance_status' => $status]);
    }

    public function scopeTopPerformers($query, int $limit = 10)
    {
        return $query->orderByDesc('overall_score')->limit($limit);
    }

    public function scopeNeedsImprovement($query)
    {
        return $query->where('performance_status', 'needs_improvement');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'type',
        'duration_minutes',
        'difficulty',
        'status',
        'metadata',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LearningAssignment::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(LearningAssessment::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(LearningHistory::class);
    }
}

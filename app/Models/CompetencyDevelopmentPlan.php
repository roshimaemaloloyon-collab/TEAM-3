<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetencyDevelopmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'plan_name',
        'description',
        'assigned_competencies',
        'assigned_trainings',
        'assigned_learning_modules',
        'coaching_sessions',
        'development_objectives',
        'completion_percentage',
        'target_completion_date',
        'hr_remarks',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'assigned_competencies' => 'array',
            'assigned_trainings' => 'array',
            'assigned_learning_modules' => 'array',
            'coaching_sessions' => 'array',
            'target_completion_date' => 'date',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function history(): HasMany
    {
        return $this->hasMany(CompetencyHistory::class);
    }
}

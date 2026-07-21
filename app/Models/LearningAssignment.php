<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'learning_module_id',
        'assigned_date',
        'due_date',
        'completed_date',
        'progress_percentage',
        'status',
        'notes',
        'metadata',
        'assigned_by',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'due_date' => 'date',
            'completed_date' => 'date',
            'metadata' => 'array',
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

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}

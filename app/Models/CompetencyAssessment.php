<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetencyAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'competency_id',
        'score',
        'status',
        'assessor_remarks',
        'recommendations',
        'metadata',
        'assessed_by',
        'assessed_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'assessed_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}

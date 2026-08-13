<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetencyGap extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'strengths',
        'weaknesses',
        'skill_gaps',
        'overall_gap_score',
        'assessed_at',
    ];

    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'skill_gaps' => 'array',
        'overall_gap_score' => 'float',
        'assessed_at' => 'datetime',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetencyHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'competency_id',
        'score',
        'record_type',
        'notes',
        'metadata',
        'recorded_by',
        'recorded_at',
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

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

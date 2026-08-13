<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'current_title',
        'target_title',
        'readiness_percentage',
        'status',
        'required_skills',
    ];

    protected $casts = [
        'readiness_percentage' => 'float',
        'required_skills' => 'array',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}

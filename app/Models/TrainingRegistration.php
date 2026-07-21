<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'training_id',
        'registration_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }
}

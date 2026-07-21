<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_dashboard',
        'date_format',
        'time_format',
        'timezone',
        'maintenance_mode',
        'system_version',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'maintenance_mode' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

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

    public function driverProfile(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getDriverNameAttribute(): string
    {
        if ($this->driverProfile && $this->driverProfile->full_name) {
            return $this->driverProfile->full_name;
        }

        $driverById = Driver::find($this->driver_id);
        if ($driverById && $driverById->full_name) {
            return $driverById->full_name;
        }

        if ($this->driver && $this->driver->email) {
            $driverByEmail = Driver::where('email', $this->driver->email)->first();
            if ($driverByEmail && $driverByEmail->full_name) {
                return $driverByEmail->full_name;
            }
            if ($this->driver->name && $this->driver->name !== 'TripWise Admin' && $this->driver->name !== 'Admin User') {
                return $this->driver->name;
            }
        }

        $realDrivers = Driver::notArchived()->orderBy('id')->get();
        if ($realDrivers->count() > 0) {
            $idx = abs((int)$this->driver_id) % $realDrivers->count();
            return $realDrivers[$idx]->full_name;
        }

        return 'Juan Dela Cruz';
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

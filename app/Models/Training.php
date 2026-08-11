<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\CastsAttributes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'instructor',
        'venue',
        'capacity',
        'start_datetime',
        'end_datetime',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(TrainingEvaluation::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'upcoming' => 'Upcoming',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'upcoming' => 'blue',
            'ongoing' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function isFull(): bool
    {
        return $this->registrations()->where('status', 'approved')->count() >= $this->capacity;
    }

    public function getAvailableSlots(): int
    {
        return max(0, $this->capacity - $this->registrations()->where('status', 'approved')->count());
    }

    public function getProgress(): float
    {
        if ($this->capacity === 0) return 0.0;
        return round(($this->registrations()->where('status', 'approved')->count() / $this->capacity) * 100, 2);
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing' && now()->between($this->start_datetime, $this->end_datetime);
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming' && $this->start_datetime->isFuture();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->end_datetime->isPast();
    }

    public function getDurationHours(): float
    {
        return $this->start_datetime->diffInHours($this->end_datetime);
    }

    public function getAttendanceRate(): float
    {
        $total = $this->registrations()->where('status', 'approved')->count();
        if ($total === 0) return 0.0;
        $present = $this->attendance()->where('status', 'present')->count();
        return round(($present / $total) * 100, 2);
    }

    public function getAverageRating(): ?float
    {
        return $this->evaluations()->avg('overall_rating');
    }

    public function getCertificatesIssued(): int
    {
        return $this->certificates()->where('status', 'issued')->count();
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['upcoming', 'ongoing']);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['upcoming', 'ongoing']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

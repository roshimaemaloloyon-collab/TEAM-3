<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'first_name',
        'middle_name',
        'last_name',
        'photo',
        'birth_date',
        'gender',
        'civil_status',
        'address',
        'contact_number',
        'email',
        'emergency_contact_person',
        'emergency_contact_number',
        'date_hired',
        'branch',
        'vehicle_assignment',
        'vehicle_type',
        'route_assignment',
        'status',
        'performance_score',
        'trips_count',
        'complaints_count',
        'username',
        'role',
        'license_number',
        'license_expiration',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'date_hired' => 'date',
        'license_expiration' => 'date',
        'performance_score' => 'float',
        'trips_count' => 'integer',
        'complaints_count' => 'integer',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getFormattedIdAttribute(): string
    {
        return str_starts_with($this->driver_id, '#') ? $this->driver_id : "#{$this->driver_id}";
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'review');
    }

    public function scopeNotArchived($query)
    {
        return $query->where('status', '!=', 'archived');
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'review' => 'yellow',
            'suspended' => 'red',
            'archived' => 'gray',
            default => 'gray',
        };
    }

    public function getPerformanceRating(): string
    {
        if ($this->performance_score >= 4.5) return 'Excellent';
        if ($this->performance_score >= 4.0) return 'Good';
        if ($this->performance_score >= 3.5) return 'Average';
        if ($this->performance_score >= 3.0) return 'Below Average';
        return 'Poor';
    }

    public function getPerformanceRatingColor(): string
    {
        if ($this->performance_score >= 4.5) return 'green';
        if ($this->performance_score >= 4.0) return 'blue';
        if ($this->performance_score >= 3.5) return 'yellow';
        if ($this->performance_score >= 3.0) return 'orange';
        return 'red';
    }

    public function canBeDeleted(): bool
    {
        return $this->status !== 'active' && $this->status !== 'review';
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->vehicle_assignment !== null;
    }

    public function getLicenseStatus(): string
    {
        if (!$this->license_expiration) return 'No License';
        if ($this->license_expiration->isPast()) return 'Expired';
        if ($this->license_expiration->diffInDays(now()) <= 30) return 'Expiring Soon';
        return 'Valid';
    }

    public function getTripsThisMonth(): int
    {
        return $this->trips_count;
    }

    public function getComplaintRate(): float
    {
        if ($this->trips_count === 0) return 0.0;
        return round(($this->complaints_count / $this->trips_count) * 100, 2);
    }

    public function updatePerformanceScore(): void
    {
        $avg = \App\Models\Performance::where('driver_id', $this->id)->avg('overall_score');
        if ($avg !== null) {
            $this->update(['performance_score' => round($avg, 2)]);
        }
    }

    public function incrementTrips(): void
    {
        $this->increment('trips_count');
    }

    public function incrementComplaints(): void
    {
        $this->increment('complaints_count');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'status', 'phone', 'address'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'driver' => 'Driver',
            'manager' => 'Manager',
            'supervisor' => 'Supervisor',
            default => ucfirst($this->role),
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'active' => 'green',
            'inactive' => 'gray',
            'suspended' => 'red',
            default => 'gray',
        };
    }

    public function canLogin(): bool
    {
        return $this->status === 'active';
    }

    public function getFullName(): string
    {
        return $this->name;
    }

    public function getInitials(): string
    {
        $parts = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        return $initials;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdminOrManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function lastLogin(): ?string
    {
        return \App\Models\LoginLog::where('user_id', $this->id)
            ->orderByDesc('created_at')
            ->value('created_at');
    }

    public function getLoginLogs(): HasMany
    {
        return $this->hasMany(\App\Models\LoginLog::class);
    }

    public function getActivityLogs(): HasMany
    {
        return $this->hasMany(\App\Models\UserActivityLog::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecuritySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'two_factor_enabled',
        'session_timeout',
        'max_login_attempts',
        'lockout_duration',
        'force_logout_all',
        'last_password_change',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'two_factor_enabled' => 'boolean',
            'force_logout_all' => 'boolean',
            'last_password_change' => 'datetime',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

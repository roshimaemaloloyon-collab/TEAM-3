<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'category',
        'priority',
        'status',
        'channel',
        'user_id',
        'metadata',
        'read_at',
        'archived_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'read_at' => 'datetime',
            'archived_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(NotificationHistory::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }
}

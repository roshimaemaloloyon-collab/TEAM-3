<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'module',
        'description',
        'severity',
        'metadata',
        'user_id',
        'ip_address',
        'user_agent',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'performed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

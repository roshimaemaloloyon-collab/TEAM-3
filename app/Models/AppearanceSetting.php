<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppearanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'language',
        'font_size',
        'sidebar_behavior',
        'high_contrast',
        'reduce_motion',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'high_contrast' => 'boolean',
            'reduce_motion' => 'boolean',
        ];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

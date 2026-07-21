<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_name',
        'category',
        'metric_value',
        'recorded_date',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'metric_value' => 'array',
            'recorded_date' => 'date',
        ];
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

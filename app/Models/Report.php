<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'report_type',
        'parameters',
        'report_data',
        'export_format',
        'status',
        'generated_by',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'report_data' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function exports(): HasMany
    {
        return $this->hasMany(ReportExport::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(ReportHistory::class);
    }
}

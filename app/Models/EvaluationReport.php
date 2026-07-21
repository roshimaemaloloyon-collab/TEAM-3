<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'title',
        'parameters',
        'report_data',
        'export_format',
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'export_format',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'exported_by',
        'exported_at',
    ];

    protected function casts(): array
    {
        return [
            'exported_at' => 'datetime',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function exportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgencySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_name',
        'logo_path',
        'address',
        'contact_number',
        'email',
        'description',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

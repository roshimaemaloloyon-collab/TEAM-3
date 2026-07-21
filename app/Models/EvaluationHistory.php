<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'peer_evaluation_id',
        'action',
        'changes',
        'performed_by',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'performed_at' => 'datetime',
        ];
    }

    public function peerEvaluation(): BelongsTo
    {
        return $this->belongsTo(PeerEvaluation::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}

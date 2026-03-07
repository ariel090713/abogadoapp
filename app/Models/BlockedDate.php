<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedDate extends Model
{
    protected $fillable = [
        'lawyer_profile_id',
        'blocked_date',
        'start_time',
        'end_time',
        'reason',
        'is_full_day',
    ];

    protected $casts = [
        'blocked_date' => 'date',
        'is_full_day' => 'boolean',
    ];

    public function lawyerProfile(): BelongsTo
    {
        return $this->belongsTo(LawyerProfile::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LawyerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ibp_number',
        'ibp_card_path',
        'supporting_document_path',
        'bio',
        'languages',
        'years_experience',
        'law_school',
        'law_firm',
        'graduation_year',
        'rating',
        'total_reviews',
        'total_consultations',
        'is_verified',
        'verified_at',
        'is_rejected',
        'rejection_reason',
        'rejected_at',
        'is_available',
        'auto_accept_bookings',
        'username',
        // Service pricing
        'chat_rate_15min',
        'chat_rate_30min',
        'chat_rate_60min',
        'video_rate_15min',
        'video_rate_30min',
        'video_rate_60min',
        'document_review_min_price',
        // Service availability
        'offers_chat_consultation',
        'offers_video_consultation',
        'offers_document_review',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'is_rejected' => 'boolean',
        'rejected_at' => 'datetime',
        'is_available' => 'boolean',
        'auto_accept_bookings' => 'boolean',
        'languages' => 'array',
        // Service pricing
        'chat_rate_15min' => 'decimal:2',
        'chat_rate_30min' => 'decimal:2',
        'chat_rate_60min' => 'decimal:2',
        'video_rate_15min' => 'decimal:2',
        'video_rate_30min' => 'decimal:2',
        'video_rate_60min' => 'decimal:2',
        'document_review_min_price' => 'decimal:2',
        // Service availability
        'offers_chat_consultation' => 'boolean',
        'offers_video_consultation' => 'boolean',
        'offers_document_review' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'lawyer_specializations');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function availabilitySchedules(): HasMany
    {
        return $this->hasMany(AvailabilitySchedule::class);
    }
    
    public function blockedDates(): HasMany
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function getRouteKeyName(): string
    {
        return 'username';
    }
}

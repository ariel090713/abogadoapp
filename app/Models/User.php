<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'location',
        'province',
        'city',
        'languages',
        'profile_photo',
        'onboarding_completed_at',
        'onboarding_data',
        'notification_preferences',
        'is_active',
        'last_seen_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'onboarding_completed_at' => 'datetime',
            'onboarding_data' => 'array',
            'last_seen_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_login_at' => 'datetime',
            'notification_preferences' => 'array',
            'languages' => 'array',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the lawyer profile for this user
     */
    public function lawyerProfile()
    {
        return $this->hasOne(LawyerProfile::class);
    }

    /**
     * Get the social accounts for this user
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Check if user is a lawyer
     */
    public function isLawyer(): bool
    {
        return $this->role === 'lawyer';
    }

    /**
     * Check if user is a client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has completed onboarding
     */
    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_completed_at !== null;
    }

    /**
     * Get consultations where user is the client
     */
    public function clientConsultations()
    {
        return $this->hasMany(Consultation::class, 'client_id');
    }

    /**
     * Get consultations where user is the lawyer
     */
    public function lawyerConsultations()
    {
        return $this->hasMany(Consultation::class, 'lawyer_id');
    }

    /**
     * Get all transactions for this user
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all transactions where this user is the lawyer
     */
    public function lawyerTransactions()
    {
        return $this->hasMany(Transaction::class, 'lawyer_id');
    }

    /**
     * Get all payouts for this lawyer
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class, 'lawyer_id');
    }

    /**
     * Get notification preference for a specific type and channel
     */
    public function getNotificationPreference(string $type, string $channel = 'mail'): bool
    {
        $preferences = $this->notification_preferences ?? [];
        
        // Default to true if not set
        return $preferences[$type][$channel] ?? true;
    }

    /**
     * Check if user wants email notifications for a type
     */
    public function wantsEmailNotification(string $type): bool
    {
        return $this->getNotificationPreference($type, 'mail');
    }

    /**
     * Check if user wants database notifications for a type
     */
    public function wantsDatabaseNotification(string $type): bool
    {
        return $this->getNotificationPreference($type, 'database');
    }

    /**
     * Check if user wants push notifications for a type
     */
    public function wantsPushNotification(string $type): bool
    {
        return $this->getNotificationPreference($type, 'broadcast');
    }

    /**
     * Get admin actions performed by this admin
     */
    public function adminActions()
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }

    /**
     * Check if user is currently online (active in last 5 minutes)
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at && 
               $this->last_seen_at->gt(now()->subMinutes(5));
    }

    /**
     * Update user's last seen timestamp
     */
    public function updateLastSeen(): void
    {
        $this->update(['last_seen_at' => now()]);
    }

    /**
     * Get time since user was last seen
     */
    public function lastSeenHuman(): string
    {
        if (!$this->last_seen_at) {
            return 'Never';
        }

        if ($this->isOnline()) {
            return 'Online';
        }

        return $this->last_seen_at->diffForHumans();
    }
}

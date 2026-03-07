<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'notification_preferences' => $this->getDefaultNotificationPreferences(),
        ]);

        // Auto-subscribe to newsletter
        NewsletterSubscriber::firstOrCreate(
            ['email' => $user->email],
            [
                'is_subscribed' => true,
                'token' => Str::random(32),
            ]
        );

        return $user;
    }

    /**
     * Get default notification preferences for new users
     */
    protected function getDefaultNotificationPreferences(): array
    {
        return [
            // Consultation notifications
            'consultation_requests' => [
                'mail' => true,
                'database' => true,
                'broadcast' => true,
            ],
            'consultation_updates' => [
                'mail' => true,
                'database' => true,
                'broadcast' => true,
            ],
            'consultation_reminders' => [
                'mail' => true,
                'database' => true,
                'broadcast' => true,
            ],
            
            // Payment notifications
            'payment_updates' => [
                'mail' => true,
                'database' => true,
                'broadcast' => true,
            ],
            
            // Document notifications
            'document_updates' => [
                'mail' => true,
                'database' => true,
                'broadcast' => true,
            ],
            
            // Case management notifications
            'case_updates' => [
                'mail' => true,
                'database' => true,
                'broadcast' => true,
            ],
            
            // System notifications
            'system_updates' => [
                'mail' => true,
                'database' => true,
                'broadcast' => false, // Don't push system updates
            ],
        ];
    }
}

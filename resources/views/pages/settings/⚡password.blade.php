<?php

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div>
    <x-slot name="title">Settings</x-slot>
    
    @if(auth()->user()->isClient())
        <x-slot name="sidebar">
            <x-client-sidebar />
        </x-slot>
    @elseif(auth()->user()->isLawyer())
        <x-slot name="sidebar">
            <x-lawyer-sidebar />
        </x-slot>
    @elseif(auth()->user()->isAdmin())
        {{-- Admin sidebar here if needed --}}
    @endif
    
    <x-pages.settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Current password')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('New password')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <x-button type="submit" wire:click="updatePassword" loading loadingText="Saving...">
                    Save Changes
                </x-button>

                <x-action-message class="text-sm text-green-600 font-medium" on="password-updated">
                    Password updated successfully!
                </x-action-message>
            </div>
        </form>
    </x-pages.settings.layout>
</div>

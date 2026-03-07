<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth', \App\Http\Middleware\EnsureOnboardingCompleted::class])->group(function () {
    Route::redirect('settings', '/settings/password');
});

Route::middleware(['auth', 'verified', \App\Http\Middleware\EnsureOnboardingCompleted::class])->group(function () {
    Route::livewire('settings/password', 'pages::settings.password')->name('user-password.edit');

    Route::livewire('settings/two-factor', 'pages::settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

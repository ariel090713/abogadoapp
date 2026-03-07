<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'client' => \App\Http\Middleware\EnsureUserIsClient::class,
            'lawyer' => \App\Http\Middleware\EnsureUserIsLawyer::class,
            'onboarding.redirect' => \App\Http\Middleware\RedirectIfOnboardingCompleted::class,
        ]);
        
        // Track user's last seen timestamp
        $middleware->append(\App\Http\Middleware\TrackLastSeen::class);
        
        // Ensure user is active (not suspended)
        $middleware->append(\App\Http\Middleware\EnsureUserIsActive::class);
        
        // Exclude PayMongo webhook and Livewire uploads from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'paymongo/webhook',
            'livewire*/upload-file',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

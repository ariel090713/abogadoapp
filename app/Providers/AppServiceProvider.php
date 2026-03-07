<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureNgrokHttps();
        $this->registerLoginListener();
    }

    /**
     * Register login event listener to track last login time
     */
    protected function registerLoginListener(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function ($event) {
                $event->user->update([
                    'last_login_at' => now(),
                ]);
            }
        );
    }

    /**
     * Force HTTPS when using ngrok or production
     */
    protected function configureNgrokHttps(): void
    {
        // Force HTTPS for ngrok URLs or production
        if (str_contains(config('app.url'), 'ngrok') || app()->isProduction()) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            // Force Livewire to use HTTPS
            if (class_exists(\Livewire\Livewire::class)) {
                \Livewire\Livewire::setUpdateRoute(function ($handle) {
                    return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                        ->middleware(['web']);
                });
            }
        }
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}

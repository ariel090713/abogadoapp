<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // If user hasn't completed onboarding, redirect to onboarding
        if ($user && !$user->hasCompletedOnboarding()) {
            // Don't redirect if already on onboarding routes
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.start');
            }
        }
        
        return $next($request);
    }
}

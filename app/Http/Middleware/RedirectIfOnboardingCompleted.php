<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // If user has already completed onboarding, redirect to dashboard
        if ($user && $user->hasCompletedOnboarding()) {
            // Allow access to success page if onboarding was completed recently (within 5 minutes)
            if ($request->routeIs('onboarding.success')) {
                $completedAt = $user->onboarding_completed_at;
                if ($completedAt && $completedAt->diffInMinutes(now()) <= 5) {
                    return $next($request);
                }
            }
            
            // Redirect to dashboard for all other onboarding routes
            return redirect()->route('dashboard');
        }
        
        return $next($request);
    }
}

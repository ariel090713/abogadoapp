<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackLastSeen
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $cacheKey = 'user-last-seen-' . $user->id;
            
            // Only update every 2 minutes to reduce database writes
            if (!Cache::has($cacheKey)) {
                $user->updateLastSeen();
                Cache::put($cacheKey, true, now()->addMinutes(2));
            }
        }

        return $next($request);
    }
}

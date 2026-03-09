<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isAdmin()) {
            // Redirect to appropriate dashboard based on user role
            if ($user->isLawyer()) {
                return redirect()->route('lawyer.dashboard');
            } elseif ($user->isClient()) {
                return redirect()->route('client.dashboard');
            }
            
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

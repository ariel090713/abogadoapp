<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->findOrCreateUser($googleUser, 'google');
            Auth::login($user);
            
            // Redirect to onboarding if profile incomplete
            if (!$user->hasCompletedOnboarding()) {
                return redirect()->route('onboarding.start');
            }
            
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Google login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again.');
        }
    }
    
    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    /**
     * Handle Facebook OAuth callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $user = $this->findOrCreateUser($facebookUser, 'facebook');
            Auth::login($user);
            
            if (!$user->hasCompletedOnboarding()) {
                return redirect()->route('onboarding.start');
            }
            
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Facebook login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Unable to login with Facebook. Please try again.');
        }
    }
    
    /**
     * Find or create user from social provider
     */
    private function findOrCreateUser($socialUser, string $provider): User
    {
        // Check if user exists with this social account
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();
        
        if ($socialAccount) {
            // Update tokens
            $socialAccount->update([
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]);
            
            return $socialAccount->user;
        }
        
        // Check if user exists with this email
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(), // Social login = verified email
                'password' => Hash::make(Str::random(32)), // Random password
                'role' => 'client', // Default role
            ]);
        }
        
        // Link social account
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
        ]);
        
        return $user;
    }
}

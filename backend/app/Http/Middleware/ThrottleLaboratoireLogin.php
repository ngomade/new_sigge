<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ThrottleLaboratoireLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->throttleKey($request);
        $maxAttempts = 5; // Nombre maximum de tentatives
        $decayMinutes = 15; // Durée du blocage en minutes

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            
            return back()->withErrors([
                'login' => "Trop de tentatives de connexion. Veuillez réessayer dans {$minutes} minutes."
            ])->withInput($request->only('login'));
        }

        $response = $next($request);

        // Si la connexion a échoué (redirection avec erreurs)
        if ($response->isRedirect() && session()->has('errors')) {
            RateLimiter::hit($key, $decayMinutes * 60);
        } else {
            // Si la connexion a réussi, réinitialiser le compteur
            RateLimiter::clear($key);
        }

        return $response;
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->input('login')) . '|' . $request->ip();
    }
}
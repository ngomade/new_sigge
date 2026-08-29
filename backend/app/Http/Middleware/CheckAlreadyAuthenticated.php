<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckAlreadyAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {
            $personalAccessToken = PersonalAccessToken::findToken($token);

            if ($personalAccessToken && $personalAccessToken->expires_at && $personalAccessToken->expires_at->isFuture()) {
                return response()->json([
                    'message' => 'Vous êtes déjà connecté',
                    'token' => $token,
                ], 401);
            }
        }

        return $next($request);
    }
}

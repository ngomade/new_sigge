<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\concours\Compte;
use App\Models\concours\Personnel;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService extends Controller
{
    /**
     * Generate an authentication token for the given user.
     *
     * @param Compte|Personnel $compte
     * @return JsonResponse
     */
    public function generateTokenFromUser(Compte|Personnel $compte): JsonResponse
    {
        $expiration = now()->addDay();
        $token = $compte->createToken('auth_token', ['*'], $expiration)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_type' => $compte instanceof Compte ? 'candidat' : 'admin',
            'user' => $compte,
            'user_info' => $compte instanceof Compte
                ? 'Informations supplémentaires pour le candidat'
                : 'Informations supplémentaires pour l\'administrateur',
        ]);
    }

    /**
     * Retrieve user information from a personal access token.
     * @param PersonalAccessToken $token
     * @return JsonResponse
     */
    public function getUserFromToken(PersonalAccessToken $token): JsonResponse
    {
        $user = $token->tokenable;

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        return response()->json([
            'user' => $user,
            'type' => $user instanceof Compte ? 'candidat' : 'admin'
        ]);

    }
}

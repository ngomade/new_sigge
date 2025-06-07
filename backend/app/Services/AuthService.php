<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\concours\Candidat;
use App\Models\concours\Compte;
use App\Models\Personnel;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService extends Controller
{
    /**
     * Generate an authentication token for the given user.
     *
     * @param Compte|Personnel $compte
     * @param bool $showCompte
     * @return JsonResponse
     */
    public function generateTokenFromUser(Compte|Personnel $compte, bool $showCompte = false): JsonResponse
    {
        $expiration = now()->addDay();
        $token = $compte->createToken('auth_token', ['*'], $expiration)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'compte' => $showCompte ? $compte : null,
            'user_type' => $compte instanceof Compte ? 'candidat' : 'admin',
            'user' => $compte,
            'candidat' => $compte instanceof Compte ? Candidat::where("ca_num_recu", $compte->ca_num_recu)->first() : null,
            'user_info' => $compte instanceof Compte
                ? 'Informations supplémentaires pour le candidat'
                : 'Informations supplémentaires pour l\'administrateur',
        ]);
    }

    /**
     * Retrieve user information from a personal access token.
     * @param PersonalAccessToken $token
     * @param string $rawToken
     * @return JsonResponse
     */
    public function getUserFromToken(PersonalAccessToken $token, string $rawToken): JsonResponse
    {
        $user = $token->tokenable;

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        return response()->json([
            "access_token" => $rawToken,
            "token_type" => "Bearer",
            'user' => $user,
            'candidat' =>  $user instanceof Compte ? Candidat::where("ca_num_recu", $user->ca_num_recu)->first() : null,
            'type' => $user instanceof Compte ? 'candidat' : 'admin'
        ]);

    }
}

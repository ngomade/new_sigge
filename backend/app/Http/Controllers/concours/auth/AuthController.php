<?php

namespace App\Http\Controllers\concours\auth;

use App\Http\Controllers\Controller;
use App\Models\concours\Compte;
use App\Models\Personnel;
use App\Services\AuthService;
use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
{
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentative de connexion pour l'utilisateur
        $candidat = Compte::where('ca_num_recu', $request->login)->first();
        if ($candidat && Hash::check($request->password, $candidat->ca_pwd)) {
            return $this->authService->generateTokenFromUser($candidat);
        }

        // Tentative de connexion pour l'admin
        $admin = Personnel::where('login_pers', $request->login)->first();
        if ($admin && Hash::check($request->password, $admin->pwd_pers)) {
            return $this->authService->generateTokenFromUser($admin);
        }

        return response()->json(['errors' => 'Information de connexion incorrect.'], 400);
    }

    public function logout(Request $request)
    {
        try {
            // Récupérer le token actuel
            $token = $request->user()->currentAccessToken();

            // Supprimer le token
            if ($token instanceof PersonalAccessToken) {
                $token->delete();
            }

            return response()->json([
                'message' => 'Déconnexion réussie.',
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            Log::error('Error in logout: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la déconnexion.',
            ], 500);
        }
    }

    /**
     * Vérifie la validité du token d'accès envoyé dans l'en-tête Authorization.
     *
     * Elle récupère le token d'accès personnel à partir du bearer token de la requête,
     * vérifie s'il existe et s'il n'est pas expiré. Si le token est invalide ou expiré, elle retourne
     * une réponse JSON avec un message d'erreur et le code 401. Si le token est valide, elle retourne
     * les informations de l'utilisateur associé via le service d'authentification.
     *
     * @param Request $request La requête HTTP contenant le bearer token.
     * @return JsonResponse Réponse JSON contenant les informations de l'utilisateur ou un message d'erreur.
     */
    public function checkToken(Request $request)
    {
        try {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if (!$token || $token->expires_at->isPast())
                return response()->json([
                    'message' => 'Session invalide ou expiré.'
                ], 401);

            return $this->authService->getUserFromToken($token, $request->bearerToken());
        } catch (Exception $e) {
            Log::error('Error in checkToken: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la verification du token.',
            ], 500);
        }
    }

    // Rafraîchir le token d'authentification (exemple pour Sanctum)
    public function refresh(Request $request)
    {
        try {
            // Révoquer l'ancien token
            $token = $request->user()->currentAccessToken();
            if ($token instanceof PersonalAccessToken) $token->delete();
            $newToken = $request->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'Bearer',
                'message' => 'Token rafraîchi avec succès.'
            ]);
        } catch (Exception $e) {
            Log::error('Error in refresh: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors du rafraîchissement du token.'
            ], 500);
        }
    }
}

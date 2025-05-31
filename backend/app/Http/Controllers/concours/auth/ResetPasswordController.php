<?php

namespace App\Http\Controllers\concours\auth;

use App\Http\Controllers\Controller;
use App\Models\concours\Compte;
use App\Models\concours\Personnel;
use App\Notifications\ResetPwdCompteUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * Envoie un email de réinitialisation de mot de passe
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        try {

            $user = Compte::where('ca_num_recu', $request->login)->first() ?: Personnel::where('login_pers', $request->login)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Aucun compte trouvé avec cet identifiant.'
                ], 404);
            }
            // Générer un code à 5 chiffres
            $code = (string) random_int(10000, 99999);
            $user->reset_token = $code;
            $user->reset_token_expires_at = now()->addHours(24);
            $user->save();

            $user->notify(new ResetPwdCompteUser($code));

            return response()->json([
                'message' => 'Un email de réinitialisation a été envoyé à votre adresse email.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'envoi de l\'email de réinitialisation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réinitialise le mot de passe avec le token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:3',
        ]);

        try {

            $user = Compte::where('reset_token', $request->token)
                ->where('reset_token_expires_at', '>', now())
                ->first()
                ?:
                Personnel::where('reset_token', $request->token)
                    ->where('reset_token_expires_at', '>', now())
                    ->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Token invalide ou expiré.'
                ], 400);
            }

            // Mettre à jour le mot de passe
            ($user instanceof Compte)
                ? $user->ca_pwd = bcrypt($request->password)
                : $user->pwd_pers = bcrypt($request->password);

            $user->reset_token = null;
            $user->reset_token_expires_at = null;
            if (!$user->hasVerifiedEmail()) {
                $user->email_verified_at = now();
            }
            $user->save();

            return response()->json([
                'message' => 'Votre mot de passe a été réinitialisé avec succès.'
            ]);
        } catch (Exception) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la réinitialisation du mot de passe.'
            ], 500);
        }
    }
}

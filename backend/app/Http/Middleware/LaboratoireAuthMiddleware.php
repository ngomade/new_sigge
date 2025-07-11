<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\laboratoires\LaboratoirePersLab;

class LaboratoireAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté au laboratoire
        if (!session('user_id') || !session('laboratoire_code')) {
            return redirect()->route('laboratoires.login.form', $request->route('code_lab'))
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier que l'utilisateur est connecté au bon laboratoire
        $code_lab = $request->route('code_lab');
        if (session('laboratoire_code') !== $code_lab) {
            return redirect()->route('laboratoires.show', $code_lab)
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à ce laboratoire.');
        }

        // Vérifier que l'affectation est toujours valide (dates et statut)
$userType = session('user_type');

$query = LaboratoirePersLab::where('code_lab', $code_lab)
    ->where('statut', 'actif');

if ($userType === 'externe') {
    $query->where('id_user_externe', session('user_id'));
} else {
    $query->where('id_pers_lab', session('user_id'));
}

$affectation = $query->first();

        if (!$affectation) {
            // Nettoyer la session et rediriger
            session()->forget(['user_id', 'user_name', 'user_type', 'laboratoire_code']);
            return redirect()->route('laboratoires.show', $code_lab)
                ->with('error', 'Votre affectation au laboratoire n\'est plus valide.');
        }

        // Vérifier que la date actuelle est dans la période d'affectation
        $now = now();
        if ($affectation->date_affectation > $now) {
            session()->forget(['user_id', 'user_name', 'user_type', 'laboratoire_code']);
            return redirect()->route('laboratoires.show', $code_lab)
                ->with('error', 'Votre affectation au laboratoire n\'a pas encore commencé.');
        }

        if ($affectation->date_fin_affectation && $affectation->date_fin_affectation < $now) {
            session()->forget(['user_id', 'user_name', 'user_type', 'laboratoire_code']);
            return redirect()->route('laboratoires.show', $code_lab)
                ->with('error', 'Votre affectation au laboratoire a expiré.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\laboratoires\LaboratoirePersLab;

class LaboratoireMembreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $code_lab = $request->route('code_lab');
        $userId = session('user_id');
        $userType = session('user_type');

        if (!$code_lab || !$userId || !$userType) {
            return redirect()->route('laboratoires.show', $code_lab ?? 'default')
                ->with('error', 'Accès non autorisé.');
        }

        // Vérifier l'appartenance au laboratoire
        $query = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->where('date_affectation', '<=', now())
            ->where(function($q) {
                $q->whereNull('date_fin_affectation')
                  ->orWhere('date_fin_affectation', '>=', now());
            });

        if ($userType === 'externe') {
            $query->where('id_user_externe', $userId);
        } else {
            $query->where('id_pers_lab', $userId);
        }

        $affectation = $query->first();

        if (!$affectation) {
            return redirect()->route('laboratoires.show', $code_lab)
                ->with('error', 'Vous n\'êtes pas membre actif de ce laboratoire.');
        }

        // Ajouter l'affectation à la requête pour utilisation ultérieure
        $request->attributes->set('affectation', $affectation);

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\Laboratoire;

class LaboratoireAdminMiddleware
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

        // Vérifier si l'utilisateur est admin du laboratoire
        $isAdmin = false;

        // Vérifier si c'est l'admin principal du laboratoire
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->first();
        if ($laboratoire && $laboratoire->admin_pers_labo === $userId) {
            $isAdmin = true;
        }

        // Sinon, vérifier le rôle dans l'affectation
        if (!$isAdmin) {
            $query = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('statut', 'actif')
                ->with('roleLabo');

            if ($userType === 'externe') {
                $query->where('id_user_externe', $userId);
            } else {
                $query->where('id_pers_lab', $userId);
            }

            $affectation = $query->first();

            if ($affectation && $affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                $isAdmin = true;
            }
        }

        if (!$isAdmin) {
            return redirect()->route('laboratoires.espace.membre', $code_lab)
                ->with('error', 'Vous devez être administrateur du laboratoire pour accéder à cette page.');
        }

        return $next($request);
    }
}

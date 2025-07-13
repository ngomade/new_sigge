<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\laboratoires\LaboratoirePersLab;

class LaboratoirePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $userId = session('user_id');
        $userType = session('user_type');
        $code_lab = $request->route('code_lab');

        // Récupérer l'affectation
        $affectation = $request->attributes->get('affectation');

        if (!$affectation) {
            $query = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('statut', 'actif')
                ->with('roleLabo');

            if ($userType === 'externe') {
                $query->where('id_user_externe', $userId);
            } else {
                $query->where('id_pers_lab', $userId);
            }

            $affectation = $query->first();
        }

        if (!$affectation || !$affectation->roleLabo) {
            if (!$code_lab) {
                return redirect()->route('labo.laboratoires.index')
                    ->with('error', 'Vous n\'avez pas les permissions nécessaires.');
            }
            return redirect()->route('laboratoires.espace.membre', ['code_lab' => $code_lab])
                ->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }

        // Définir les permissions par rôle
        $rolePermissions = [
            'admin' => ['*'], // Toutes les permissions
            'chef_projet' => [
                'projets.view',
                'projets.create',
                'projets.edit',
                'projets.participants',
                'projets.documents',
                'equipements.view',
                'equipements.reserve',
                'publications.view',
                'publications.create',
                'publications.edit',
            ],
            'technicien' => [
                'equipements.view',
                'equipements.reserve',
                'equipements.entretenir',
                'projets.view',
                'publications.view',
            ],
            'membre' => [
                'projets.view',
                'equipements.view',
                'equipements.reserve',
                'publications.view',
                'publications.create',
            ],
            'externe' => [
                'projets.view',
                'equipements.view',
                'publications.view',
            ],
        ];

        $userRole = strtolower($affectation->roleLabo->lib_rl);
        $userPermissions = $rolePermissions[$userRole] ?? [];

        // Vérifier si l'utilisateur a la permission
        if (!in_array('*', $userPermissions) && !in_array($permission, $userPermissions)) {
            if (!$code_lab) {
                return redirect()->route('labo.laboratoires.index')
                    ->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
            }
            return redirect()->route('laboratoires.espace.membre', ['code_lab' => $code_lab])
                ->with('error', 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }

        return $next($request);
    }
}

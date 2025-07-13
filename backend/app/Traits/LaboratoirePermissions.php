<?php

namespace App\Traits;

use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\Laboratoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait LaboratoirePermissions
{
    /**
     * Vérifier si l'utilisateur actuel a une permission spécifique
     */
    protected function hasPermission($permission, $code_lab = null): bool
    {
        $userId = session('user_id');
        $userType = session('user_type');

        if (!$userId || !$userType) {
            return false;
        }

        // Si pas de code_lab fourni, essayer de le récupérer de la session
        if (!$code_lab) {
            $code_lab = session('laboratoire_code');
        }

        if (!$code_lab) {
            return false;
        }

        // Récupérer l'affectation
        $query = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with('roleLabo');

        if ($userType === 'externe') {
            $query->where('id_user_externe', $userId);
        } else {
            $query->where('id_pers_lab', $userId);
        }

        $affectation = $query->first();

        if (!$affectation || !$affectation->roleLabo) {
            return false;
        }

        // Définir les permissions par rôle
        $rolePermissions = $this->getRolePermissions();
        $userRole = strtolower($affectation->roleLabo->lib_rl);
        $userPermissions = $rolePermissions[$userRole] ?? [];

        // Vérifier la permission
        return in_array('*', $userPermissions) || in_array($permission, $userPermissions);
    }

    /**
     * Vérifier si l'utilisateur est admin du laboratoire
     */
    protected function isLabAdmin($code_lab = null): bool
    {
        $userId = session('user_id');
        $userType = session('user_type');

        if (!$userId || !$userType) {
            return false;
        }

        if (!$code_lab) {
            $code_lab = session('laboratoire_code');
        }

        // Vérifier si c'est l'admin principal
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->first();
        if ($laboratoire && $laboratoire->admin_pers_labo === $userId) {
            return true;
        }

        // Vérifier le rôle admin
        $query = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with('roleLabo');

        if ($userType === 'externe') {
            $query->where('id_user_externe', $userId);
        } else {
            $query->where('id_pers_lab', $userId);
        }

        $affectation = $query->first();

        return $affectation &&
               $affectation->roleLabo &&
               strtolower($affectation->roleLabo->lib_rl) === 'admin';
    }

    /**
     * Obtenir l'affectation courante de l'utilisateur
     */
    protected function getCurrentAffectation($code_lab = null)
    {
        $userId = session('user_id');
        $userType = session('user_type');

        if (!$userId || !$userType) {
            return null;
        }

        if (!$code_lab) {
            $code_lab = session('laboratoire_code');
        }

        $query = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('statut', 'actif')
            ->with(['roleLabo', 'persLab', 'userExterne', 'laboratoire']);

        if ($userType === 'externe') {
            $query->where('id_user_externe', $userId);
        } else {
            $query->where('id_pers_lab', $userId);
        }

        return $query->first();
    }

    /**
     * Obtenir les permissions d'un utilisateur
     */
    protected function getUserPermissions($code_lab = null): array
    {
        $affectation = $this->getCurrentAffectation($code_lab);

        if (!$affectation || !$affectation->roleLabo) {
            return [];
        }

        $rolePermissions = $this->getRolePermissions();
        $userRole = strtolower($affectation->roleLabo->lib_rl);

        return $rolePermissions[$userRole] ?? [];
    }

    /**
     * Définition des permissions par rôle
     */
    protected function getRolePermissions(): array
    {
        return [
            'admin' => ['*'],
            'chef_projet' => [
                'projets.view', 'projets.create', 'projets.edit', 'projets.delete',
                'projets.participants', 'projets.documents',
                'equipements.view', 'equipements.reserve', 'equipements.cancel_reservation',
                'publications.view', 'publications.create', 'publications.edit',
                'membres.view',
                'dashboard.view', 'dashboard.stats',
            ],
            'chercheur' => [
                'projets.view',
                'equipements.view', 'equipements.reserve',
                'publications.view', 'publications.create',
                'membres.view',
                'dashboard.view',
            ],
            'technicien' => [
                'projets.view',
                'equipements.view', 'equipements.create', 'equipements.edit', 'equipements.delete',
                'equipements.maintenance', 'reservations.view', 'reservations.manage',
                'dashboard.view', 'dashboard.stats',
            ],
            'secretaire' => [
                'membres.view', 'membres.create', 'membres.edit',
                'candidatures.view', 'candidatures.process',
                'documents.view', 'documents.manage',
                'dashboard.view', 'dashboard.stats',
            ],
            'membre' => [
                'projets.view',
                'equipements.view',
                'publications.view', 'publications.create',
                'membres.view',
                'dashboard.view',
            ],
        ];
    }

    /**
     * Vérifier plusieurs permissions (au moins une doit être vraie)
     */
    protected function hasAnyPermission(array $permissions, $code_lab = null): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission, $code_lab)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier plusieurs permissions (toutes doivent être vraies)
     */
    protected function hasAllPermissions(array $permissions, $code_lab = null): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission, $code_lab)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Autoriser l'action ou lever une exception 403
     */
    protected function authorizeAction($permission, $code_lab = null): void
    {
        if (!$this->hasPermission($permission, $code_lab)) {
            abort(403, 'Vous n\'avez pas la permission d\'effectuer cette action.');
        }
    }

    /**
     * Logger une tentative d'accès non autorisé
     */
    protected function logUnauthorizedAccess($action, Request $request): void
    {
        Log::warning('Tentative d\'accès non autorisé', [
            'user_id' => session('user_id'),
            'user_type' => session('user_type'),
            'laboratoire' => session('laboratoire_code'),
            'action' => $action,
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String()
        ]);
    }
}

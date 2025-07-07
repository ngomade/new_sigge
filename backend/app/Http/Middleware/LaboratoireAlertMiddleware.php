<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LaboratoireAlertService;
use Illuminate\Support\Facades\Log;

class LaboratoireAlertMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si c'est une route de dashboard de laboratoire
        if ($request->route() && str_contains($request->route()->getName(), 'laboratoires.admin.dashboard')) {
            $codeLab = $request->route('code_lab');

            if ($codeLab) {
                // Exécuter les vérifications d'alertes en arrière-plan
                try {
                    $alertService = new LaboratoireAlertService();
                    $alertService->runAllChecks();
                } catch (\Exception $e) {
                    // Log l'erreur mais ne pas interrompre la requête
                    Log::error('Erreur lors de la vérification des alertes: ' . $e->getMessage());
                }
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return $next($request);
    }
}

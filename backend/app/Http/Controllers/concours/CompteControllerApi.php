<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\Compte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class CompteControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comptes = Compte::with('candidat')->get();
        return response()->json($comptes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'ca_num_recu' => 'required|string|unique:compte,ca_num_recu',
            'ca_code' => 'nullable|string|exists:candidat,ca_code',
            'ca_pwd' => 'required|string|min:6',
            'ca_recu' => 'required|string|max:255',
            'ca_nom' => 'required|string|max:255',
            'ca_email' => 'nullable|email|max:255',
            'ca_prenom' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            // Hasher le mot de passe
            $validateData['ca_pwd'] = Hash::make($validateData['ca_pwd']);
            
            $compte = Compte::create($validateData);
            DB::commit();
            
            // Ne pas retourner le mot de passe hashé
            $compte->makeHidden('ca_pwd');
            
            return response()->json($compte, 201);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la création du compte: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ca_num_recu)
    {
        $compte = Compte::with('candidat')->find($ca_num_recu);
        
        if (!$compte) {
            return response()->json(['erreur' => 'Compte non trouvé'], 404);
        }
        
        // Ne pas retourner le mot de passe
        $compte->makeHidden('ca_pwd');
        
        return response()->json($compte, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ca_num_recu)
    {
        $validateData = $request->validate([
            'ca_code' => 'nullable|string|exists:candidat,ca_code',
            'ca_pwd' => 'sometimes|required|string|min:6',
            'ca_recu' => 'sometimes|required|string|max:255',
            'ca_nom' => 'sometimes|required|string|max:255',
            'ca_email' => 'nullable|email|max:255',
            'ca_prenom' => 'sometimes|required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $compte = Compte::findOrFail($ca_num_recu);
            
            // Hasher le mot de passe si fourni
            if (isset($validateData['ca_pwd'])) {
                $validateData['ca_pwd'] = Hash::make($validateData['ca_pwd']);
            }
            
            $compte->update($validateData);
            DB::commit();
            
            // Ne pas retourner le mot de passe
            $compte->makeHidden('ca_pwd');
            
            return response()->json($compte, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du compte: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ca_num_recu)
    {
        try {
            DB::beginTransaction();
            $compte = Compte::findOrFail($ca_num_recu);
            $compte->delete();
            DB::commit();
            return response()->json(['succes' => 'Compte supprimé avec succès'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors de la suppression du compte: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Authentifier un utilisateur
     */
    public function login(Request $request)
    {
        $validateData = $request->validate([
            'ca_num_recu' => 'required|string',
            'ca_pwd' => 'required|string',
        ]);

        try {
            $compte = Compte::where('ca_num_recu', $validateData['ca_num_recu'])->first();
            
            if (!$compte || !Hash::check($validateData['ca_pwd'], $compte->ca_pwd)) {
                return response()->json(['erreur' => 'Identifiants invalides'], 401);
            }
            
            // Charger les relations
            $compte->load('candidat');
            
            // Ne pas retourner le mot de passe
            $compte->makeHidden('ca_pwd');
            
            return response()->json([
                'message' => 'Connexion réussie',
                'compte' => $compte
            ], 200);
            
        } catch (Throwable $th) {
            return response()->json(['erreur' => 'Erreur lors de la connexion: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request, string $ca_num_recu)
    {
        $validateData = $request->validate([
            'ancien_pwd' => 'required|string',
            'nouveau_pwd' => 'required|string|min:6|confirmed',
        ]);

        try {
            DB::beginTransaction();
            $compte = Compte::findOrFail($ca_num_recu);
            
            // Vérifier l'ancien mot de passe
            if (!Hash::check($validateData['ancien_pwd'], $compte->ca_pwd)) {
                return response()->json(['erreur' => 'Ancien mot de passe incorrect'], 400);
            }
            
            // Mettre à jour avec le nouveau mot de passe
            $compte->ca_pwd = Hash::make($validateData['nouveau_pwd']);
            $compte->save();
            
            DB::commit();
            
            return response()->json(['succes' => 'Mot de passe modifié avec succès'], 200);
            
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'Erreur lors du changement de mot de passe: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Récupérer les comptes par candidat
     */
    public function byCandidat(string $ca_code)
    {
        $comptes = Compte::where('ca_code', $ca_code)->get();
        
        // Ne pas retourner les mots de passe
        $comptes->makeHidden(['ca_pwd']);
        
        return response()->json($comptes, 200);
    }
}
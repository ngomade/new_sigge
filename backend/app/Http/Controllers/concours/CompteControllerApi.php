<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\concours\SendinfoOfConnection;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Models\concours\Compte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CompteControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comptes = Compte::with('candidat')->get();
        return response()->json($comptes);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request, AuthService $authService)
    {
        $validateData = $request->validate([
            'ca_num_recu' => 'required|string|unique:compte,ca_num_recu',
            'ca_code' => 'nullable|string|exists:candidat,ca_code',
            'ca_pwd' => 'required|string|min:3',
            'ca_recu' => 'required|file|max:2048|mimes:pdf,jpg,jpeg,png', // Limite de 2 Mo pour le fichier
            'ca_nom' => 'required|string|max:255',
            'ca_email' => 'nullable|email|max:255',
            'ca_prenom' => 'required|string|max:255',
        ]);
        // Hasher le mot de passe
        $validateData['ca_pwd'] = Hash::make($validateData['ca_pwd']);

        try {
            DB::beginTransaction();

            $validateData['ca_recu'] = $this->storageRecu($request->file('ca_recu'), $validateData['ca_nom'], $validateData['ca_prenom']);

            $compte = Compte::create($validateData);
            try {
                $compte->notify(new SendinfoOfConnection());
            } catch (Throwable $th) {
                Log::error('Error sending connection info notification: ' . $th->getMessage());
                return response()->json(['erreur' => 'Erreur lors de l\'envoi de l\'email'], 500);
            }
            User::create([
                'name' => $compte->ca_nom,
                'email' => $compte->ca_email,
                'password' => $compte->ca_pwd,
                'usertype' => 'candidat',
            ]);
            DB::commit();

            return $authService->generateTokenFromUser($compte);

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating compte: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la création du compte'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ca_num_recu)
    {
        $compte = Compte::with('candidat')->findOrFail($ca_num_recu);

        return response()->json($compte);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, string $ca_num_recu)
    {
        $validateData = $request->validate([
            'ca_code' => 'nullable|string|exists:candidat,ca_code',
            'ca_pwd' => 'sometimes|string|min:3',
            'ca_recu' => 'sometimes|file|max:2048|mimes:pdf,jpg,jpeg,png', // Limite de 2 Mo pour le fichier
            'ca_nom' => 'sometimes|required|string|max:255',
            'ca_email' => 'nullable|email|max:255',
            'ca_prenom' => 'sometimes|string|max:255',
        ]);
        if ($request->has("ca_pwd")) {
            $validateData['ca_pwd'] = Hash::make($validateData['ca_pwd']);
        }

        $compte = Compte::findOrFail($ca_num_recu);
        try {
            DB::beginTransaction();

            if ($request->hasFile('ca_recu')) {
                Storage::delete($compte->ca_recu); // Supprimer l'ancien fichier
                $validateData['ca_recu'] = $this->storageRecu($request->file('ca_recu'), $compte->ca_nom, $compte->ca_prenom);
            }

            $compte->update($validateData);

            // Mettre à jour l'utilisateur associé
            $user = User::where('email', $compte->ca_email)->first();
            $user?->update([
                'name' => $compte->ca_nom,
                'email' => $compte->ca_email,
                'password' => $compte->ca_pwd,
            ]);

            DB::commit();

            return response()->json($compte);
        } catch (Throwable $th) {
            DB::rollback();
            Log::error('Error updating compte: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du compte'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(string $ca_num_recu)
    {
        $compte = Compte::findOrFail($ca_num_recu);
        try {
            DB::beginTransaction();
            $compte->candidat()->delete();
            $compte->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollback();
            Log::error('Error deleting compte: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la suppression du compte'], 500);
        }
    }

    public function showRecu($ca_num_recu)
    {
        $recu = Compte::findOrFail($ca_num_recu)->ca_recu;

        if (!Storage::exists($recu)) {
            return response()->json(['erreur' => 'Reçu non trouvé'], 404);
        }
        return Storage::download($recu);
    }

    public function statsCompte()
    {
        return response()->json([
            'total' => Compte::count(),
            'comptes' => Compte::with('candidat')->get(),
        ]);
    }

    /**
     * Récupérer les comptes par candidat
     */
    public function byCandidat(string $ca_code)
    {
        $comptes = Compte::where('ca_code', $ca_code)->with('candidat')->get();

        return response()->json($comptes);
    }

    private function storageRecu($file, $nom, $prenom)
    {
        $filename = $nom . '_' . $prenom . '_' . now()->format('M_d_H_i_s');

        return $file->storeAs("private/" . getdate()['year'], $filename);
    }
}

<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Compte;
use App\Models\concours\User;
use App\Notifications\concours\SendinfoOfConnection;
use App\Services\AuthService;
use App\Services\ReceiptOCRService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class CompteControllerApi extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['store', 'extractReceiptData']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comptes = Compte::with('candidat')->get();
        return response()->json($comptes);
    }

    /**
     * Extraire les données du reçu avec OCR
     */
    public function extractReceiptData(Request $request, ReceiptOCRService $ocrService)
    {
        try {
            $request->validate([
                'receipt' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png'
            ]);

            $result = $ocrService->extractDataFromReceipt($request->file('receipt'));

            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation échouée',
                'errors' => $e->errors(),
                'data' => []
            ], 422);
        } catch (Exception $e) {
            Log::error('Erreur extraction OCR: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'extraction des données: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request, AuthService $authService, ReceiptOCRService $ocrService)
    {
        try {
            $validateData = $request->validate([
                'ca_num_recu' => 'required|string|unique:compte,ca_num_recu',
                'ca_code' => 'nullable|string|exists:candidat,ca_code',
                'ca_pwd' => 'required|string|min:8',
                'ca_recu' => 'required|file|max:4048|mimes:pdf,jpg,png,jpeg',
                'ca_nom' => 'required|string|max:255',
                'ca_email' => 'nullable|email|max:255',
                'ca_prenom' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'erreur' => 'Données de validation incorrectes',
                'errors' => $e->errors()
            ], 422);
        }

        // Hasher le mot de passe
        $validateData['ca_pwd'] = Hash::make($validateData['ca_pwd']);

        try {
            DB::beginTransaction();

            // Gérer le fichier reçu
            $file = $request->file('ca_recu');
            $extension = $file->getClientOriginalExtension();

            // Si c'est une image, la convertir en PDF
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                // Stocker temporairement l'image
                $tempPath = $file->store('temp');
                $fullTempPath = Storage::path($tempPath);

                // Convertir en PDF
                $pdfPath = $ocrService->convertImageToPdf($fullTempPath);

                // Créer un nouveau fichier uploadé depuis le PDF
                $pdfFile = new File($pdfPath);
                $filename = $validateData['ca_nom'] . '-' . $validateData['ca_prenom'] . '-' . now()->format('M_d_H_i') . '.pdf';
                $storedPath = Storage::putFileAs(getdate()['year'], $pdfFile, $filename);

                // Nettoyer les fichiers temporaires
                Storage::delete($tempPath);
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }

                $validateData['ca_recu'] = $storedPath;
            } else {
                // Si c'est déjà un PDF, le stocker normalement
                $validateData['ca_recu'] = $this->storageRecu($file, $validateData['ca_nom'], $validateData['ca_prenom'], 'pdf');
            }

            $compte = Compte::create($validateData);

            // Créer l'utilisateur associé
            if ($compte->ca_email) {
                User::create([
                    'name' => $compte->ca_nom . ' ' . $compte->ca_prenom,
                    'email' => $compte->ca_email,
                    'password' => $compte->ca_pwd,
                    'usertype' => 'candidat',
                ]);
            }

            // Envoyer la notification
            try {
                $compte->notify(new SendinfoOfConnection());
            } catch (Throwable $th) {
                Log::warning('Erreur lors de l\'envoi de l\'email de connexion: ' . $th->getMessage());
                // Ne pas faire échouer la création du compte pour un problème d'email
            }

            DB::commit();

            return $authService->generateTokenFromUser($compte, true);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Erreur lors de la création du compte: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la création du compte: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ca_num_recu)
    {
        try {
            $compte = Compte::with('candidat')->findOrFail($ca_num_recu);
            return response()->json($compte);
        } catch (ModelNotFoundException $e) {
            return response()->json(['erreur' => 'Compte non trouvé'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, string $ca_num_recu)
    {
        try {
            $validateData = $request->validate([
                'ca_code' => 'nullable|string|exists:candidat,ca_code',
                'ca_pwd' => 'sometimes|string|min:8',
                'ca_recu' => 'sometimes|file|max:2048|mimes:pdf,jpg,jpeg,png',
                'ca_nom' => 'sometimes|required|string|max:255',
                'ca_email' => 'nullable|email|max:255',
                'ca_prenom' => 'sometimes|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'erreur' => 'Données de validation incorrectes',
                'errors' => $e->errors()
            ], 422);
        }

        if ($request->has("ca_pwd")) {
            $validateData['ca_pwd'] = Hash::make($validateData['ca_pwd']);
        }

        try {
            $compte = Compte::findOrFail($ca_num_recu);

            DB::beginTransaction();

            if ($request->hasFile('ca_recu')) {
                // Supprimer l'ancien fichier s'il existe
                if ($compte->ca_recu && Storage::exists($compte->ca_recu)) {
                    Storage::delete($compte->ca_recu);
                }
                $validateData['ca_recu'] = $this->storageRecu(
                    $request->file('ca_recu'),
                    $compte->ca_nom,
                    $compte->ca_prenom,
                    $request->file('ca_recu')->getClientOriginalExtension()
                );
            }

            $compte->update($validateData);

            // Mettre à jour l'utilisateur associé
            if ($compte->ca_email) {
                $user = User::where('email', $compte->ca_email)->first();
                $user?->update([
                    'name' => $compte->ca_nom . ' ' . $compte->ca_prenom,
                    'email' => $compte->ca_email,
                    'password' => $compte->ca_pwd,
                ]);
            }

            DB::commit();

            return response()->json($compte);
        } catch (ModelNotFoundException $e) {
            return response()->json(['erreur' => 'Compte non trouvé'], 404);
        } catch (Throwable $th) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour du compte: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la mise à jour du compte'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(string $ca_num_recu)
    {
        try {
            $compte = Compte::findOrFail($ca_num_recu);

            DB::beginTransaction();

            // Supprimer le fichier reçu
            if ($compte->ca_recu && Storage::exists($compte->ca_recu)) {
                Storage::delete($compte->ca_recu);
            }

            // Supprimer l'utilisateur associé
            if ($compte->ca_email) {
                User::where('email', $compte->ca_email)->delete();
            }

            $compte->candidat()->delete();
            $compte->delete();

            DB::commit();

            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            return response()->json(['erreur' => 'Compte non trouvé'], 404);
        } catch (Throwable $th) {
            DB::rollback();
            Log::error('Erreur lors de la suppression du compte: ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la suppression du compte'], 500);
        }
    }

    public function showRecu($ca_num_recu)
    {
        try {
            $compte = Compte::findOrFail($ca_num_recu);
            $recu = $compte->ca_recu;

            if (!$recu || !Storage::exists($recu)) {
                return response()->json(['erreur' => 'Reçu non trouvé'], 404);
            }

            $fileContent = Storage::get($recu);
            $base64 = base64_encode($fileContent);
            return response()->json([
                'file_name' => 'document.pdf',
                'mime_type' => 'application/pdf',
                'base64_pdf' => $base64
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['erreur' => 'Compte non trouvé'], 404);
        }
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

    private function storageRecu($file, $nom, $prenom, $extension)
    {
        $filename = $nom . '-' . $prenom . '-' . now()->format('M_d_H_i') . '.' . $extension;
        return $file->storeAs(getdate()['year'], $filename);
    }
}

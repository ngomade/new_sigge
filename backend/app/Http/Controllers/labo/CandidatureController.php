<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\UserExterne;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\LaboratoirePersLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\ExterneConfirmationMail;
use App\Mail\ExternePasswordResetMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidatureApprovedMail;

class CandidatureController extends Controller
{
    /**
     * Affiche le formulaire de candidature
     */
    public function create($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        return view('laboratoires.public.candidature', compact('laboratoire'));
    }

    /**
     * Traite la candidature
     */
    public function store(Request $request, $code_lab)
    {
        $request->validate([
            'nom_user_ext' => 'required|string|max:255',
            'prenom_user_ext' => 'required|string|max:255',
            'email_user_ext' => 'required|email|unique:user_externe,email_user_ext',
            'tel_user_ext' => 'required|string|max:20',
            'motivation_path' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        try {
            // Générer un mot de passe temporaire
            $tempPassword = \Illuminate\Support\Str::random(8);
            // Gérer le fichier de lettre de motivation
            $motivationPath = $request->file('motivation_path')->store('motivations', 'public');

            // Créer l'utilisateur externe
            $userExterne = UserExterne::create([
                'code_lab' => $code_lab,
                'nom_user_ext' => $request->nom_user_ext,
                'prenom_user_ext' => $request->prenom_user_ext,
                'email_user_ext' => $request->email_user_ext,
                'tel_user_ext' => $request->tel_user_ext,
                'statut' => 'en_attente', // En attente de validation par l'admin
                'pwd' => Hash::make(Str::random(12)), // Mot de passe temporaire
                'date_debut' => now(),
                'motivation_path' => $motivationPath
            ]);

            // Gérer le CV si fourni
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $userExterne->update(['cv_path' => $cvPath]);
            }

            // Envoyer l'email de confirmation
            $emailSent = true;
            $emailErrorMessage = '';
            try {
                \Illuminate\Support\Facades\Mail::to($userExterne->email_user_ext)
                    ->send(new ExterneConfirmationMail($userExterne, $laboratoire, $tempPassword));
            } catch (\Exception $e) {
                $emailSent = false;
                $emailErrorMessage = $e->getMessage();
                // Log l'erreur d'envoi d'email mais ne pas faire échouer la création
                \Illuminate\Support\Facades\Log::error('Erreur envoi email confirmation externe: ' . $emailErrorMessage);
            }

            $successMessage = 'Utilisateur externe créé avec succès.';
            if ($emailSent) {
                $successMessage .= ' Un email de confirmation avec le mot de passe a été envoyé à ' . $userExterne->email_user_ext;
            } else {
                $successMessage .= ' Cependant, l\'envoi de l\'email a échoué : ' . $emailErrorMessage;
            }



            return redirect()->route('laboratoires.show', $code_lab)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur lors de la soumission de votre candidature : ' . $e->getMessage());
        }
    }

    /**
     * Affiche la liste des candidatures (admin)
     */
    public function index()
    {
        $candidatures = UserExterne::with(['laboratoire', 'affectations.roleLabo'])
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sige_app.frontend.labo.candidatures.index', compact('candidatures'));
    }

    /**
     * Affiche les détails d'une candidature (admin)
     */
    public function show($id)
    {
        $candidature = UserExterne::with(['laboratoire', 'affectations.roleLabo'])
            ->findOrFail($id);

        return view('sige_app.frontend.labo.candidatures.show', compact('candidature'));
    }

    /**
     * Approuve une candidature (admin)
     */
    public function approve($id)
    {
        $candidature = UserExterne::findOrFail($id);

        try {
            // Générer un mot de passe temporaire
            $tempPassword = Str::random(8);

            // Mettre à jour le statut et le mot de passe
            $candidature->update([
                'statut' => 'actif',
                'pwd' => Hash::make($tempPassword)
            ]);

            // Récupérer l'id du rôle "membre"
            $roleMembre = \App\Models\laboratoires\RoleLabo::whereRaw('LOWER(lib_rl) = ?', ['membre'])->first();
            $idRoleMembre = $roleMembre?->id_rl;

            // Créer l'affectation dans laboratoire_pers_lab
            LaboratoirePersLab::create([
                'code_lab' => $candidature->code_lab,
                'id_user_externe' => $candidature->id_user_ext,
                'date_affectation' => now(),
                'statut' => 'actif',
                'id_rl' => $idRoleMembre,
            ]);


            // TODO: Envoyer un email avec les identifiants
            Mail::to($candidature->email_user_ext)->send(new CandidatureApprovedMail($candidature, $tempPassword));

            return redirect()->route('labo.candidatures.index')
                ->with('success', 'Candidature approuvée avec succès. Un email a été envoyé au candidat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'approbation : ' . $e->getMessage());
        }
    }

    /**
     * Rejette une candidature (admin)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'motif_rejet' => 'required|string|max:500',
        ]);
        $candidature = UserExterne::findOrFail($id);

        try {
            // Mettre à jour le statut dans user_externe seulement
            $candidature->update(['statut' => 'rejeté']);

            // Envoyer un email de rejet avec le motif
            \Mail::to($candidature->email_user_ext)->send(new \App\Mail\CandidatureRejectedMail($candidature, $request->motif_rejet));

            return redirect()->route('labo.candidatures.index')
                ->with('success', 'Candidature rejetée avec succès. Un email a été envoyé au candidat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du rejet : ' . $e->getMessage());
        }
    }
}

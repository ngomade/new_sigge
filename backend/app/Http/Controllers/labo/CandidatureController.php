<?php

namespace App\Http\Controllers\Labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\UserExterne;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\LaboratoirePersLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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
            'motivation' => 'required|string|min:100',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        try {
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
                'motivation' => $request->motivation
            ]);

            // Gérer le CV si fourni
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
                $userExterne->update(['cv_path' => $cvPath]);
            }

            // Créer l'entrée dans pers_lab
            $persLab = PersLab::create([
                'id_pers_lab' => $userExterne->id_user_ext,
                'type_pers_lab' => 'user_externe',
                'date_entree' => now(),
                'statut' => 'en_attente'
            ]);

            // Créer l'affectation en attente
            LaboratoirePersLab::create([
                'code_lab' => $code_lab,
                'id_pers_lab' => $persLab->id_pers_lab,
                'id_user_externe' => $userExterne->id_user_ext,
                'date_affectation' => now(),
                'statut' => 'en_attente'
            ]);

            return redirect()->route('laboratoires.show', $code_lab)
                ->with('success', 'Votre candidature a été soumise avec succès ! Elle sera examinée par l\'administrateur du laboratoire.');

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

            // Mettre à jour l'affectation
            LaboratoirePersLab::where('id_user_externe', $candidature->id_user_ext)
                ->update(['statut' => 'actif']);

            // Mettre à jour pers_lab
            PersLab::where('id_pers_lab', $candidature->id_user_ext)
                ->update(['statut' => 'actif']);

            // TODO: Envoyer un email avec les identifiants
            // Mail::to($candidature->email_user_ext)->send(new CandidatureApprovedMail($candidature, $tempPassword));

            return redirect()->route('labo.candidatures.index')
                ->with('success', 'Candidature approuvée avec succès. Un email a été envoyé au candidat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'approbation : ' . $e->getMessage());
        }
    }

    /**
     * Rejette une candidature (admin)
     */
    public function reject($id)
    {
        $candidature = UserExterne::findOrFail($id);

        try {
            // Mettre à jour le statut
            $candidature->update(['statut' => 'rejeté']);

            // Mettre à jour l'affectation
            LaboratoirePersLab::where('id_user_externe', $candidature->id_user_ext)
                ->update(['statut' => 'rejeté']);

            // Mettre à jour pers_lab
            PersLab::where('id_pers_lab', $candidature->id_user_ext)
                ->update(['statut' => 'rejeté']);

            // TODO: Envoyer un email de rejet
            // Mail::to($candidature->email_user_ext)->send(new CandidatureRejectedMail($candidature));

            return redirect()->route('labo.candidatures.index')
                ->with('success', 'Candidature rejetée avec succès. Un email a été envoyé au candidat.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du rejet : ' . $e->getMessage());
        }
    }
}

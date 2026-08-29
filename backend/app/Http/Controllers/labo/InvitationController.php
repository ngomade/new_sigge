<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\LaboratoireInvitation;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\PersLab;
use App\Models\laboratoires\RoleLabo;
use App\Models\Personnel;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvitationController extends Controller
{
    /**
     * Affiche la page de gestion des invitations pour l'admin
     */
    public function index($code_lab)
    {
        // Vérifier l'authentification
        $adminId = session('user_id');
        $userType = session('user_type');

        if (! $adminId || ! $userType) {
            return redirect()->route('laboratoires.login.form', $code_lab)
                ->withErrors(['error' => 'Veuillez vous connecter pour accéder à cette page.']);
        }

        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        // Vérifier que l'utilisateur est admin du laboratoire
        $persLab = PersLab::where('id_pers_lab', $adminId)->first();

        if (! $persLab) {
            return redirect()->route('laboratoires.login.form', $code_lab)
                ->withErrors(['error' => 'Profil utilisateur non trouvé.']);
        }

        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
            ->where('id_pers_lab', $persLab->id_pers_lab)
            ->where('statut', 'actif')
            ->first();

        if (! $affectation) {
            return redirect()->route('laboratoires.login.form', $code_lab)
                ->withErrors(['error' => 'Vous n\'êtes pas autorisé à accéder à cette page.']);
        }

        // Vérifier le rôle admin
        if ($affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) !== 'admin') {
            return redirect()->route('laboratoires.espace.membre', $code_lab)
                ->withErrors(['error' => 'Seuls les administrateurs peuvent accéder à cette page.']);
        }

        $invitations = LaboratoireInvitation::where('code_lab', $code_lab)
            ->with(['roleLabo', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = RoleLabo::all();

        return view('laboratoires.admin.invitations.index', compact('laboratoire', 'invitations', 'roles'));
    }

    /**
     * Crée une nouvelle invitation
     */
    public function store(Request $request, $code_lab)
    {
        $request->validate([
            'id_rl' => 'nullable|exists:role_labo,id_rl',
            'date_fin_affectation' => 'required|date|after:today',
            'duree_validite_jours' => 'required|integer|min:1|max:30',
            'nombre_utilisations_max' => 'required|integer|min:1|max:100',
        ]);

        try {
            $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

            // Récupérer l'ID de l'admin connecté depuis la session
            $adminId = session('user_id');
            $userType = session('user_type');

            if (! $adminId || ! $userType) {
                return back()->withErrors(['error' => 'Session admin non trouvée. Veuillez vous reconnecter.']);
            }

            // Vérifier que l'utilisateur est bien admin du laboratoire
            $persLab = PersLab::where('id_pers_lab', $adminId)->first();

            if (! $persLab) {
                return back()->withErrors(['error' => 'Profil utilisateur non trouvé.']);
            }

            $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                ->where('id_pers_lab', $persLab->id_pers_lab)
                ->where('statut', 'actif')
                ->first();

            if (! $affectation) {
                return back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à créer des invitations pour ce laboratoire.']);
            }

            // Vérifier le rôle admin
            if ($affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) !== 'admin') {
                return back()->withErrors(['error' => 'Seuls les administrateurs peuvent créer des invitations.']);
            }

            // Créer l'invitation
            $invitation = LaboratoireInvitation::creerInvitation(
                $code_lab,
                $adminId,
                $request->id_rl,
                $request->date_fin_affectation,
                $request->duree_validite_jours,
                $request->nombre_utilisations_max
            );

            return back()->with('success', 'Lien d\'invitation créé avec succès !')
                ->with('invitation_url', $invitation->url_invitation);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'invitation: '.$e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de la création de l\'invitation']);
        }
    }

    /**
     * Supprime une invitation
     */
    public function destroy($code_lab, LaboratoireInvitation $invitation)
    {
        try {
            // Vérifier que l'invitation appartient bien au laboratoire
            if ($invitation->code_lab !== $code_lab) {
                return back()->withErrors(['error' => 'Invitation non trouvée']);
            }

            $invitation->delete();

            return back()->with('success', 'Invitation supprimée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'invitation: '.$e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Affiche la page d'acceptation d'invitation
     */
    public function accepterInvitation($token)
    {
        $invitation = LaboratoireInvitation::where('token', $token)
            ->with(['laboratoire', 'roleLabo'])
            ->first();

        if (! $invitation) {
            return redirect()->route('home')->withErrors(['error' => 'Lien d\'invitation invalide']);
        }

        if (! $invitation->est_valide) {
            return redirect()->route('home')->withErrors(['error' => 'Ce lien d\'invitation a expiré ou a déjà été utilisé']);
        }

        return view('laboratoires.invitation.verification', compact('invitation'));
    }

    /**
     * Traite la vérification et l'acceptation de l'invitation
     */
    public function traiterInvitation(Request $request, $token)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $invitation = LaboratoireInvitation::where('token', $token)->first();

        if (! $invitation || ! $invitation->est_valide) {
            return back()->withErrors(['error' => 'Lien d\'invitation invalide ou expiré']);
        }

        try {
            DB::beginTransaction();

            // Vérifier l'authentification
            $personne = $this->verifierAuthentification($request->login, $request->password);

            if (! $personne) {
                return back()->withErrors(['error' => 'Identifiants incorrects. Vérifiez votre login et mot de passe.']);
            }

            // Vérifier si la personne est déjà membre du laboratoire
            $persLab = PersLab::where('id_pers_lab', $personne['id'])->first();

            if ($persLab) {
                $affectationExistante = LaboratoirePersLab::where('code_lab', $invitation->code_lab)
                    ->where('id_pers_lab', $persLab->id_pers_lab)
                    ->where('statut', 'actif')
                    ->first();

                if ($affectationExistante) {
                    return back()->withErrors(['error' => 'Vous êtes déjà membre actif de ce laboratoire']);
                }
            } else {
                // Créer l'entrée dans pers_lab
                $persLab = PersLab::create([
                    'id_pers_lab' => $personne['id'],
                    'type_pers_lab' => $personne['type'],
                    'date_entree' => now(),
                    'statut' => 'actif',
                ]);
            }

            // Créer l'affectation
            LaboratoirePersLab::create([
                'code_lab' => $invitation->code_lab,
                'id_pers_lab' => $persLab->id_pers_lab,
                'id_rl' => $invitation->id_rl,
                'date_affectation' => now(),
                'date_fin_affectation' => $invitation->date_fin_affectation,
                'statut' => 'actif',
            ]);

            // Marquer l'invitation comme utilisée
            $invitation->marquerCommeUtilisee();

            DB::commit();

            return redirect()->route('laboratoires.show', $invitation->code_lab)
                ->with('success', 'Vous avez rejoint le laboratoire avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement de l\'invitation: '.$e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de l\'acceptation de l\'invitation']);
        }
    }

    /**
     * Vérifie l'authentification d'une personne
     */
    private function verifierAuthentification(string $login, string $password): ?array
    {
        // Vérifier si c'est un Personnel
        $personnel = Personnel::where('login_pers', $login)->first();
        if ($personnel) {
            $passwordValid = false;
            try {
                if (Hash::check($password, $personnel->pwd_pers)) {
                    $passwordValid = true;
                }
            } catch (\RuntimeException $e) {
                if ($personnel->pwd_pers === md5($password)) {
                    $passwordValid = true;
                }
            }

            if ($passwordValid) {
                return [
                    'id' => $personnel->code_pers,
                    'type' => 'personnel',
                    'nom' => $personnel->nom_pers,
                    'prenom' => $personnel->prenom_pers,
                ];
            }
        }

        // Vérifier si c'est un User (étudiant)
        $user = Users::where('login_user', $login)->first();
        if ($user) {
            $passwordValid = false;
            try {
                if (Hash::check($password, $user->pwd_user)) {
                    $passwordValid = true;
                }
            } catch (\RuntimeException $e) {
                if ($user->pwd_user === md5($password)) {
                    $passwordValid = true;
                }
            }

            if ($passwordValid) {
                return [
                    'id' => $user->code_user,
                    'type' => 'user',
                    'nom' => $user->nom_user,
                    'prenom' => $user->prenom_user,
                ];
            }
        }

        return null;
    }

    /**
     * Télécharge le QR code d'une invitation
     */
    public function telechargerQRCode($token)
    {
        $invitation = LaboratoireInvitation::where('token', $token)->first();

        if (! $invitation) {
            abort(404, 'Invitation non trouvée');
        }

        $format = request('format', 'png');

        if ($format === 'svg') {
            $qrCodeSvg = $invitation->qr_code;

            return response($qrCodeSvg)
                ->header('Content-Type', 'image/svg+xml');
        } else {
            // Générer un QR code PNG de haute qualité
            $qrCodePng = QrCode::size(400)
                ->format('png')
                ->style('round')
                ->eye('circle')
                ->margin(2)
                ->color(105, 108, 255)
                ->generate($invitation->url_invitation);

            return response($qrCodePng)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="qr-code-invitation-'.$token.'.png"');
        }
    }

    /**
     * Commande artisan pour nettoyer les invitations expirées
     */
    public function nettoyerInvitationsExpirees()
    {
        $invitationsExpirees = LaboratoireInvitation::where('date_expiration', '<', now())
            ->where('statut', 'actif')
            ->get();

        foreach ($invitationsExpirees as $invitation) {
            $invitation->marquerCommeExpiree();
        }

        Log::info('Nettoyage des invitations expirées terminé', [
            'nombre_invitations_expirees' => $invitationsExpirees->count(),
        ]);

        return $invitationsExpirees->count();
    }
}

<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\LaboratoirePersLab;
use App\Models\laboratoires\UserExterne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PublicLaboratoireController extends Controller
{
    /**
     * Affiche la landing page publique d'un laboratoire
     */
    public function show($code_lab)
    {
        $laboratoire = Laboratoire::with([
            'projets' => function ($query) {
                $query->where('statut_projet', 'En cours');
            },
            'membres.affectations.roleLabo',
            'equipements',
        ])->where('code_lab', $code_lab)->firstOrFail();

        return view('laboratoires.public.show', compact('laboratoire'));
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function loginForm($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

        return view('laboratoires.public.login', compact('laboratoire'));
    }

    /**
     * Traite la connexion
     */
    public function login(Request $request, $code_lab)
    {
        try {
            $request->validate([
                'login' => 'required',
                'password' => 'required',
            ]);

            $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();

            // 1. Vérifier si c'est un Personnel
            $personnel = \App\Models\Personnel::where('login_pers', $request->login)->first();

            if ($personnel) {
                $passwordValid = false;
                try {
                    if (Hash::check($request->password, $personnel->pwd_pers)) {
                        $passwordValid = true;
                    }
                } catch (\RuntimeException $e) {
                    // Fallback to md5 check if Hash::check fails (e.g. non-bcrypt password)
                    if ($personnel->pwd_pers === md5($request->password)) {
                        $passwordValid = true;
                    }
                }
                if ($passwordValid) {
                    $persLab = \App\Models\laboratoires\PersLab::where('id_pers_lab', $personnel->code_pers)->first();
                    if ($persLab) {
                        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                            ->where('id_pers_lab', $persLab->id_pers_lab)
                            ->where('statut', 'actif')
                            ->first();
                        if ($affectation) {
                            session([
                                'user_id' => $personnel->code_pers,
                                'user_type' => 'personnel',
                                'laboratoire_code' => $code_lab,
                                'user_name' => $personnel->nom_pers.' '.$personnel->prenom_pers,
                            ]);
                            // Redirection selon le rôle
                            if ($affectation->roleLabo && strtolower($affectation->roleLabo->lib_rl) === 'admin') {
                                return redirect()->route('laboratoires.admin.dashboard', $code_lab)
                                    ->with('success', 'Connexion réussie !');
                            } else {
                                return redirect()->route('laboratoires.espace.membre', ['code_lab' => $code_lab])
                                    ->with('success', 'Connexion réussie !');
                            }
                        }
                    }
                }
            }

            // 2. Vérifier si c'est un User (étudiant)
            $user = \App\Models\Users::where('login_user', $request->login)->first();

            if ($user) {
                $passwordValid = false;
                try {
                    if (Hash::check($request->password, $user->pwd_user)) {
                        $passwordValid = true;
                    }
                } catch (\RuntimeException $e) {
                    // Fallback to md5 check if Hash::check fails (e.g. non-bcrypt password)
                    if ($user->pwd_user === md5($request->password)) {
                        $passwordValid = true;
                    }
                }
                if ($passwordValid) {
                    $persLab = \App\Models\laboratoires\PersLab::where('id_pers_lab', $user->code_user)->first();
                    if ($persLab) {
                        $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                            ->where('id_pers_lab', $persLab->id_pers_lab)
                            ->where('statut', 'actif')
                            ->first();
                        if ($affectation) {
                            session([
                                'user_id' => $user->code_user,
                                'user_type' => 'user',
                                'laboratoire_code' => $code_lab,
                                'user_name' => $user->nom_user.' '.$user->prenom_user,
                            ]);

                            // Redirection membre classique
                            return redirect()->route('laboratoires.espace.membre', ['code_lab' => $code_lab])
                                ->with('success', 'Connexion réussie !');
                        }
                    }
                }
            }

            // 3. Vérifier si c'est un utilisateur externe
            $userExterne = UserExterne::where('email_user_ext', $request->login)
                ->where('code_lab', $code_lab)
                ->where('statut', 'actif')
                ->first();

            if ($userExterne) {
                Log::info('External user found for login attempt', ['email' => $request->login, 'user_id' => $userExterne->id_user_ext]);
                $passwordValid = false;
                try {
                    if (Hash::check($request->password, $userExterne->pwd)) {
                        $passwordValid = true;
                    }
                } catch (\RuntimeException $e) {
                    // Fallback to md5 check if Hash::check fails (e.g. non-bcrypt password)
                    if ($userExterne->pwd === md5($request->password)) {
                        $passwordValid = true;
                    }
                }
                if (! $passwordValid) {
                    Log::debug('External user password check failed', [
                        'user_id' => $userExterne->id_user_ext,
                        'stored_password' => $userExterne->pwd,
                        'input_password_md5' => md5($request->password),
                        'input_password_raw' => $request->password,
                    ]);
                    // Additional detailed logging for troubleshooting
                    Log::debug('Detailed external user password check failure info', [
                        'user_id' => $userExterne->id_user_ext,
                        'input_password_length' => strlen($request->password),
                        'input_password_hex' => bin2hex($request->password),
                        'stored_password_length' => strlen($userExterne->pwd),
                        'stored_password_hash_prefix' => substr($userExterne->pwd, 0, 4),
                    ]);
                }
                if ($passwordValid) {
                    Log::info('External user password valid', ['user_id' => $userExterne->id_user_ext]);
                    // Check for active affectation in LaboratoirePersLab
                    $affectation = LaboratoirePersLab::where('code_lab', $code_lab)
                        ->where('id_user_externe', $userExterne->id_user_ext)
                        ->where('statut', 'actif')
                        ->where('date_affectation', '<=', now())
                        ->where(function ($query) {
                            $query->whereNull('date_fin_affectation')
                                ->orWhere('date_fin_affectation', '>=', now());
                        })
                        ->first();

                    Log::debug('External user affectation query result', ['affectation' => $affectation]);
                    if ($affectation) {
                        session([
                            'user_id' => $userExterne->id_user_ext,
                            'user_type' => 'externe',
                            'laboratoire_code' => $code_lab,
                            'user_name' => $userExterne->nom_user_ext.' '.$userExterne->prenom_user_ext,
                        ]);

                        // Redirection membre classique
                        return redirect()->route('laboratoires.espace.membre', ['code_lab' => $code_lab])
                            ->with('success', 'Connexion réussie !');
                    } else {
                        Log::warning('External user affectation inactive or missing', ['user_id' => $userExterne->id_user_ext]);

                        return back()->withErrors([
                            'login' => 'Votre affectation au laboratoire n\'est plus valide. Veuillez contacter l\'administrateur.',
                        ])->withInput($request->only('login'));
                    }
                } else {
                    Log::warning('External user password invalid', ['user_id' => $userExterne->id_user_ext]);
                }
            } else {
                Log::warning('External user not found for login', ['email' => $request->login, 'code_lab' => $code_lab]);
            }

            return back()->withErrors([
                'login' => 'Identifiants incorrects ou vous n\'êtes pas autorisé à accéder à ce laboratoire.',
            ])->withInput($request->only('login'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Login error in PublicLaboratoireController: '.$e->getMessage(), ['exception' => $e]);

            return back()->withErrors([
                'login' => 'Une erreur est survenue lors de la connexion. Veuillez réessayer.',
            ])->withInput($request->only('login'));
        }
    }

    /**
     * Déconnexion
     */
    public function logout($code_lab)
    {
        session()->forget(['user_id', 'user_type', 'laboratoire_code', 'user_name']);

        return redirect()->route('laboratoires.show', $code_lab)
            ->with('success', 'Déconnexion réussie.');
    }

    /**
     * Espace membre du laboratoire
     */
    public function espaceMembre($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $userType = session('user_type');
        $userId = session('user_id');

        // Récupérer les informations de l'utilisateur selon son type
        $user = null;
        switch ($userType) {
            case 'personnel':
                $user = \App\Models\Personnel::where('code_pers', $userId)->first();
                break;
            case 'user':
                $user = \App\Models\Users::where('code_user', $userId)->first();
                break;
            case 'externe':
                $user = UserExterne::where('id_user_ext', $userId)->first();
                break;
        }

        // Récupérer les projets du laboratoire via la relation
        $projets = $laboratoire->projets;

        // Récupérer les équipements du laboratoire via la relation
        $equipements = $laboratoire->equipements;

        // Récupérer les publications du laboratoire (10 dernières)
        $publications = \App\Models\laboratoires\Publication::where('code_lab', $laboratoire->code_lab)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('laboratoires.public.espace-membre', compact('laboratoire', 'user', 'userType', 'projets', 'equipements', 'publications'));
    }

    /**
     * Profil utilisateur
     */
    public function profil($code_lab)
    {
        $laboratoire = Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        $userType = session('user_type');
        $userId = session('user_id');

        // Récupérer les informations de l'utilisateur selon son type
        $user = null;
        switch ($userType) {
            case 'personnel':
                $user = \App\Models\Personnel::where('code_pers', $userId)->first();
                break;
            case 'user':
                $user = \App\Models\Users::where('code_user', $userId)->first();
                break;
            case 'externe':
                $user = UserExterne::where('id_user_ext', $userId)->first();
                break;
        }

        return view('laboratoires.public.profil', compact('laboratoire', 'user', 'userType'));
    }

    /**
     * Mise à jour du profil utilisateur
     */
    public function updateProfil(Request $request, $code_lab)
    {
        $userType = session('user_type');
        $userId = session('user_id');

        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'nullable|string|max:20',
        ];
        // Ajout validation mot de passe si externe
        if ($userType == 'externe') {
            if ($request->filled('pwd')) {
                $rules['pwd'] = 'required|string';
            }
        }
        $request->validate($rules);

        try {
            switch ($userType) {
                case 'personnel':
                    $user = \App\Models\Personnel::where('code_pers', $userId)->first();
                    if ($user) {
                        $user->update([
                            'nom_pers' => $request->nom,
                            'prenom_pers' => $request->prenom,
                            'email_pers' => $request->email,
                            'first_phone_pers' => $request->telephone,
                        ]);
                    }
                    break;
                case 'user':
                    $user = \App\Models\Users::where('code_user', $userId)->first();
                    if ($user) {
                        $user->update([
                            'nom_user' => $request->nom,
                            'prenom_user' => $request->prenom,
                            'email_user' => $request->email,
                            'first_phone_user' => $request->telephone,
                        ]);
                    }
                    break;
                case 'externe':
                    $user = UserExterne::where('id_user_ext', $userId)->first();
                    if ($user) {
                        $updateData = [
                            'nom_user_ext' => $request->nom,
                            'prenom_user_ext' => $request->prenom,
                            'email_user_ext' => $request->email,
                            'tel_user_ext' => $request->telephone,
                        ];
                        if ($request->filled('pwd')) {
                            $updateData['pwd'] = Hash::make($request->pwd); // ou Hash::make si tu veux migrer
                        }
                        $user->update($updateData);
                    }
                    break;
            }

            // Mettre à jour le nom dans la session
            session(['user_name' => $request->nom.' '.$request->prenom]);

            return redirect()->route('laboratoires.profil', $code_lab)
                ->with('success', 'Profil mis à jour avec succès !');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour du profil.']);
        }
    }
}

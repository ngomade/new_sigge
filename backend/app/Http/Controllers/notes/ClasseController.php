<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\concours\User;
use App\Models\notes\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Classe::with('user');

            // Filtres
            if ($request->filled('search')) {
                $query->where('label_class', 'LIKE', '%'.$request->search.'%');
            }

            if ($request->filled('user')) {
                $query->where('code_user', $request->user);
            }

            $classes = $query->paginate(10);

            // Données pour les filtres
            // $users = User::where('role', 'enseignant')->orderBy('name')->get();

            return view('sige_app.backend.classe.classe_index', compact('classes', 'users'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des classes: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du chargement des classes.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $users = User::where('role', 'enseignant')->orderBy('name')->get();

            return view('sige_app.backend.classe.classe_create', compact('users'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création de classe: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('classes.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_class' => 'required|string|max:100',
            'code_user' => 'required|exists:users,code_user',
        ], [
            'label_class.required' => 'Le libellé de la classe est obligatoire.',
            'label_class.max' => 'Le libellé de la classe ne doit pas dépasser 100 caractères.',
            'code_user.required' => 'L\'utilisateur est obligatoire.',
            'code_user.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Vérifier si la classe existe déjà
            $existingClasse = Classe::where('label_class', $request->label_class)->first();

            if ($existingClasse) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Une classe avec ce libellé existe déjà.')
                    ->withInput();
            }

            $classe = Classe::create([
                'code_class' => Str::uuid(),
                'label_class' => $request->label_class,
                'code_user' => $request->code_user,
            ]);

            DB::commit();

            Log::info('Classe créée avec succès', [
                'classe_id' => $classe->code_class,
                'user_id' => auth()->id(),
                'data' => $request->only(['label_class', 'code_user']),
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Classe créée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la création de la classe: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la création de la classe.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_class)
    {
        try {
            $classe = Classe::with(['user', 'niveaux'])->findOrFail($code_class);

            // Statistiques pour cette classe
            $stats = [
                'total_niveaux' => $classe->niveaux->count(),
            ];

            return view('sige_app.backend.classe.classe_show', compact('classe', 'stats'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de la classe: '.$e->getMessage(), [
                'classe_id' => $code_class,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('classes.index')
                ->with('error', 'Classe introuvable ou erreur lors du chargement.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_class)
    {
        try {
            $classe = Classe::findOrFail($code_class);
            $users = User::where('role', 'enseignant')->orderBy('name')->get();

            return view('sige_app.backend.classe.classe_edit', compact('classe', 'users'));

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de modification: '.$e->getMessage(), [
                'classe_id' => $code_class,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('classes.index')
                ->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_class)
    {
        $validator = Validator::make($request->all(), [
            'label_class' => 'required|string|max:100',
            'code_user' => 'required|exists:users,code_user',
        ], [
            'label_class.required' => 'Le libellé de la classe est obligatoire.',
            'label_class.max' => 'Le libellé de la classe ne doit pas dépasser 100 caractères.',
            'code_user.required' => 'L\'utilisateur est obligatoire.',
            'code_user.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $classe = Classe::findOrFail($code_class);

            // Vérifier si une autre classe avec ce libellé existe déjà
            $existingClasse = Classe::where('label_class', $request->label_class)
                ->where('code_class', '!=', $code_class)
                ->first();

            if ($existingClasse) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Une classe avec ce libellé existe déjà.')
                    ->withInput();
            }

            $classe->update([
                'label_class' => $request->label_class,
                'code_user' => $request->code_user,
            ]);

            DB::commit();

            Log::info('Classe mise à jour avec succès', [
                'classe_id' => $classe->code_class,
                'user_id' => auth()->id(),
                'data' => $request->only(['label_class', 'code_user']),
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Classe mise à jour avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la mise à jour de la classe: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'classe_id' => $code_class,
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la classe.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_class)
    {
        try {
            DB::beginTransaction();

            $classe = Classe::findOrFail($code_class);

            // Vérifier si la classe a des niveaux associés
            if ($classe->niveaux()->count() > 0) {
                DB::rollBack();

                return redirect()->route('classes.index')
                    ->with('error', 'Impossible de supprimer cette classe car elle contient des niveaux associés.');
            }

            // Vérifier si la classe a des assignations
            if ($classe->assignations()->count() > 0) {
                DB::rollBack();

                return redirect()->route('classes.index')
                    ->with('error', 'Impossible de supprimer cette classe car elle a des assignations.');
            }

            $classe->delete();

            DB::commit();

            Log::info('Classe supprimée avec succès', [
                'classe_id' => $code_class,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Classe supprimée avec succès.');

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de la classe: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'classe_id' => $code_class,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('classes.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de la classe.');
        }
    }
}

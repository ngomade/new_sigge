<?php

namespace App\Http\Controllers\requetes;

use App\Http\Controllers\Controller;
use App\Models\requetes\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Afficher la liste des catégories
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();

        return view('sige_app.backend.requetes.categorie_index', compact('categories'));
    }

    /**
     * Ajouter une nouvelle catégorie
     */
    public function store(Request $request)
    {
        $request->validate([
            'label_cat' => 'required|string|max:255',
            'desc_cat' => 'nullable|string',
        ]);

        try {
            // Générer un code unique pour la catégorie
            $code_cat = 'CAT_'.strtoupper(Str::random(6));

            // Vérifier que le code n'existe pas déjà
            while (Category::where('code_cat', $code_cat)->exists()) {
                $code_cat = 'CAT_'.strtoupper(Str::random(6));
            }

            Category::create([
                'code_cat' => $code_cat,
                'label_cat' => $request->label_cat,
                'desc_cat' => $request->desc_cat,
            ]);

            return redirect()->back()->with('success', 'Catégorie ajoutée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout de la catégorie: '.$e->getMessage());
        }
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, $code_cat)
    {
        $request->validate([
            'label_cat' => 'required|string|max:255',
            'desc_cat' => 'nullable|string',
        ]);

        try {
            $category = Category::findOrFail($code_cat);

            $category->update([
                'label_cat' => $request->label_cat,
                'desc_cat' => $request->desc_cat,
            ]);

            return redirect()->back()->with('success', 'Catégorie modifiée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la modification: '.$e->getMessage());
        }
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy($code_cat)
    {
        try {
            $category = Category::findOrFail($code_cat);

            // Vérifier s'il y a des requêtes liées à cette catégorie
            if ($category->requests()->count() > 0) {
                return redirect()->back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des requêtes');
            }

            $category->delete();

            return redirect()->back()->with('success', 'Catégorie supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: '.$e->getMessage());
        }
    }
}

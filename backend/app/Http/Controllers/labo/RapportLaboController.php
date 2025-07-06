<?php

namespace App\Http\Controllers\Labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\RapportLabo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RapportLaboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $rapports = RapportLabo::orderBy('created_at', 'desc')->paginate(10);

            $laboratoire = null;
            if (session()->has('laboratoire_code')) {
                $laboratoire = \App\Models\laboratoires\Laboratoire::where('code_lab', session('laboratoire_code'))->first();
            }

            return view('laboratoires.admin.rapports.index', compact('rapports', 'laboratoire'));
        } catch (\Exception $e) {
            Log::error('Error fetching rapports: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement des rapports.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $laboratoire = null;
            if (session()->has('laboratoire_code')) {
                $laboratoire = \App\Models\laboratoires\Laboratoire::where('code_lab', session('laboratoire_code'))->first();
            }
            return view('laboratoires.admin.rapports.create', compact('laboratoire'));
        } catch (\Exception $e) {
            Log::error('Error showing create rapport form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'affichage du formulaire.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'description' => 'nullable|string',
            'fichier_path' => 'nullable|string',
        ]);

        try {
            $primaryKey = $this->generatePrimaryKey();

            $rapport = new RapportLabo();
            $rapport->code_rapport = $primaryKey;
            $rapport->titre = $request->titre;
            $rapport->description = $request->description;
            $rapport->fichier_path = $request->fichier_path;
            $rapport->save();

            return redirect()->route('labo.rapports.index')->with('success', 'Rapport ajouté avec succès.');
        } catch (\Exception $e) {
            Log::error('Error storing rapport: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout du rapport.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_rapport)
    {
        try {
            $rapport = RapportLabo::where('code_rapport', $code_rapport)->firstOrFail();
            return view('laboratoires.admin.rapports.show', compact('rapport'));
        } catch (\Exception $e) {
            Log::error('Error showing rapport: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Rapport non trouvé.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_rapport)
    {
        try {
            $rapport = RapportLabo::where('code_rapport', $code_rapport)->firstOrFail();
            return view('laboratoires.admin.rapports.edit', compact('rapport'));
        } catch (\Exception $e) {
            Log::error('Error showing edit rapport form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'affichage du formulaire.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_rapport)
    {
        $request->validate([
            'titre' => 'required|max:255',
            'description' => 'nullable|string',
            'fichier_path' => 'nullable|string',
        ]);

        try {
            $rapport = RapportLabo::where('code_rapport', $code_rapport)->firstOrFail();
            $rapport->titre = $request->titre;
            $rapport->description = $request->description;
            $rapport->fichier_path = $request->fichier_path;
            $rapport->save();

            return redirect()->route('labo.rapports.show', $rapport->code_rapport)->with('success', 'Rapport mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating rapport: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour du rapport.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_rapport)
    {
        try {
            $rapport = RapportLabo::where('code_rapport', $code_rapport)->firstOrFail();
            $rapport->delete();

            return redirect()->route('labo.rapports.index')->with('success', 'Rapport supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting rapport: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la suppression du rapport.');
        }
    }

    /**
     * Generate a primary key starting with RAP followed by ascending numbers.
     */
    private function generatePrimaryKey()
    {
        $lastRapport = RapportLabo::orderBy('created_at', 'desc')->first();

        if (!$lastRapport) {
            return 'RAP1';
        }

        $lastCode = $lastRapport->code_rapport;
        $number = (int) str_replace('RAP', '', $lastCode);

        return 'RAP' . ($number + 1);
    }
}

<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use App\Models\laboratoires\Equipements;
use App\Models\laboratoires\Laboratoire;
use App\Models\laboratoires\ReservationAgent;
use App\Models\laboratoires\EntretienReparation;
use Illuminate\Http\Request;

class EquipementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Equipements::with('laboratoire');

        if ($request->has('laboratoire')) {
            $query->where('code_lab', $request->laboratoire);
        }

        if ($request->has('etat')) {
            $query->where('etat', $request->etat);
        }

        $equipements = $query->paginate(10);
        $laboratoires = Laboratoire::all();

        return view('sige_app.frontend.labo.equipements.index', compact('equipements', 'laboratoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratoires = Laboratoire::all();
        return view('sige_app.frontend.admin.equipements.create', compact('laboratoires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_equip' => 'required|max:255',
            'ref_equip' => 'required|unique:equipements,ref_equip',
            'desc_equip' => 'nullable',
            'etat' => 'required|in:disponible,en_maintenance,hors_service',
            'date_achat' => 'required|date',
            'valeur' => 'required|numeric|min:0',
            'localisation' => 'required|max:255',
            'code_lab' => 'required|exists:laboratoire,code_lab'
        ]);

        Equipements::create($validated);

        return redirect()->route('equipements.index')
            ->with('success', 'Équipement ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipement = Equipements::with(['laboratoire', 'reservations.membre', 'entretiens.membre'])
            ->findOrFail($id);

        $reservations = $equipement->reservations()
            ->with('membre')
            ->orderBy('debut_reserv', 'desc')
            ->take(10)
            ->get();

        $entretiens = $equipement->entretiens()
            ->with('membre')
            ->orderBy('debut_entretien', 'desc')
            ->take(10)
            ->get();

        return view('sige_app.frontend.labo.equipements.show', compact('equipement', 'reservations', 'entretiens'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $equipement = Equipements::findOrFail($id);
        $laboratoires = Laboratoire::all();

        return view('sige_app.frontend.admin.equipements.edit', compact('equipement', 'laboratoires'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $equipement = Equipements::findOrFail($id);

        $validated = $request->validate([
            'nom_equip' => 'required|max:255',
            'ref_equip' => 'required|unique:equipements,ref_equip,' . $id . ',code_equip',
            'desc_equip' => 'nullable',
            'etat' => 'required|in:disponible,en_maintenance,hors_service',
            'date_achat' => 'required|date',
            'valeur' => 'required|numeric|min:0',
            'localisation' => 'required|max:255',
            'code_lab' => 'required|exists:laboratoire,code_lab'
        ]);

        $equipement->update($validated);

        return redirect()->route('equipements.show', $equipement->code_equip)
            ->with('success', 'Équipement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $equipement = Equipements::findOrFail($id);

        // Vérifier s'il n'y a pas de réservations actives
        $hasActiveReservations = $equipement->reservations()
            ->where('statut', 'confirmee')
            ->where('fin_reserv', '>=', now())
            ->exists();

        if ($hasActiveReservations) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer un équipement avec des réservations actives.');
        }

        $equipement->delete();

        return redirect()->route('equipements.index')
            ->with('success', 'Équipement supprimé avec succès.');
    }

    /**
     * Afficher le formulaire de réservation
     */
    public function showReservationForm($id)
    {
        $equipement = Equipements::findOrFail($id);
        $reservations = $equipement->reservations()
            ->where('statut', 'confirmee')
            ->where('fin_reserv', '>=', now())
            ->get();

        return view('sige_app.frontend.labo.equipements.reserver', compact('equipement', 'reservations'));
    }

    /**
     * Enregistrer une réservation
     */
    public function storeReservation(Request $request, $id)
    {
        $equipement = Equipements::findOrFail($id);
        $request->validate([
            'participant_type' => 'required|in:interne,externe',
            'id_pers_lab' => 'nullable|exists:pers_lab,id_pers_lab',
            'id_user_ext' => 'nullable|exists:user_externe,id_user_ext',
            'debut_reserv' => 'required|date|after:now',
            'fin_reserv' => 'required|date|after:debut_reserv'
        ]);
        if ($request->participant_type === 'interne' && !$request->id_pers_lab) {
            return redirect()->back()->with('error', 'Veuillez sélectionner un membre interne.');
        }
        if ($request->participant_type === 'externe' && !$request->id_user_ext) {
            return redirect()->back()->with('error', 'Veuillez sélectionner un user externe.');
        }
        if ($request->id_pers_lab && $request->id_user_ext) {
            return redirect()->back()->with('error', 'Un seul type de participant doit être sélectionné.');
        }
        $conflict = ReservationAgent::where('code_equip', $id)
            ->where('statut', 'confirmee')
            ->where(function ($query) use ($request) {
                $query->whereBetween('debut_reserv', [$request->debut_reserv, $request->fin_reserv])
                    ->orWhereBetween('fin_reserv', [$request->debut_reserv, $request->fin_reserv])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('debut_reserv', '<=', $request->debut_reserv)
                            ->where('fin_reserv', '>=', $request->fin_reserv);
                    });
            })
            ->exists();
        if ($conflict) {
            return redirect()->back()->with('error', 'L\'équipement n\'est pas disponible pour cette période.');
        }
        ReservationAgent::create([
            'code_equip' => $id,
            'id_pers_lab' => $request->participant_type === 'interne' ? $request->id_pers_lab : null,
            'id_user_ext' => $request->participant_type === 'externe' ? $request->id_user_ext : null,
            'debut_reserv' => $request->debut_reserv,
            'fin_reserv' => $request->fin_reserv,
            'statut' => 'en_attente'
        ]);
        $equipement->updateEtatAutomatique();
        return redirect()->route('equipements.show', $id)
            ->with('success', 'Demande de réservation enregistrée avec succès.');
    }
}

<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\EcoleElement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class EcoleElementControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ecoleElements = EcoleElement::all();

        return response()->json($ecoleElements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'code_ecole' => 'required|exists:ecole,code_ecole',
            'code_el' => 'required|exists:dossier,code_el',
        ]);
        try {
            $ecoleElement = EcoleElement::create($validateData);

            return response()->json($ecoleElement);
        } catch (Throwable $th) {
            Log::error('Error creating ecole element: '.$th->getMessage());

            return response()->json(['erreur' => 'erreur lors de l\'enregistrement de l\'ecole element'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ecoleElement = EcoleElement::findorFail($id);

        return response()->json($ecoleElement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'code_ecole' => 'required|exists:ecole,code_ecole',
            'code_el' => 'required|exists:dossier,code_el',
        ]);
        try {
            $ecoleElement = EcoleElement::findOrFail($id);
            $ecoleElement->update($validateData);

            return response()->json($ecoleElement);
        } catch (Throwable $th) {
            Log::error('Error updating ecole element: '.$th->getMessage());

            return response()->json(['erreur' => 'erreur lors de la mise à jour de l\'ecole element'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ecoleElement = EcoleElement::findOrFail($id);
            $ecoleElement->delete();

            return response()->json(['succes' => 'ecole element supprimé']);
        } catch (Throwable $th) {
            Log::error('Error deleting ecole element: '.$th->getMessage());

            return response()->json(['erreur' => 'erreur lors de la suppression de l\'ecole element'], 500);
        }
    }
}

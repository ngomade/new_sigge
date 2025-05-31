<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EcoleElement;
use Illuminate\Support\Facades\DB;
use Throwable;

class EcoleElementControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ecoleElements = EcoleElement::all();
        return response()->json($ecoleElements, 200);
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
            DB::beginTransaction();
            $ecoleElement = EcoleElement::create($validateData);
            DB::commit();
            return response()->json($ecoleElement, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de l\'enregistrement de l\'ecole element'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ecoleElement = EcoleElement::find($id);
        if (!$ecoleElement) {
            return response()->json(['erreur' => 'ecole element non trouvé'], 404);
        }
        return response()->json($ecoleElement, 200);
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
            DB::beginTransaction();
            $ecoleElement = EcoleElement::findOrFail($id);
            $ecoleElement->update($validateData);
            DB::commit();
            return response()->json($ecoleElement, 200);
        } catch (Throwable $th) {
            DB::rollback();
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
            DB::commit();
            return response()->json(['succes' => 'ecole element supprimé'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de la suppression de l\'ecole element'], 500);
        }
    }
}

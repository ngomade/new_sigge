<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Composition;
use Illuminate\Support\Facades\DB;
use Throwable;

class CompositionControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compositions = Composition::all();
        return response()->json($compositions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'code_ecole' => 'required|string|exists:ecole,code_ecole',
            'site_code' => 'required|string|exists:site_composition,site_code',
        ]);
        try {
            DB::beginTransaction();
            $composition = Composition::create($validateData);
            DB::commit();
            return response()->json($composition, 201);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de l\'enregistrement de la composition'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $composition = Composition::find($id);
        if (!$composition) {
            return response()->json(['erreur' => 'composition non trouvée'], 404);
        }
        return response()->json($composition, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
           'code_ecole' => 'required|string|exists:ecole,code_ecole',
            'site_code' => 'required|string|exists:site_composition,site_code',
        ]);
        try {
            DB::beginTransaction();
            $composition = Composition::findOrFail($id);
            $composition->update($validateData);
            DB::commit();
            return response()->json($composition, 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de la mise à jour de la composition'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $composition = Composition::findOrFail($id);
            $composition->delete();
            DB::commit();
            return response()->json(['succes' => 'composition supprimée'], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de la suppression de la composition'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ecole;
use Illuminate\Support\Facades\DB;
use Throwable;

class EcoleControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ecole = Ecole::all();
        return response()->json($ecole,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'code_ecole' => 'required|string|max:32',
            'label_ecole' => 'required|string|max:128',
            'logo_ecole' => 'required|mimes:png,jpg,jpeg|max:512',
            'desc_ecole' => 'required|string|max:255',
            'tel_ecole' => 'required|string|max:32',
            'email_ecole' => 'nullable|email|max:128',
            'bp_ecole' => 'required|string|max:128',
            
            'centre_depot' => 'required|exists:centre_depot,centre_depot_code',
            
        ]);
        
        try {
            DB::beginTransaction();
            $res = Ecole::create($request->all());
            DB::commit();
            return response()->json($res, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur l\'or de l\'enregistrement de l\'ecole: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ecole = Ecole::findOrfail($id);
        if (!$ecole) {
            return response()->json(['error' => 'Ecole non trouve'], 404);
        }
        return response()->json($ecole, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code_ecole)
    {
        //
        $validatedData = $request->validate([
            'code_ecole' => 'required|string|max:32',
            'label_ecole' => 'required|string|max:128',
            'logo_ecole' => 'required|mimes:png,jpg,jpeg|max:512',
            'desc_ecole' => 'required|string|max:255',
            'tel_ecole' => 'required|string|max:32',
            'email_ecole' => 'nullable|email|max:128',
            'bp_ecole' => 'required|string|max:128',
            
            'centre_depot' => 'required|exists:centre_depot,centre_depot_code',
        ]);
        try{
            DB::beginTransaction();
            $ecole = Ecole::findOrfail($code_ecole);
            $ecole->update($request->all());
            DB::commit();
            return response()->json($ecole, 200);

        }catch(Throwable $th){
            DB::rollBack();
            return response()->json(['error' => 'Erreur l\'or de la mise a jour de l\'ecole: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code_ecole)

    {
        //
        try{
            DB::beginTransaction();
            $ecole = Ecole::findOrfail($code_ecole);
            $ecole->delete();
            DB::commit();
            return response()->json(null,200);

        }catch(Throwable $th){
            DB::rollBack();
            return response()->json(['erreur' => 'Erreur l\'or de la suppression:' .$th->getMessage()],500);
       }
   }
       
}

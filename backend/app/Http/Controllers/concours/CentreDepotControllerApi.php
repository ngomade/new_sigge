<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\CentreDepot;

use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Http\Request;

class CentreDepotControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $centre = CentreDepot::All();
        return response()->json($centre,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateData = $request->validate([
            'centre_depot_label' => 'required|String|max:255'

        ]);
        try{
            DB::beginTransaction();
            $res = CentreDepot::create($request->all());
            DB::commit();
            return response()->json($res,200);

        }catch(Throwable $th){
            DB::rollback();
            return response()->json(['erreur ' => 'erreur l\'or de l\'enregistrement du centre'],500);
            

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $centre= CentreDepot::findOrfail($id);
        if(!$centre){
            return response()->json(['erreur' => 'centre non trouve'],404);
        }
        return response()->json($centre,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $centre_depot_code)
    {
        //
        $validateData = $request->validate([
            'centre_depot_label' => 'required|String|max:255'

        ]);
        try{
            DB::beginTransaction();
            $centre = CentreDepot::findOrfail($centre_depot_code);
            $centre->update($request->all());
            DB::commit();
            return response()->json($centre,200);

        }catch(Throwable $th){
            DB::rollback();
            return response()->json(['erreur ' => 'erreur l\'or de la mse a jour du centre'],500);
            

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $centre_depot_code)
    {
        //
        try{
            $centre = CentreDepot::findOrfail($centre_depot_code);
            $centre->delete();
            DB::commit();
            return response()->json(['succes' => 'centre supprime'],200);

        }catch(Throwable $th){
            DB::rollback();
            return response()->json(['erreur' => 'erreur lors de la suppression'],500);
        }
    }
}

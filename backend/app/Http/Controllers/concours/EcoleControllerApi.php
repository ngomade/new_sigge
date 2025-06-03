<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\Ecole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EcoleControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ecole = Ecole::All();
        //with(["candidat", "centre_depot", "centre_examen", 'ecole_element', 'site_composition'])->get();
        return response()->json($ecole);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'code_ecole' => 'required|string|max:32',
            'label_ecole' => 'required|string|max:128',
            'logo_ecole' => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'desc_ecole' => 'required|string|max:255',
            'tel_ecole' => 'required|string|max:32',
            'email_ecole' => 'nullable|email|max:128',
            'bp_ecole' => 'required|string|max:128',
            'centre_depot_code' => 'required|exists:centre_depot,centre_depot_code',
            'sites_composition' => 'sometimes|array',
            'sites_composition.*' => "required|exists:site_composition,code_site",
            'candidat' => 'sometimes|array',
            'candidat.*' => "required|exists:candidat,ca_code",
        ]);

        try {
            DB::beginTransaction();
            $validatedData["logo_ecole"] = $this->storageLogo($request, $validatedData["label_ecole"]);

            $res = Ecole::create($validatedData);
            if ($request->has('sites_composition')) {
                $res->ecole_elements()->attach($request->sites_composition);
            }

            DB::commit();
            return response()->json($res);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating ecole : ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'enregistrement de l\'ecole '], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ecole = Ecole::findOrfail($id);

        return response()->json($ecole->load(['centre_depot', 'centre_examen', 'ecole_elements', 'sites_composition']));
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, string $code_ecole)
    {
        //
        $validatedData = $request->validate([
            'code_ecole' => 'sometimes|string|max:32',
            'label_ecole' => 'sometimes|string|max:128',
            'logo_ecole' => 'sometimes|mimes:png,jpg,jpeg|max:512',
            'desc_ecole' => 'sometimes|string|max:255',
            'tel_ecole' => 'sometimes|string|max:32',
            'email_ecole' => 'nullable|email|max:128',
            'bp_ecole' => 'sometimes|string|max:128',
            'centre_depot' => 'sometimes|exists:centre_depot,centre_depot_code',
            
            'sites_composition.*' => "required|exists:site_composition,code_site",
            'sites_composition' => 'sometimes|array',
            'sites_composition.*' => "required|exists:site_composition,code_site",
            'ecole_element' => 'sometimes|array',
            'ecole_element.*' => "required|exists:dossier,code_el",
        ]);
        $ecole = Ecole::findOrfail($code_ecole);
        try {
            DB::beginTransaction();

            if ($request->hasFile('logo_ecole')) {
                Storage::delete($ecole->logo_ecole);
                $validatedData["logo_ecole"] = $this->storageLogo($request, $ecole->label_ecole);
            }
            $ecole->update($validatedData);
            if ($request->has('sites_composition')) {
                $ecole->ecole_elements()->sync($request->sites_composition);
            }
            DB::commit();

            return response()->json($ecole);

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating ecole : ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de la mise a jour de l\'ecole'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(string $code_ecole)

    {
        //
        $ecole = Ecole::findOrfail($code_ecole);
        try {
            DB::beginTransaction();
            if (Storage::exists($ecole->logo_ecole)) {
                Storage::delete($ecole->logo_ecole);
            }
            if ($ecole->ecole_elements()->exists()) {
                $ecole->ecole_elements()->detach();
            }
            $ecole->delete();
            DB::commit();
            return response()->noContent();

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting ecole : ' . $th->getMessage());
            return response()->json(['erreur' => 'Erreur lors de la suppression'], 500);
        }
    }

    /**
     * @param Request $request
     * @param $ecole
     * @return string
     */
    private function storageLogo(Request $request, $label_ecole): string
    {
        $logo_ecole = $request->file('logo_ecole');
        $filename = "logo_" . $label_ecole . "_" . now()->format("Y_m_d_H_i_s") . '.' . $logo_ecole->extension();
        return $logo_ecole->storeAs('private/logos', $filename);
    }

}

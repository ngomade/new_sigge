<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\concours\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PersonnelControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personnels = Personnel::all();
        return response()->json($personnels);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_pers' => 'required|string|max:32|unique:personnel,code_pers',
            'nom_pers' => 'required|string|max:255',
            'prenom_pers' => 'nullable|string|max:255',
            'sexe_pers' => 'required|string|max:1',
            'date_naissance_pers' => 'required|date',
            'lieu_naissance_pers' => 'required|string|max:255',
            'statut_mat_pers' => 'required|string|max:32',
            'lieu_residence_pers' => 'nullable|string|max:255',
            'first_phone_pers' => 'required|string|max:32',
            'second_phone_pers' => 'nullable|string|max:32',
            'cni_pers' => 'required|string|max:32',
            'date_deliv_cni_pers' => 'required|date',
            'email_pers' => 'required|email|max:255|unique:personnel,email_pers',
            'login_pers' => 'required|string|max:255|unique:personnel,login_pers',
            'pwd_pers' => 'required|string|min:6',
            'photo_pers' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'lang_pers' => 'nullable|string|max:10',
            'nationalite_pers' => 'nullable|string|max:255',
            'region_pers' => 'nullable|string|max:255',
            'depart_pers' => 'nullable|string|max:255',
            'arrond_pers' => 'nullable|string|max:255',
            'bibliographie_pers' => 'nullable|string',
            'nb_enfant_pers' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo_pers')) {
                $validatedData['photo_pers'] = $request->file('photo_pers')->store('private/photos_personnel');
            }

            $validatedData['pwd_pers'] = Hash::make($validatedData['pwd_pers']);

            $personnel = Personnel::create($validatedData);

            DB::commit();
            return response()->json($personnel, 201);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating personnel: ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de la création du personnel'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $personnel = Personnel::findOrFail($id);
        return response()->json($personnel);
    }

    /**
     * Update the specified resource in storage.
     * @throws Throwable
     */
    public function update(Request $request, string $id)
    {
        $personnel = Personnel::findOrFail($id);

        $validatedData = $request->validate([
            // 'code_pers' => 'sometimes|string|max:32|unique:personnel,code_pers,' . $id . ',code_pers',
            'nom_pers' => 'sometimes|string|max:255',
            'prenom_pers' => 'nullable|string|max:255',
            'sexe_pers' => 'sometimes|string|max:1',
            'date_naissance_pers' => 'sometimes|date',
            'lieu_naissance_pers' => 'sometimes|string|max:255',
            'statut_mat_pers' => 'sometimes|string|max:32',
            'lieu_residence_pers' => 'nullable|string|max:255',
            'first_phone_pers' => 'sometimes|string|max:32',
            'second_phone_pers' => 'nullable|string|max:32',
            'cni_pers' => 'sometimes|string|max:32',
            'date_deliv_cni_pers' => 'sometimes|date',
            'email_pers' => 'sometimes|email|max:255|unique:personnel,email_pers,' . $id . ',code_pers',
            'login_pers' => 'sometimes|string|max:255|unique:personnel,login_pers,' . $id . ',code_pers',
            'pwd_pers' => 'sometimes|string|min:6',
            'photo_pers' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'lang_pers' => 'nullable|string|max:10',
            'nationalite_pers' => 'nullable|string|max:255',
            'region_pers' => 'nullable|string|max:255',
            'depart_pers' => 'nullable|string|max:255',
            'arrond_pers' => 'nullable|string|max:255',
            'bibliographie_pers' => 'nullable|string',
            'nb_enfant_pers' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo_pers')) {
                if ($personnel->photo_pers && Storage::exists($personnel->photo_pers)) {
                    Storage::delete($personnel->photo_pers);
                }
                $validatedData['photo_pers'] = $request->file('photo_pers')->store('private/photos_personnel');
            }

            if (isset($validatedData['pwd_pers'])) {
                $validatedData['pwd_pers'] = Hash::make($validatedData['pwd_pers']);
            }

            $personnel->update($validatedData);

            DB::commit();
            return response()->json($personnel);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating personnel: ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de la mise à jour du personnel'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Throwable
     */
    public function destroy(string $id)
    {
        $personnel = Personnel::findOrFail($id);

        try {
            DB::beginTransaction();

            if ($personnel->photo_pers && Storage::exists($personnel->photo_pers)) {
                Storage::delete($personnel->photo_pers);
            }

            $personnel->delete();

            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting personnel: ' . $th->getMessage());
            return response()->json(['error' => 'Erreur lors de la suppression du personnel'], 500);
        }
    }
}

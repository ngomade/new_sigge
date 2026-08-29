<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PersonnelControllerApi extends Controller
{
    /**
     * Affiche la liste des personnels.
     */
    public function index()
    {
        $personnels = Personnel::all();

        return response()->json($personnels);
    }

    /**
     * Enregistre un nouveau personnel.
     *
     * @throws Throwable
     */
    public function store(StorePersonnelRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            if ($request->hasFile('photo_pers')) {
                $validatedData['photo_pers'] = $request->file('photo_pers')->store('private/photos_personnel');
            }

            $validatedData['pwd_pers'] = Hash::make($validatedData['pwd_pers']);

            $personnel = Personnel::create($validatedData);

            DB::commit();

            return response()->json($personnel);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Erreur lors de la création du personnel : '.$th->getMessage());

            return response()->json(['error' => 'Erreur lors de la création du personnel'], 500);
        }
    }

    /**
     * Affiche un personnel spécifique.
     */
    public function show(string $id)
    {
        $personnel = Personnel::findOrFail($id);

        return response()->json($personnel);
    }

    /**
     * Met à jour un personnel existant.
     *
     * @throws Throwable
     */
    public function update(UpdatePersonnelRequest $request, string $id)
    {
        $personnel = Personnel::findOrFail($id);
        $validatedData = $request->validated();

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
            Log::error('Erreur lors de la mise à jour du personnel : '.$th->getMessage());

            return response()->json(['error' => 'Erreur lors de la mise à jour du personnel'], 500);
        }
    }

    /**
     * Supprime un personnel.
     *
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
            Log::error('Erreur lors de la suppression du personnel : '.$th->getMessage());

            return response()->json(['error' => 'Erreur lors de la suppression du personnel'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\EtudiantEc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EtudiantEcControllerApi extends Controller
{
    public function index()
    {
        $etudiantEcs = EtudiantEc::all();
        return response()->json($etudiantEcs);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_user' => 'required|string|max:32|exists:user,code_user',
            'code_ec' => 'required|string|max:32|exists:ec,code_ec',
        ]);

        try {
            DB::beginTransaction();
            $etudiantEc = EtudiantEc::create($validatedData);
            DB::commit();
            return response()->json($etudiantEc);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating EtudiantEc: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating EtudiantEc'], 500);
        }
    }

    public function show(string $code_user, string $code_ec)
    {
        $etudiantEc = EtudiantEc::where('code_user', $code_user)
            ->where('code_ec', $code_ec)
            ->firstOrFail();
        return response()->json($etudiantEc);
    }

    public function update(Request $request, string $code_user, string $code_ec)
    {
        $validatedData = $request->validate([
            'code_user' => 'sometimes|string|max:32|exists:user,code_user',
            'code_ec' => 'sometimes|string|max:32|exists:ec,code_ec',
        ]);

        $etudiantEc = EtudiantEc::where('code_user', $code_user)
            ->where('code_ec', $code_ec)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $etudiantEc->update($validatedData);
            DB::commit();
            return response()->json($etudiantEc);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating EtudiantEc: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating EtudiantEc'], 500);
        }
    }

    public function destroy(string $code_user, string $code_ec)
    {
        $etudiantEc = EtudiantEc::where('code_user', $code_user)
            ->where('code_ec', $code_ec)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $etudiantEc->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting EtudiantEc: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting EtudiantEc'], 500);
        }
    }
}

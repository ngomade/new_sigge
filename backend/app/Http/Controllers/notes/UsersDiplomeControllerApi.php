<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\UsersDiplome;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsersDiplomeControllerApi extends Controller
{
    public function index()
    {
        $usersDiplomes = UsersDiplome::all();
        return response()->json($usersDiplomes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_user' => 'required|string|exists:users,code_user',
            'code_dip' => 'required|string|exists:diplome,code_dip',
            'annee_dip' => 'required|date',
            'institution_dip' => 'required|string',
            'mention_dip' => 'required|string',
            'pays_dip' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $usersDiplome = UsersDiplome::create($validatedData);
            DB::commit();
            return response()->json($usersDiplome);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating UsersDiplome: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating UsersDiplome'], 500);
        }
    }

    public function show(string $code_user, string $code_dip)
    {
        $usersDiplome = UsersDiplome::where('code_user', $code_user)
            ->where('code_dip', $code_dip)
            ->firstOrFail();
        return response()->json($usersDiplome);
    }

    public function update(Request $request, string $code_user, string $code_dip)
    {
        $validatedData = $request->validate([
            'code_user' => 'sometimes|string|exists:users,code_user',
            'code_dip' => 'sometimes|string|exists:diplome,code_dip',
            'annee_dip' => 'sometimes|date',
            'institution_dip' => 'sometimes|string',
            'mention_dip' => 'sometimes|string',
            'pays_dip' => 'sometimes|string',
        ]);

        $usersDiplome = UsersDiplome::where('code_user', $code_user)
            ->where('code_dip', $code_dip)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $usersDiplome->update($validatedData);
            DB::commit();
            return response()->json($usersDiplome);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating UsersDiplome: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating UsersDiplome'], 500);
        }
    }

    public function destroy(string $code_user, string $code_dip)
    {
        $usersDiplome = UsersDiplome::where('code_user', $code_user)
            ->where('code_dip', $code_dip)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $usersDiplome->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting UsersDiplome: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting UsersDiplome'], 500);
        }
    }
}

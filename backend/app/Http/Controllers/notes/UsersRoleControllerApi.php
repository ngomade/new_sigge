<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use App\Models\UsersRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsersRoleControllerApi extends Controller
{
    public function index()
    {
        $usersRoles = UsersRole::all();

        return response()->json($usersRoles);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_user' => 'required|string|exists:users,code_user',
            'id_role' => 'required|string|exists:role,id_role',
            'date_debut_role' => 'required|date',
            'date_fin_role' => 'sometimes|nullable|date',
            'etat_role' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $usersRole = UsersRole::create($validatedData);
            DB::commit();

            return response()->json($usersRole);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating UsersRole: '.$th->getMessage());

            return response()->json(['error' => 'Error creating UsersRole'], 500);
        }
    }

    public function show(string $code_user, string $id_role)
    {
        $usersRole = UsersRole::where('code_user', $code_user)
            ->where('id_role', $id_role)
            ->firstOrFail();

        return response()->json($usersRole);
    }

    public function update(Request $request, string $code_user, string $id_role)
    {
        $validatedData = $request->validate([
            'code_user' => 'sometimes|string|exists:users,code_user',
            'id_role' => 'sometimes|string|exists:role,id_role',
            'date_debut_role' => 'sometimes|date',
            'date_fin_role' => 'sometimes|nullable|date',
            'etat_role' => 'sometimes|integer',
        ]);

        $usersRole = UsersRole::where('code_user', $code_user)
            ->where('id_role', $id_role)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $usersRole->update($validatedData);
            DB::commit();

            return response()->json($usersRole);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating UsersRole: '.$th->getMessage());

            return response()->json(['error' => 'Error updating UsersRole'], 500);
        }
    }

    public function destroy(string $code_user, string $id_role)
    {
        $usersRole = UsersRole::where('code_user', $code_user)
            ->where('id_role', $id_role)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            $usersRole->delete();
            DB::commit();

            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting UsersRole: '.$th->getMessage());

            return response()->json(['error' => 'Error deleting UsersRole'], 500);
        }
    }
}

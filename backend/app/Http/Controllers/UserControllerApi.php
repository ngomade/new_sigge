<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserControllerApi extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $user = User::create($validatedData);
            DB::commit();
            return response()->json($user);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating User: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating User'], 500);
        }
    }

    public function show(string $code_user)
    {
        $user = User::findOrFail($code_user);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, string $code_user)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($code_user);

        try {
            DB::beginTransaction();
            $user->update($validatedData);
            DB::commit();
            return response()->json($user);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating User: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating User'], 500);
        }
    }

    public function destroy(string $code_user)
    {
        $user = User::findOrFail($code_user);

        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting User: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting User'], 500);
        }
    }
}

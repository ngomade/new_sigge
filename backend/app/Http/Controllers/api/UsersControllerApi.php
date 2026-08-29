<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsersControllerApi extends Controller
{
    public function index()
    {
        $users = Users::all();

        return response()->json($users);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $user = Users::create($validatedData);
            DB::commit();

            return response()->json($user);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating User: '.$th->getMessage());

            return response()->json(['error' => 'Error creating User'], 500);
        }
    }

    public function show(string $code_user)
    {
        $user = Users::findOrFail($code_user);

        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, string $code_user)
    {
        $validatedData = $request->validated();

        $user = Users::findOrFail($code_user);

        try {
            DB::beginTransaction();
            $user->update($validatedData);
            DB::commit();

            return response()->json($user);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating User: '.$th->getMessage());

            return response()->json(['error' => 'Error updating User'], 500);
        }
    }

    public function destroy(string $code_user)
    {
        $user = Users::findOrFail($code_user);

        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();

            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting User: '.$th->getMessage());

            return response()->json(['error' => 'Error deleting User'], 500);
        }
    }
}

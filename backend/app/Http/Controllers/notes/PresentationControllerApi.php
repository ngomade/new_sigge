<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Presentation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PresentationControllerApi extends Controller
{
    public function index()
    {
        $presentations = Presentation::all();
        return response()->json($presentations);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_bureau' => 'required|string|max:128|exists:bureau,code_bureau',
            'photo_chef' => 'required|string|max:128',
            'message_chef' => 'required|string|max:2000',
            'cursus_ing' => 'sometimes|nullable|string',
            'grille_ing' => 'sometimes|nullable|string',
            'science_ing' => 'sometimes|nullable|string',
            'grille_science' => 'sometimes|nullable|string',
            'nom_chef' => 'required|string|max:128',
        ]);

        try {
            DB::beginTransaction();
            $presentation = Presentation::create($validatedData);
            DB::commit();
            return response()->json($presentation);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Presentation: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Presentation'], 500);
        }
    }

    public function show(int $code_pres)
    {
        $presentation = Presentation::findOrFail($code_pres);
        return response()->json($presentation);
    }

    public function update(Request $request, int $code_pres)
    {
        $validatedData = $request->validate([
            'code_bureau' => 'sometimes|string|max:128|exists:bureau,code_bureau',
            'photo_chef' => 'sometimes|string|max:128',
            'message_chef' => 'sometimes|string|max:2000',
            'cursus_ing' => 'sometimes|nullable|string',
            'grille_ing' => 'sometimes|nullable|string',
            'science_ing' => 'sometimes|nullable|string',
            'grille_science' => 'sometimes|nullable|string',
            'nom_chef' => 'sometimes|string|max:128',
        ]);

        $presentation = Presentation::findOrFail($code_pres);

        try {
            DB::beginTransaction();
            $presentation->update($validatedData);
            DB::commit();
            return response()->json($presentation);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Presentation: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Presentation'], 500);
        }
    }

    public function destroy(int $code_pres)
    {
        $presentation = Presentation::findOrFail($code_pres);

        try {
            DB::beginTransaction();
            $presentation->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Presentation: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Presentation'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\concours\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SlideControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = Slide::with('personnel')->get();

        return response()->json($slides);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'first_title' => 'required|string',
            'second_title' => 'required|string',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'code_pers' => 'required|string|exists:personnel,code_pers',
        ]);

        try {
            $slide = Slide::create($validateData);

            return response()->json($slide->load('personnel'));
        } catch (Throwable $th) {
            Log::error('Error creating slide: '.$th->getMessage());

            return response()->json(['erreur' => 'Erreur lors de la création du slide'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $slide = Slide::with('personnel')->findOrFail($id);

        return response()->json($slide);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'first_title' => 'sometimes|string',
            'second_title' => 'sometimes|string',
            'photo' => 'sometimes|string',
            'code_pers' => 'sometimes|string|exists:personnel,code_pers',
        ]);

        $slide = Slide::findOrFail($id);
        try {
            $slide->update($validateData);

            return response()->json($slide->load('personnel'));
        } catch (Throwable $th) {
            Log::error('Error updating slide: '.$th->getMessage());

            return response()->json(['erreur' => 'Erreur lors de la mise à jour du slide: '.$th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slide = Slide::findOrFail($id);
        try {
            $slide->delete();

            return response()->noContent();
        } catch (Throwable $th) {
            Log::error('Error deleting slide: '.$th->getMessage());

            return response()->json(['erreur' => 'Erreur lors de la suppression du slide: '.$th->getMessage()], 500);
        }
    }
}

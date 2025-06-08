<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DocumentsControllerApi extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code_session' => 'required|string|max:32|exists:session_examen,code_session',
            'code_bureau' => 'required|string|max:128|exists:bureau,code_bureau',
            'label_doc' => 'required|string|max:128',
            'description_doc' => 'sometimes|nullable|string',
            'type_doc' => 'required|string|max:128',
            'nom_fichier' => 'required|string|max:128',
        ]);

        try {
            DB::beginTransaction();
            $document = Document::create($validatedData);
            DB::commit();
            return response()->json($document);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error creating Document: ' . $th->getMessage());
            return response()->json(['error' => 'Error creating Document'], 500);
        }
    }

    public function show(int $code_doc)
    {
        $document = Document::findOrFail($code_doc);
        return response()->json($document);
    }

    public function update(Request $request, int $code_doc)
    {
        $validatedData = $request->validate([
            'code_session' => 'sometimes|string|max:32|exists:session_examen,code_session',
            'code_bureau' => 'sometimes|string|max:128|exists:bureau,code_bureau',
            'label_doc' => 'sometimes|string|max:128',
            'description_doc' => 'sometimes|nullable|string',
            'type_doc' => 'sometimes|string|max:128',
            'nom_fichier' => 'sometimes|string|max:128',
        ]);

        $document = Document::findOrFail($code_doc);

        try {
            DB::beginTransaction();
            $document->update($validatedData);
            DB::commit();
            return response()->json($document);
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error updating Document: ' . $th->getMessage());
            return response()->json(['error' => 'Error updating Document'], 500);
        }
    }

    public function destroy(int $code_doc)
    {
        $document = Document::findOrFail($code_doc);

        try {
            DB::beginTransaction();
            $document->delete();
            DB::commit();
            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Error deleting Document: ' . $th->getMessage());
            return response()->json(['error' => 'Error deleting Document'], 500);
        }
    }
}

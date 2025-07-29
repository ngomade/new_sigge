<?php

namespace App\Http\Controllers\notes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\notes\Classe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\concours\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::with('user')->paginate(10);
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'enseignant')->get();
        return view('classes.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label_class' => 'required|string|max:100',
            'code_user' => 'required|exists:users,code_user'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Classe::create([
                'code_class' => Str::uuid(),
                'label_class' => $request->label_class,
                'code_user' => $request->code_user
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Classe créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la classe.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($code_class)
    {
        $classe = Classe::with(['user', 'niveaux'])->findOrFail($code_class);
        return view('classes.show', compact('classe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($code_class)
    {
        $classe = Classe::findOrFail($code_class);
        $users = User::where('role', 'enseignant')->get();
        return view('classes.edit', compact('classe', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $code_class)
    {
        $validator = Validator::make($request->all(), [
            'label_class' => 'required|string|max:100',
            'code_user' => 'required|exists:users,code_user'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $classe = Classe::findOrFail($code_class);
            $classe->update([
                'label_class' => $request->label_class,
                'code_user' => $request->code_user
            ]);

            return redirect()->route('classes.index')
                ->with('success', 'Classe modifiée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification de la classe.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($code_class)
    {
        try {
            $classe = Classe::findOrFail($code_class);
            $classe->delete();

            return redirect()->route('classes.index')
                ->with('success', 'Classe supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la classe.');
        }
    }
}
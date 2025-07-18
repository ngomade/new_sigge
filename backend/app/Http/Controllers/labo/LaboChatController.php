<?php

namespace App\Http\Controllers\labo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laboratoires\LaboChat;
use Illuminate\Support\Facades\Auth;

class LaboChatController extends Controller
{
    // Affiche la page de chat (section ou page dédiée)
    public function index($code_lab)
    {
        $laboratoire = \App\Models\laboratoires\Laboratoire::where('code_lab', $code_lab)->firstOrFail();
        // On peut charger les 30 derniers messages pour l'affichage initial
        $messages = LaboChat::where('code_lab', $code_lab)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->reverse(); // Pour affichage du plus ancien au plus récent
        return view('laboratoires.public.chat', compact('messages', 'code_lab', 'laboratoire'));
    }

    // API : récupérer les messages (JSON, pour rafraîchissement ou temps réel)
    public function fetch($code_lab)
    {
        $messages = LaboChat::where('code_lab', $code_lab)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->reverse();
        return response()->json($messages);
    }

    // API : envoyer un message
    public function send(Request $request, $code_lab)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        $userId = session('user_id');
        $userType = session('user_type');
        if (!$userId || !$userType) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Non authentifié'], 401);
            }
            return redirect()->route('chat.index', $code_lab)->with('error', 'Non authentifié');
        }
        $msg = LaboChat::create([
            'code_lab' => $code_lab,
            'id_expediteur' => $userId,
            'type_expediteur' => $userType,
            'message' => $request->message,
        ]);
        // Pour le temps réel, on pourra broadcaster ici
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($msg);
        }
        // Fallback : redirige vers le chat si submit natif
        return redirect()->route('chat.index', $code_lab)->with('success', 'Message envoyé !');
    }
}

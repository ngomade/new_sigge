@extends('laboratoires.public.layout')

@section('content')
<style>
    body, .chat-app-bg {
        background: linear-gradient(135deg, #f8fafc 70%, #e3e6f0 100%) !important;
    }
    .chat-app-card {
        border-radius: 1.5em;
        box-shadow: 0 4px 24px rgba(52,152,219,0.10);
        overflow: hidden;
        background: rgba(255,255,255,0.98);
        border: none;
    }
    .chat-header {
        background: linear-gradient(90deg, #3498db 80%, #6dd5fa 100%);
        color: #fff;
        border-bottom: 1px solid #e3e6f0;
        padding: 1.1em 1.5em;
        font-size: 1.2em;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chat-messages-area {
        height: 60vh;
        min-height: 340px;
        max-height: 65vh;
        overflow-y: auto;
        background: linear-gradient(135deg, #f8fafc 80%, #e3e6f0 100%);
        padding: 2em 1.5em 1em 1.5em;
        display: flex;
        flex-direction: column;
        gap: 0.7em;
        scroll-behavior: smooth;
    }
    .chat-row {
        display: flex;
        align-items: flex-end;
        gap: 0.7em;
        margin-bottom: 0.2em;
        transition: background 0.2s;
    }
    .chat-row.me { flex-direction: row-reverse; }
    .chat-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e3e6f0 60%, #d0e6fa 100%);
        color: #3498db;
        font-weight: bold;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1em;
        box-shadow: 0 1px 4px rgba(52,152,219,0.10);
        border: 2px solid #fff;
    }
    .chat-bubble {
        border-radius: 1.2em 1.2em 1.2em 0.3em;
        box-shadow: 0 2px 8px rgba(52,152,219,0.08);
        word-break: break-word;
        padding: 0.7em 1.1em;
        max-width: 70vw;
        min-width: 60px;
        font-size: 1.05em;
        position: relative;
        transition: background 0.2s;
    }
    .chat-bubble.me {
        background: linear-gradient(135deg, #3498db 60%, #6dd5fa 100%);
        color: #fff;
        border-bottom-right-radius: 0.3em !important;
        border-bottom-left-radius: 1.2em !important;
        align-self: flex-end;
    }
    .chat-bubble.other {
        background: rgba(255,255,255,0.95);
        color: #333;
        border-bottom-left-radius: 0.3em !important;
        border-bottom-right-radius: 1.2em !important;
        border: 1px solid #e3e6f0;
        align-self: flex-start;
    }
    .chat-bubble .chat-meta {
        font-size: 0.85em;
        color: #8ca0b3;
        margin-top: 0.2em;
        text-align: right;
    }
    .chat-bubble .chat-author {
        font-size: 0.93em;
        font-weight: 600;
        color: #3498db;
        margin-bottom: 0.1em;
    }
    .chat-bubble.me .chat-author {
        color: #fff;
    }
    .chat-footer {
        background: #f8fafc;
        border-top: 1px solid #e3e6f0;
        padding: 1em 1.5em;
        position: relative;
    }
    .chat-input-group {
        display: flex;
        align-items: center;
        gap: 0.7em;
    }
    .chat-input {
        flex: 1;
        border-radius: 2em;
        border: 1px solid #d0e6fa;
        padding: 0.7em 1.2em;
        font-size: 1.08em;
        background: #fff;
        transition: border 0.2s;
    }
    .chat-input:focus {
        border: 1.5px solid #3498db;
        outline: none;
        background: #f8faff;
    }
    .chat-send-btn {
        border-radius: 50%;
        width: 44px; height: 44px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #3498db 60%, #6dd5fa 100%);
        color: #fff;
        border: none;
        font-size: 1.3em;
        box-shadow: 0 2px 8px rgba(52,152,219,0.10);
        transition: background 0.2s, box-shadow 0.2s;
    }
    .chat-send-btn:active, .chat-send-btn:focus {
        background: #2980b9;
        color: #fff;
        outline: none;
    }
    #chat-sending { font-size: 0.95em; color: #8ca0b3; margin-top: 0.3em; }
    @media (max-width: 600px) {
        .chat-app-card { border-radius: 0.7em; }
        .chat-header, .chat-footer { padding: 0.7em 0.7em; }
        .chat-messages-area { padding: 1em 0.5em 0.5em 0.5em; }
        .chat-bubble { font-size: 0.97em; }
        .chat-avatar { width: 30px; height: 30px; font-size: 0.95em; }
    }
</style>
<div class="container py-4 chat-app-bg">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card chat-app-card mb-4">
                <div class="chat-header">
                    <span><i class='bx bx-chat'></i> Salon général du laboratoire</span>
                    <a href="{{ route('laboratoires.espace.membre', $code_lab) }}" class="btn btn-outline-light btn-sm"><i class='bx bx-arrow-back'></i> Retour</a>
                </div>
                <div class="chat-messages-area" id="chat-messages">
                    @foreach($messages as $msg)
                        @php
                            $isMe = session('user_id') == $msg->id_expediteur && session('user_type') == $msg->type_expediteur;
                            $expediteur = null;
                            if($msg->type_expediteur === 'personnel') {
                                $expediteur = \App\Models\Personnel::find($msg->id_expediteur);
                                $nom = $expediteur ? $expediteur->nom_pers . ' ' . $expediteur->prenom_pers : 'Personnel';
                            } elseif($msg->type_expediteur === 'user') {
                                $expediteur = \App\Models\Users::find($msg->id_expediteur);
                                $nom = $expediteur ? $expediteur->nom_user . ' ' . $expediteur->prenom_user : 'Utilisateur';
                            } elseif($msg->type_expediteur === 'externe') {
                                $expediteur = \App\Models\laboratoires\UserExterne::find($msg->id_expediteur);
                                $nom = $expediteur ? $expediteur->nom_user_ext . ' ' . $expediteur->prenom_user_ext : 'Externe';
                            } else {
                                $nom = 'Membre';
                            }
                            $initiales = collect(explode(' ', $nom))->map(fn($n) => mb_substr($n,0,1))->join('');
                        @endphp
                        <div class="chat-row {{ $isMe ? 'me' : 'other' }}">
                            <div class="chat-avatar" title="{{ $isMe ? 'Moi' : $nom }}">{{ $isMe ? 'M' : $initiales }}</div>
                            <div class="chat-bubble {{ $isMe ? 'me' : 'other' }}">
                                <div class="chat-author">{{ $isMe ? 'Moi' : $nom }}</div>
                                <div>{{ $msg->message }}</div>
                                <div class="chat-meta">{{ $msg->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="chat-footer">
                    <form id="chat-form" action="{{ route('chat.send', $code_lab) }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="chat-input-group">
                            <input type="text" name="message" id="chat-message" class="form-control chat-input" placeholder="Écrire un message..." maxlength="2000" required>
                            <button class="chat-send-btn" type="submit" id="chat-send-btn"><i class='bx bx-send'></i></button>
                        </div>
                        <div id="chat-sending" style="display:none;">Envoi...</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('Chat JS chargé');
function scrollToBottom() {
    var chat = document.getElementById('chat-messages');
    chat.scrollTop = chat.scrollHeight;
}
scrollToBottom();

let chatInterval = setInterval(fetchChat, 3000);
function fetchChat() {
    fetch("{{ route('chat.fetch', $code_lab) }}")
        .then(r => r.json())
        .then(data => {
            let html = '';
            data.forEach(function(msg) {
                let isMe = (msg.id_expediteur == {{ session('user_id') }} && msg.type_expediteur == '{{ session('user_type') }}');
                let nom = msg.nom_expediteur || (isMe ? 'Moi' : 'Membre');
                let initiales = nom.split(' ').map(n => n.charAt(0)).join('');
                html += `<div class="chat-row ${isMe ? 'me' : 'other'}">
                    <div class="chat-avatar" title="${isMe ? 'Moi' : nom}">${isMe ? 'M' : initiales}</div>
                    <div class="chat-bubble ${isMe ? 'me' : 'other'}">
                        <div class="chat-author">${isMe ? 'Moi' : nom}</div>
                        <div>${msg.message}</div>
                        <div class="chat-meta">${(new Date(msg.created_at)).toLocaleString('fr-FR')}</div>
                    </div>
                </div>`;
            });
            document.getElementById('chat-messages').innerHTML = html;
            scrollToBottom();
        });
}

const chatForm = document.getElementById('chat-form');
chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    let input = document.getElementById('chat-message');
    let message = input.value.trim();
    if(!message) return false;
    document.getElementById('chat-send-btn').disabled = true;
    document.getElementById('chat-sending').style.display = '';
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': this.querySelector('[name=_token]').value,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({message})
    })
    .then(() => {
        input.value = '';
        input.focus();
        document.getElementById('chat-send-btn').disabled = false;
        document.getElementById('chat-sending').style.display = 'none';
        fetchChat();
    });
    return false;
});
chatForm.onsubmit = function() { return false; };
</script>
@endpush

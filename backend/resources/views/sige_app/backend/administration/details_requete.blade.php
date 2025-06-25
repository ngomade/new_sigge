@extends("sige_app.backend.template.backend")
@section("js")
    <!-- Script pour le compteur de caractères -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('text_repone');
    const charCount = document.getElementById('char-count');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const remaining = 180 - this.value.length;
            charCount.textContent = remaining;
            charCount.className = remaining < 20 ? 'text-danger' : remaining < 50 ? 'text-warning' : 'text-muted';
        });
    }

    // Email notification toggle buttons for status update
    const activateBtn = document.getElementById('activateEmail');
    const deactivateBtn = document.getElementById('deactivateEmail');
    const emailInput = document.getElementById('email_notifications');

    if (activateBtn && deactivateBtn && emailInput) {
        activateBtn.addEventListener('click', function() {
            emailInput.value = '1';
            activateBtn.classList.add('btn-success');
            activateBtn.classList.remove('btn-outline-success');
            deactivateBtn.classList.add('btn-outline-danger');
            deactivateBtn.classList.remove('btn-danger');
            alert('Mail active');
        });

        deactivateBtn.addEventListener('click', function() {
            emailInput.value = '0';
            deactivateBtn.classList.add('btn-danger');
            deactivateBtn.classList.remove('btn-outline-danger');
            activateBtn.classList.add('btn-outline-success');
            activateBtn.classList.remove('btn-success');
            alert('Le mail désactivé');
        });

        // Initialize buttons based on current value
        if (emailInput.value === '1') {
            activateBtn.classList.add('btn-success');
            activateBtn.classList.remove('btn-outline-success');
            deactivateBtn.classList.add('btn-outline-danger');
            deactivateBtn.classList.remove('btn-danger');
        } else {
            deactivateBtn.classList.add('btn-danger');
            deactivateBtn.classList.remove('btn-outline-danger');
            activateBtn.classList.add('btn-outline-success');
            activateBtn.classList.remove('btn-success');
        }
    }

    // Email notification toggle buttons for response
    const activateBtnResponse = document.getElementById('activateEmailResponse');
    const deactivateBtnResponse = document.getElementById('deactivateEmailResponse');
    const emailInputResponse = document.getElementById('email_notifications_response');

    if (activateBtnResponse && deactivateBtnResponse && emailInputResponse) {
        activateBtnResponse.addEventListener('click', function() {
            emailInputResponse.value = '1';
            activateBtnResponse.classList.add('btn-success');
            activateBtnResponse.classList.remove('btn-outline-success');
            deactivateBtnResponse.classList.add('btn-outline-danger');
            deactivateBtnResponse.classList.remove('btn-danger');
            alert('Mail de réponse activé');
        });

        deactivateBtnResponse.addEventListener('click', function() {
            emailInputResponse.value = '0';
            deactivateBtnResponse.classList.add('btn-danger');
            deactivateBtnResponse.classList.remove('btn-outline-danger');
            activateBtnResponse.classList.add('btn-outline-success');
            activateBtnResponse.classList.remove('btn-success');
            alert('Mail de réponse désactivé');
        });

        // Initialize buttons based on current value
        if (emailInputResponse.value === '1') {
            activateBtnResponse.classList.add('btn-success');
            activateBtnResponse.classList.remove('btn-outline-success');
            deactivateBtnResponse.classList.add('btn-outline-danger');
            deactivateBtnResponse.classList.remove('btn-danger');
        } else {
            deactivateBtnResponse.classList.add('btn-danger');
            deactivateBtnResponse.classList.remove('btn-outline-danger');
            activateBtnResponse.classList.add('btn-outline-success');
            activateBtnResponse.classList.remove('btn-success');
        }
    }
});
</script>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header with navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.requetes.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
            <svg class="bi bi-arrow-left me-2" width="16" height="16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H2.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 1 1 .708.708L2.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
            </svg>
            Retour à la liste
        </a>
        <h1 class="h3 mb-0">{{ $requete->code_requete }}</h1>
    </div>

    {{-- <!-- Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

    <div class="row">
        <!-- Main content -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    Informations de la requête
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Code de la requête</label>
                            <p class="form-control-plaintext font-monospace">{{ $requete->code_requete }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Statut actuel</label>
                            @php
                                $statusColors = [
                                    'en attente' => 'bg-warning text-dark',
                                    'en cours' => 'bg-primary text-white',
                                    'traitée' => 'bg-success text-white',
                                    'rejetée' => 'bg-danger text-white',
                                   
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$requete->status] ?? 'bg-light text-dark' }}">
                                {{ ucfirst($requete->status) }}
                            </span>
                        </div>
                       
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Catégorie</label>
                            <p class="form-control-plaintext">{{ $requete->category->label_cat ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Bureau assigné</label>
                            <p class="form-control-plaintext">{{ $requete->bureau->label_bureau ?? 'N/A' }}</p>
                        </div>
                        {{-- <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Priorité</label>
                            @php
                                $priorityColors = [
                                    'basse' => 'bg-secondary text-white',
                                    'normale' => 'bg-primary text-white',
                                    'haute' => 'bg-warning text-dark',
                                    'urgente' => 'bg-danger text-white'
                                ];
                            @endphp
                            <span class="badge {{ $priorityColors[$requete->priorite] ?? 'bg-light text-dark' }}">
                                {{ ucfirst($requete->priorite) }}
                            </span>
                        </div> --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date de soumission</label>
                            <p class="form-control-plaintext">{{ $requete->date_sousmis ? $requete->date_sousmis->format('d/m/Y à H:i') : 'N/A' }}</p>
                        </div>
                        @if($requete->date_asignation)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date d'assignation</label>
                            <p class="form-control-plaintext">{{ $requete->date_asignation->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif
                        @if($requete->date_traitement)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Date de traitement</label>
                            <p class="form-control-plaintext">{{ $requete->date_traitement->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif
                    </div>
                    @if($requete->description)
                    <div class="mt-4">
                        <h5>Description</h5>
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-0">{{ $requete->description }}</p>
                        </div>
                    </div>
                    @endif
                    @if($requete->note_interne)
                    <div class="mt-4">
                        <h5>Note interne</h5>
                        <div class="border-start border-4 border-warning bg-warning p-3 rounded">
                            <p class="mb-0 text-dark">{{ $requete->note_interne }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if($requete->fichiers && $requete->fichiers->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    Fichiers joints
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($requete->fichiers as $fichier)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <svg class="bi bi-file-earmark-text me-2" width="16" height="16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5z"/>
                                    <path fill-rule="evenodd" d="M9.5 3v2a1 1 0 0 0 1 1h2l-3-3z"/>
                                    <path d="M3 10h10v1H3v-1zm0-2h10v1H3v-1zm0-2h7v1H3V6z"/>
                                </svg>
                                {{ $fichier->nom_fichier }}
                            </div>
                            <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-link btn-sm">Examiner</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="card mb-4">
                <div class="card-header">
                    Réponses et suivi
                </div>
                <div class="card-body">
                    @if($requete->reponses && $requete->reponses->count() > 0)
                    <ul class="list-group mb-3">
                        @foreach($requete->reponses as $reponse)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                     <strong>{{ $reponse->createdBy->nom_pers ?? 'Admin' }}</strong> 
                                    <p class="mb-0">{{ $reponse->text_reponse }}</p>
                                </div>
                                <small class="text-muted">{{ $reponse->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted">Aucune réponse pour le moment.</p>
                    @endif
                    <form action="{{ route('admin.requetes.addResponse', $requete->code_requete) }}" method="POST" class="border-top pt-3" id="addResponseForm">
                        @csrf
                        <div class="mb-3">
                            <label for="text_reponse" class="form-label">Ajouter une réponse</label>
                            <textarea name="text_reponse" id="text_repone" rows="3" required maxlength="180" class="form-control @error('text_reponse') is-invalid @enderror" placeholder="Votre réponse (max 180 caractères)...">{{ old('text_reponse') }}</textarea>
                            @error('text_reponse')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Caractères restants: <span id="char-count">180</span></div>
                        </div>
                        <div class="mb-3 d-flex gap-2">
                            <button type="button" id="activateEmailResponse" class="btn btn-outline-success flex-grow-1">Activer l'envoi des mails</button>
                            <button type="button" id="deactivateEmailResponse" class="btn btn-outline-danger flex-grow-1">Désactiver l'envoi des mails</button>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted fst-italic">Veuillez activer l'envoi des mails avant de mettre à jour le statut ou d'envoyer une réponse si vous souhaitez que le mail soit envoyé.</small>
                        </div>
                        <input type="hidden" name="email_notifications" id="email_notifications_response" value="0" />
                        <button type="submit" class="btn btn-primary">Ajouter la réponse</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    Actions rapides
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.requetes.updateStatus', $requete->code_requete) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Changer le statut</label>
                            <select name="status" id="status" class="form-select">
                                 @if($requete->status !== 'en cours')
                                    <option value="en attente" {{ $requete->status === 'en attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="en cours" {{ $requete->status === 'en cours' ? 'selected' : '' }}>En cours</option>
                                @endif 
                                 {{-- <option value="" disabled>Selectionez</option> --}}
                                <option value="traitée" {{ $requete->status === 'traitée' ? 'selected' : '' }}>Traitée</option>
                                <option value="rejetée" {{ $requete->status === 'rejetée' ? 'selected' : '' }}>Rejetée</option>
                                
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="note_interne" class="form-label">Note interne (optionnel)</label>
                            <textarea name="note_interne" id="note_interne" rows="3" maxlength="191" class="form-control" placeholder="Note interne pour l'équipe...">{{ old('note_interne') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="nouveau_bureau" class="form-label">Réassigner à un bureau (optionnel)</label>
                            <select name="nouveau_bureau" id="nouveau_bureau" class="form-select">
                                <option value="">Garder le bureau actuel</option>
                                @foreach(App\Models\Bureau::all() as $bureau)
                                    <option value="{{ $bureau->code_bureau }}">{{ $bureau->label_bureau }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 d-flex gap-2">
                            <button type="button" id="activateEmail" class="btn btn-outline-success flex-grow-1">Activer l'envoi des mails</button>
                            <button type="button" id="deactivateEmail" class="btn btn-outline-danger flex-grow-1">Désactiver l'envoi des mails</button>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted fst-italic">Veuillez activer l'envoi des mails avant de mettre à jour le statut ou d'envoyer une réponse si vous souhaitez que le mail soit envoyé.</small>
                        </div>
                        <input type="hidden" name="email_notifications" id="email_notifications" value="0" />
                        <button type="submit" class="btn btn-success w-100">Mettre à jour</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    Informations rapides
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Temps écoulé:</span>
                        <span class="fw-semibold">{{ $requete->date_sousmis ? $requete->date_sousmis->diffForHumans() : 'N/A' }}</span>
                    </div>
                    @if($requete->date_asignation)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Temps de traitement:</span>
                        <span class="fw-semibold">{{ $requete->date_asignation->diffForHumans($requete->date_sousmis, true) }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-2">
                        <span>Nombre de réponses:</span>
                        <span class="fw-semibold">{{ $requete->reponses ? $requete->reponses->count() : 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Fichiers joints:</span>
                        <span class="fw-semibold">{{ $requete->fichiers ? $requete->fichiers->count() : 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Actions avancées
                </div>
                <div class="card-body">
                    <button onclick="window.print()" class="btn btn-outline-secondary w-100 mb-2">
                        <svg class="bi bi-printer me-2" width="16" height="16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <path d="M2 7a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-2h1a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H2zm11 5v-3H3v3h10z"/>
                            <path fill-rule="evenodd" d="M11 1H5a1 1 0 0 0-1 1v3h8V2a1 1 0 0 0-1-1z"/>
                        </svg>
                        Imprimer
                    </button>
                     <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Informations personnelles de l'utilisateur</label>
                            <p class="form-control-plaintext">{{ $requete->user->nom_user ?? 'N/A' }} {{ $requete->user->prenom_user ?? '' }}</p>
                            <p class="form-text text-muted">
                                Email: 
                                @if($requete->user->email_user)
                                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $requete->user->email_user }}" target="_blank" class="text-decoration-none">{{ $requete->user->email_user }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="form-text text-muted">
                                Téléphone: 
                                @if($requete->user->first_phone_user)
                                    <a href="tel:{{ $requete->user->first_phone_user }}" class="text-decoration-none">{{ $requete->user->first_phone_user }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                            <p class="form-text text-muted">Adresse: {{ $requete->user->lieu_resi_user ?? 'N/A' }}</p>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
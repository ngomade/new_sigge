@extends("sige_app.frontend.template.frontend")
@section("js")

@endsection

@section('content')
<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-danger shadow">
            <div class="modal-header bg-danger p-2 d-flex justify-content-between align-items-center" style="color: white">
                <h5 class="modal-title mb-0" style="color: white">{{ $requete->titre_requete }}</h5>
                <a href="{{ route('requetes.index') }}" class="btn btn-secondary btn-sm">Retour</a>
            </div>
            <div class="modal-body">
                <!-- Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Détails de la requête -->
                <div class="mb-3">
                    <h6 class="text-muted">Code: {{ $requete->code_requete }}</h6>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <p class="border rounded p-3 bg-light">{{ $requete->desc_requete }}</p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Catégorie</label>
                        <p class="form-control-plaintext">{{ $requete->category->label_cat ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Bureau</label>
                        <p class="form-control-plaintext">{{ $requete->bureau->label_bureau ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Date de soumission</label>
                        <p class="form-control-plaintext">{{ $requete->date_sousmis->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Statut</label>
                        @php
                            $statusClasses = [
                                'en attente' => 'badge bg-warning text-dark',
                                'en cours' => 'badge bg-primary',
                                'traité' => 'badge bg-success',
                                'rejeté' => 'badge bg-danger'
                            ];
                        @endphp
                        <span class="{{ $statusClasses[$requete->status] ?? 'badge bg-secondary' }}">
                            {{ ucfirst($requete->status) }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Priorité</label>
                        <span class="badge {{ $requete->priorite === 'urgent' ? 'bg-danger' : 'bg-secondary' }}">
                            {{ ucfirst($requete->priorite) }}
                        </span>
                    </div>
                </div>

                <!-- Fichiers joints -->
                @if($requete->fichiers->count() > 0)
                    <div class="mb-3">
                        <h6>Fichiers joints</h6>
                        <ul class="list-group">
                            @foreach($requete->fichiers as $fichier)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        {{ $fichier->nom_original }} ({{ number_format($fichier->taille / 1024, 2) }} KB)
                                    </div>
                                    <div>
                                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">Télécharger</a>
                                        @if($requete->status === 'en attente')
                                            <form action="{{ route('requetes.deleteFichier', $fichier->id_fichier) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Réponses -->
                @if($requete->reponses && $requete->reponses->count() > 0)
                    <div class="mb-3">
                        <h6>Réponses</h6>
                        <div class="list-group">
                            @foreach($requete->reponses as $reponse)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $reponse->user->nom ?? 'Administrateur' }}</strong>
                                        <small class="text-muted">{{ $reponse->created_at->format('d/m/Y à H:i') }}</small>
                                    </div>
                                    <p>{{ $reponse->contenu }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer justify-content-between">
                <a href="{{ route('requetes.index') }}" class="btn btn-secondary">Retour à la liste</a>
                @if($requete->status === 'en attente')
                    <a href="{{ route('requetes.edit', $requete->code_requete) }}" class="btn btn-primary">Modifier</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

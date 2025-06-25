@extends("sige_app.frontend.template.frontend")

@section('css')
@endsection

@section("js")
<script>
    function printProgressTable() {
        var printContents = document.getElementById('progressTable').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
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
                {{-- Remove bootstrap alerts for flash messages since toastr will handle them --}}
                
                {{-- @if(session('success'))
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
                

                <!-- Détails de la requête -->
                <div class="mb-3">
                    <h6 class="text-muted">Code: {{ $requete->code_requete }}</h6>
                </div>

                {{-- <div class="mb-3">
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
                </div> --}}

                {{-- <!-- Fichiers joints --> 
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
                                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">Examiner</a>
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
                 @endif --}}

                <!-- Progress Tracking Table -->
                <div class="mb-4" id="progressTable">
                    <h6>Suivi du parcours de la requête</h6>
                    <button class="btn btn-sm btn-outline-primary mb-2" onclick="printProgressTable()">Imprimer</button>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Étape</th>
                                <th>Date</th>
                                <th>Bureau</th>
                                <th>Personne en charge</th>
                                <th>Expéditeur</th>
                                <th>Destinataire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($progressSteps as $step)
                            <tr>
                                <td>{{ $step['step'] }}</td>
                                <td>{{ $step['date'] ? $step['date']->format('d/m/Y à H:i') : 'Non effectué' }}</td>
                                <td>{{ $step['bureau']->label_bureau ?? 'N/A' }}</td>
                                <td>{{ $step['manager'] ? $step['manager']->nom_pers . ' ' . $step['manager']->prenom_pers : 'N/A' }}</td>
                                @if($step['step'] === 'Soumission')
                                    <td>{{ $requete->user->nom_user ?? $requete->user->nom_pers ?? 'N/A' }}</td>
                                @else
                                    <td>{{ $step['sender'] ? $step['sender']->nom ?? $step['sender']->nom_pers ?? 'N/A' : 'N/A' }}</td>
                                @endif
                                <td>{{ $step['recipient'] ? $step['recipient']->nom ?? $step['recipient']->nom_pers ?? 'N/A' : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $progressSteps->appends(request()->except('page'))->links('pagination::bootstrap-5', ['prevText' => 'Précédent', 'nextText' => 'Suivant']) }}
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
                                        <a href="{{ Storage::url($fichier->chemin) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">Examiner</a>
                                        @if($requete->status === 'en attente')
                                            <form action="{{ route('requetes.deleteFichier', $fichier->id_fichier) }}" method="POST" class="d-inline delete-fichier-form"> 
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
                {{-- @if($requete->status === 'en attente')
                    <a href="{{ route('requetes.edit', $requete->code_requete) }}" class="btn btn-primary">Modifier</a>
                @endif --}}
            </div>
        </div>
    </div>
</div>
@endsection

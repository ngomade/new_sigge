@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-folder'></i> Détails du projet - {{ $laboratoire->label_labo }}</h2>
        <div>
            <a href="{{ route('laboratoires.admin.projets.participants', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-warning">
                <i class='bx bx-group'></i> Gérer les participants
            </a>
            <a href="{{ route('laboratoires.admin.projets.documents', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-secondary">
                <i class='bx bx-file'></i> Documents
            </a>
            <a href="{{ route('laboratoires.admin.projets.edit', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-primary">
                <i class='bx bx-edit'></i> Modifier
            </a>
            <a href="{{ route('laboratoires.admin.projets', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour à la liste
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Informations du projet -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class='bx bx-info-circle'></i> Informations du projet</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Thème :</strong> {{ $projet->theme_projet }}</p>
                            <p><strong>Statut :</strong>
                                @if($projet->statut_projet == 'En cours')
                                    <span class="badge bg-success">En cours</span>
                                @elseif($projet->statut_projet == 'Terminé')
                                    <span class="badge bg-secondary">Terminé</span>
                                @elseif($projet->statut_projet == 'En pause')
                                    <span class="badge bg-warning">En pause</span>
                                @else
                                    <span class="badge bg-danger">Annulé</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date de début :</strong> {{ $projet->debut_projet ? \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') : 'Non définie' }}</p>
                            <p><strong>Date de fin :</strong> {{ $projet->fin_projet ? \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') : 'Non définie' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Description :</strong>
                        <div class="bg-light p-3 rounded mt-2">
                            {!! nl2br(e($projet->description_projet)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class='bx bx-group'></i> Participants ({{ $projet->participants->count() }})</h5>
                    <a href="{{ route('laboratoires.admin.projets.participants', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-warning">
                        <i class='bx bx-plus'></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if($projet->participants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Rôle</th>
                                        <th>Période</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projet->participants as $participant)
                                        <tr>
                                            <td>
                                                @if($participant->membre)
                                                    {{ $participant->membre->nom_complet }}
                                                @elseif($participant->userExterne)
                                                    {{ $participant->userExterne->nom_user_ext }} {{ $participant->userExterne->prenom_user_ext }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($participant->membre)
                                                    <span class="badge bg-primary">{{ $participant->membre->type_membre_label }}</span>
                                                @elseif($participant->userExterne)
                                                    <span class="badge bg-info">Externe</span>
                                                @endif
                                            </td>
                                            <td>{{ $participant->role }}</td>
                                            <td>
                                                <small>
                                                    Du {{ $participant->debut_participation ? \Carbon\Carbon::parse($participant->debut_participation)->format('d/m/Y') : 'N/A' }}
                                                    @if($participant->fin_participation)
                                                        <br>Au {{ \Carbon\Carbon::parse($participant->fin_participation)->format('d/m/Y') }}
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class='bx bx-user-x' style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucun participant pour le moment</p>
                            <a href="{{ route('laboratoires.admin.projets.participants', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-warning">
                                <i class='bx bx-plus'></i> Ajouter le premier participant
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class='bx bx-file'></i> Documents ({{ $projet->docs->count() }})</h5>
                    <a href="{{ route('laboratoires.admin.projets.documents', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-secondary">
                        <i class='bx bx-plus'></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if($projet->docs->count() > 0)
                        <div class="list-group">
                            @foreach($projet->docs as $doc)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class='bx bx-file-pdf'></i>
                                        <strong>{{ $doc->titre_doc }}</strong>
                                        <br><small class="text-muted">Ajouté le {{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class='bx bx-download'></i> Télécharger
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class='bx bx-file-x' style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucun document pour le moment</p>
                            <a href="{{ route('laboratoires.admin.projets.documents', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-secondary">
                                <i class='bx bx-plus'></i> Ajouter le premier document
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h5><i class='bx bx-cog'></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('laboratoires.admin.projets.edit', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-primary">
                            <i class='bx bx-edit'></i> Modifier le projet
                        </a>
                        <a href="{{ route('laboratoires.admin.projets.participants', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-warning">
                            <i class='bx bx-group'></i> Gérer les participants
                        </a>
                        <a href="{{ route('laboratoires.admin.projets.documents', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-secondary">
                            <i class='bx bx-file'></i> Gérer les documents
                        </a>
                        <form method="POST" action="{{ route('laboratoires.admin.projets.destroy', [$laboratoire->code_lab, $projet->code_projet]) }}" onsubmit="return confirm('Confirmer la suppression de ce projet ?')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class='bx bx-trash'></i> Supprimer le projet
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class='bx bx-info-circle'></i> Informations</h5>
                </div>
                <div class="card-body">
                    <p><strong>Code projet :</strong> {{ $projet->code_projet }}</p>
                    <p><strong>Laboratoire :</strong> {{ $projet->laboratoire->label_labo }}</p>
                    <p><strong>Créé le :</strong> {{ \Carbon\Carbon::parse($projet->created_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>Dernière modification :</strong> {{ \Carbon\Carbon::parse($projet->updated_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

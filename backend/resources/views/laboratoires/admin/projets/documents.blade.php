@extends('laboratoires.public.layout')

@section('content')
@php
    $userId = session('user_id');
    $userType = session('user_type');
    $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', session('laboratoire_code'))
        ->where('statut', 'actif')
        ->where(function ($q) use ($userId, $userType) {
            if ($userType === 'externe') {
                $q->where('id_user_externe', $userId);
            } else {
                $q->where('id_pers_lab', $userId);
            }
        })
        ->with('roleLabo')
        ->first();
    $userRole = $affectation && $affectation->roleLabo ? strtolower($affectation->roleLabo->lib_rl) : null;
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-file'></i> Gestion des documents - {{ $projet->theme_projet }}</h2>
        <div>
            @if($userRole === 'admin' || $userRole === 'chef_projet')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                    <i class='bx bx-plus'></i> Ajouter un document
                </button>
            @endif
            <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au projet
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Liste des documents -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Documents du projet ({{ $documents->total() }})</h5>
        </div>
        <div class="card-body">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Date d'ajout</th>
                                <th>Taille</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $doc)
                                <tr>
                                    <td>
                                        <strong>{{ $doc->titre_doc }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($doc->description_doc, 100) }}</small>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ number_format(filesize(storage_path('app/public/' . $doc->fichier)) / 1024, 1) }} KB</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class='bx bx-download'></i> Télécharger
                                            </a>
                                            @if($userRole === 'admin' || $userRole === 'chef_projet')
                                                <button type="button" class="btn btn-sm btn-warning" onclick="editDocument({{ $doc->id }})">
                                                    <i class='bx bx-edit'></i> Modifier
                                                </button>
                                                <form method="POST" action="{{ route('laboratoires.admin.projets.documents.destroy', [$laboratoire->code_lab, $projet->code_projet, $doc->id]) }}" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce document ?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class='bx bx-trash'></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $documents->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class='bx bx-file' style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">Aucun document pour ce projet</p>
                    @if($userRole === 'admin' || $userRole === 'chef_projet')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                            <i class='bx bx-plus'></i> Ajouter le premier document
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@if($userRole === 'admin' || $userRole === 'chef_projet')
<!-- Modal Ajout Document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">
                    <i class='bx bx-plus'></i> Ajouter un document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('laboratoires.admin.projets.documents.store', [$laboratoire->code_lab, $projet->code_projet]) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titre_doc" class="form-label">Titre du document *</label>
                        <input type="text" class="form-control @error('titre_doc') is-invalid @enderror"
                               id="titre_doc" name="titre_doc" value="{{ old('titre_doc') }}" required>
                        @error('titre_doc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description_doc" class="form-label">Description (optionnel)</label>
                        <textarea class="form-control @error('description_doc') is-invalid @enderror"
                                  id="description_doc" name="description_doc" rows="3"
                                  placeholder="Description du document...">{{ old('description_doc') }}</textarea>
                        @error('description_doc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fichier" class="form-label">Fichier *</label>
                        <input type="file" class="form-control @error('fichier') is-invalid @enderror"
                               id="fichier" name="fichier" required>
                        <div class="form-text">
                            Formats acceptés : PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, GIF (max 10MB)
                        </div>
                        @error('fichier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class='bx bx-x'></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Ajouter le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Édition Document -->
<div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDocumentModalLabel">
                    <i class='bx bx-edit'></i> Modifier le document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="editDocumentForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_titre_doc" class="form-label">Titre du document *</label>
                        <input type="text" class="form-control" id="edit_titre_doc" name="titre_doc" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description_doc" class="form-label">Description (optionnel)</label>
                        <textarea class="form-control" id="edit_description_doc" name="description_doc" rows="3"
                                  placeholder="Description du document..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_fichier" class="form-label">Nouveau fichier (optionnel)</label>
                        <input type="file" class="form-control" id="edit_fichier" name="fichier">
                        <div class="form-text">
                            Laissez vide pour conserver le fichier actuel
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class='bx bx-x'></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editDocument(docId) {
    // Ici vous devriez récupérer les données du document via AJAX
    // Pour l'instant, on utilise une approche simple
    const form = document.getElementById('editDocumentForm');
    form.action = `{{ route('laboratoires.admin.projets.documents.update', [$laboratoire->code_lab, $projet->code_projet, ':docId']) }}`.replace(':docId', docId);

    // Ouvrir le modal
    const modal = new bootstrap.Modal(document.getElementById('editDocumentModal'));
    modal.show();
}
</script>
@endif
@endsection

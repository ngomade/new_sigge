@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-file'></i> Gestion des documents - {{ $projet->theme_projet }}</h2>
        <div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                <i class='bx bx-plus'></i> Ajouter un document
            </button>
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

    <div class="card">
        <div class="card-header">
            <h5><i class='bx bx-list-ul'></i> Liste des documents ({{ $projet->docs->count() }})</h5>
        </div>
        <div class="card-body">
            @if($projet->docs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Taille</th>
                                <th>Date d'ajout</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projet->docs as $document)
                                <tr>
                                    <td>
                                        <strong>{{ $document->titre_doc }}</strong>
                                        @if($document->description_doc)
                                            <br><small class="text-muted">{{ $document->description_doc }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $extension = pathinfo($document->fichier, PATHINFO_EXTENSION);
                                            $icon = 'bx-file';
                                            if (in_array(strtolower($extension), ['pdf'])) {
                                                $icon = 'bx-file-pdf';
                                            } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                                $icon = 'bx-file-doc';
                                            } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                                $icon = 'bx-file-spreadsheet';
                                            } elseif (in_array(strtolower($extension), ['ppt', 'pptx'])) {
                                                $icon = 'bx-file-presentation';
                                            } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                                $icon = 'bx-file-image';
                                            }
                                        @endphp
                                        <i class='bx {{ $icon }}'></i>
                                        <span class="badge bg-secondary">{{ strtoupper($extension) }}</span>
                                    </td>
                                    <td>
                                        @if(file_exists(storage_path('app/public/' . $document->fichier)))
                                            @php
                                                $size = filesize(storage_path('app/public/' . $document->fichier));
                                                if ($size < 1024) {
                                                    $sizeStr = $size . ' B';
                                                } elseif ($size < 1024 * 1024) {
                                                    $sizeStr = round($size / 1024, 1) . ' KB';
                                                } else {
                                                    $sizeStr = round($size / (1024 * 1024), 1) . ' MB';
                                                }
                                            @endphp
                                            {{ $sizeStr }}
                                        @else
                                            <span class="text-muted">Fichier manquant</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($document->created_at)->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if(file_exists(storage_path('app/public/' . $document->fichier)))
                                                <a href="{{ asset('storage/' . $document->fichier) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Télécharger">
                                                    <i class='bx bx-download'></i>
                                                </a>
                                            @endif
                                            <form method="POST" action="{{ route('laboratoires.admin.projets.documents.destroy', [$laboratoire->code_lab, $projet->code_projet, $document->id_doc]) }}"
                                                  onsubmit="return confirm('Confirmer la suppression de ce document ?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class='bx bx-file-x' style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="text-muted mt-3">Aucun document</h5>
                    <p class="text-muted">Commencez par ajouter des documents au projet.</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                        <i class='bx bx-plus'></i> Ajouter le premier document
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ajout Document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('laboratoires.admin.projets.documents.store', [$laboratoire->code_lab, $projet->code_projet]) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class='bx bx-plus'></i> Ajouter un document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class='bx bx-plus'></i> Ajouter le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation du fichier
document.getElementById('fichier').addEventListener('change', function() {
    const file = this.files[0];
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'image/jpeg',
        'image/png',
        'image/gif'
    ];

    if (file) {
        if (file.size > maxSize) {
            this.setCustomValidity('Le fichier est trop volumineux (max 10MB)');
        } else if (!allowedTypes.includes(file.type)) {
            this.setCustomValidity('Type de fichier non autorisé');
        } else {
            this.setCustomValidity('');
        }
    }
});
</script>
@endsection

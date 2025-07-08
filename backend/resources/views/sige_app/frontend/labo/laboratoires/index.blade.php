@extends('sige_app.backend.template.backend')

@section('js')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mx-auto" style="max-width: 1000px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Gestion des Laboratoires</h4>
                        <a href="{{ route('labo.laboratoires.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Nouveau Laboratoire
                        </a>
                    </div>

                    <div class="card-body">
                        {{-- @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif --}}

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Projets</th>
                                        <th>Membres</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laboratoires as $laboratoire)
                                        <tr>
                                            <td>
                                                <strong>{{ $laboratoire->code_lab }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $laboratoire->label_labo }}</strong>
                                                    @if($laboratoire->logo_labo)
                                                        <br><small class="text-muted">Logo disponible</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <i class="bx bx-envelope text-muted me-1"></i>
                                                {{ $laboratoire->email_labo }}
                                            </td>
                                            <td>
                                                <i class="bx bx-phone text-muted me-1"></i>
                                                {{ $laboratoire->tel_labo }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="bx bx-folder me-1"></i>
                                                    {{ $laboratoire->projets ? $laboratoire->projets->count() : 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="bx bx-group me-1"></i>
                                                    {{ $laboratoire->membres ? $laboratoire->membres->count() : 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('labo.laboratoires.show', $laboratoire->code_lab) }}"
                                                        class="btn btn-sm btn-info" title="Voir les détails">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('labo.laboratoires.edit', $laboratoire->code_lab) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('labo.laboratoires.destroy', $laboratoire->code_lab) }}"
                                                        method="POST" class="d-inline" id="delete-form-{{ $laboratoire->code_lab }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-laboratoire"
                                                            title="Supprimer"
                                                            data-code="{{ $laboratoire->code_lab }}"
                                                            data-name="{{ $laboratoire->label_labo }}">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bx bx-info-circle fs-1"></i>
                                                <p class="mt-2">Aucun laboratoire trouvé</p>
                                                <a href="{{ route('labo.laboratoires.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-plus"></i> Créer le premier laboratoire
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($laboratoires->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                        {{ $laboratoires->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression de laboratoire -->
    <div class="modal fade" id="confirmDeleteLaboratoireModal" tabindex="-1" aria-labelledby="confirmDeleteLaboratoireModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteLaboratoireModalLabel">
                        <i class="fas fa-trash me-2"></i>Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-3">Êtes-vous sûr de vouloir supprimer ce laboratoire ?</h6>
                    <div class="alert alert-light border">
                        <div class="mb-2">
                            <strong>Code laboratoire:</strong> <span id="laboratoireCodeToDelete"></span>
                        </div>
                        <div>
                            <strong>Nom laboratoire:</strong> <span id="laboratoireNameToDelete"></span>
                        </div>
                    </div>
                    <p class="text-muted mb-0">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Cette action est irréversible. Toutes les données associées à ce laboratoire seront définitivement supprimées.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteLaboratoireBtn">
                        <i class="fas fa-trash me-1"></i>Supprimer le laboratoire
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var confirmDeleteLaboratoireModal = document.getElementById('confirmDeleteLaboratoireModal');
    var laboratoireCodeSpan = document.getElementById('laboratoireCodeToDelete');
    var laboratoireNameSpan = document.getElementById('laboratoireNameToDelete');
    var confirmDeleteLaboratoireBtn = document.getElementById('confirmDeleteLaboratoireBtn');
    var formToSubmit = null;

    // Attach click event to all delete buttons
    document.querySelectorAll('.btn-delete-laboratoire').forEach(function(button) {
        button.addEventListener('click', function() {
            var code = this.getAttribute('data-code');
            var name = this.getAttribute('data-name');
            laboratoireCodeSpan.textContent = code;
            laboratoireNameSpan.textContent = name;
            formToSubmit = document.getElementById('delete-form-' + code);
            var modal = new bootstrap.Modal(confirmDeleteLaboratoireModal);
            modal.show();
        });
    });

    // Confirm delete button submits the form
    confirmDeleteLaboratoireBtn.addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });
});
</script>

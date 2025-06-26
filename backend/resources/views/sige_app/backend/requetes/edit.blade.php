@extends('sige_app.frontend.template.frontend')
@section('js')
    <script>
        // Variables globales pour les modals
        let confirmUpdateModal, confirmDeleteModal;
        let currentDeleteForm = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation des modals
            confirmUpdateModal = new bootstrap.Modal(document.getElementById('confirmUpdateModal'));
            confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));

            // Gestion de l'upload de fichiers
            const fichiersInput = document.getElementById('fichiers');
            if (fichiersInput) {
                fichiersInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ];

                    let hasError = false;

                    files.forEach(file => {
                        if (file.size > maxSize) {
                            alert(
                                `Le fichier "${file.name}" est trop volumineux. Taille maximum : 5MB`);
                            hasError = true;
                        }

                        if (!allowedTypes.includes(file.type)) {
                            alert(`Le fichier "${file.name}" n'est pas dans un format autorisé.`);
                            hasError = true;
                        }
                    });

                    if (hasError) {
                        e.target.value = '';
                    }
                });
            }

            // Compteur de caractères pour le titre
            const titreInput = document.getElementById('titre_requete_edit');
            if (titreInput) {
                titreInput.addEventListener('input', function(e) {
                    const maxLength = 180;
                    const currentLength = e.target.value.length;
                    const remaining = maxLength - currentLength;

                    let counter = e.target.parentNode.querySelector('.char-counter');
                    if (!counter) {
                        counter = document.createElement('p');
                        counter.className = 'char-counter mt-1 text-xs text-gray-500';
                        e.target.parentNode.appendChild(counter);
                    }

                    counter.textContent = `${remaining} caractères restants`;
                    counter.className = remaining < 20 ? 'char-counter mt-1 text-xs text-red-500' :
                        'char-counter mt-1 text-xs text-gray-500';
                });
            }

            // Compteur de caractères pour la description
            const descInput = document.getElementById('desc_requete');
            if (descInput) {
                descInput.addEventListener('input', function(e) {
                    const maxLength = 180;
                    const currentLength = e.target.value.length;
                    const remaining = maxLength - currentLength;

                    let counter = e.target.parentNode.querySelector('.char-counter');
                    if (!counter) {
                        counter = document.createElement('p');
                        counter.className = 'char-counter mt-1 text-xs text-gray-500';
                        e.target.parentNode.appendChild(counter);
                    }

                    counter.textContent = `${remaining} caractères restants`;
                    counter.className = remaining < 20 ? 'char-counter mt-1 text-xs text-red-500' :
                        'char-counter mt-1 text-xs text-gray-500';
                });
            }

            // Gestion du formulaire de modification avec modal
            const form = document.getElementById('editRequeteForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    confirmUpdateModal.show();
                });
            }

            // Confirmation de la mise à jour
            document.getElementById('confirmUpdateBtn').addEventListener('click', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mise à jour...';
                }
                confirmUpdateModal.hide();
                form.submit();
            });

            // Gestion des boutons de suppression de fichiers
            document.querySelectorAll('.delete-file-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const fileName = this.getAttribute('data-file-name');
                    const deleteForm = this.closest('form');
                    
                    // Stocker la référence du formulaire
                    currentDeleteForm = deleteForm;
                    
                    // Mettre à jour le contenu du modal
                    document.getElementById('fileNameToDelete').textContent = fileName;
                    
                    // Afficher le modal
                    confirmDeleteModal.show();
                });
            });

            // Empêcher la soumission des formulaires de suppression
            document.querySelectorAll('.delete-file-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    return false;
                });
            });

            // Confirmation de la suppression
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (currentDeleteForm) {
                    // Désactiver le bouton pour éviter les doubles clics
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Suppression...';
                    
                    confirmDeleteModal.hide();
                    
                    // Créer un nouveau formulaire temporaire pour éviter les event listeners
                    const tempForm = document.createElement('form');
                    tempForm.method = 'POST';
                    tempForm.action = currentDeleteForm.action;
                    tempForm.style.display = 'none';
                    
                    // Copier les inputs CSRF et METHOD
                    const csrfToken = currentDeleteForm.querySelector('input[name="_token"]').value;
                    const methodInput = currentDeleteForm.querySelector('input[name="_method"]').value;
                    
                    tempForm.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="${methodInput}">
                    `;
                    
                    document.body.appendChild(tempForm);
                    tempForm.submit();
                }
            });

            // Réinitialiser le bouton quand le modal se ferme
            document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function() {
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Supprimer le fichier';
                currentDeleteForm = null;
            });
        });
    </script>
@endsection

@section('content')
    <div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-danger shadow">
                <div class="modal-header bg-danger p-2 d-flex justify-content-between align-items-center"
                    style="color: white">
                    <h5 class="modal-title mb-0" style="color: white">Modifier la requête</h5>
                    <a href="{{ route('requetes.show', $requete->code_requete) }}"
                        class="btn btn-secondary btn-sm">Retour</a>
                </div>

                <div class="modal-body">
                    <form action="{{ route('requetes.update', $requete->code_requete) }}" method="POST"
                        enctype="multipart/form-data" id="editRequeteForm">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="titre_requete_edit" class="form-label">Titre de la requête <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="titre_requete" id="titre_requete_edit" maxlength="180"
                                class="form-control @error('titre_requete') is-invalid @enderror"
                                value="{{ old('titre_requete', $requete->titre_requete) }}"
                                placeholder="Entrez le titre de votre requête" required>
                            @error('titre_requete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="desc_requete" class="form-label">Description détaillée <span
                                    class="text-danger">*</span></label>
                            <textarea name="desc_requete" id="desc_requete" rows="4" maxlength="180"
                                class="form-control @error('desc_requete') is-invalid @enderror" placeholder="Décrivez votre requête en détail"
                                required>{{ old('desc_requete', $requete->desc_requete) }}</textarea>
                            @error('desc_requete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="code_cat" class="form-label">Catégorie <span
                                        class="text-danger">*</span></label>
                                <select name="code_cat" id="code_cat"
                                    class="form-select @error('code_cat') is-invalid @enderror" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach ($categories as $categorie)
                                        <option value="{{ $categorie->code_cat }}"
                                            {{ old('code_cat', $requete->code_cat) == $categorie->code_cat ? 'selected' : '' }}>
                                            {{ $categorie->label_cat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('code_cat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if ($requete->fichiers->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Fichiers actuels</label>
                                <ul class="list-group">
                                    @foreach ($requete->fichiers as $fichier)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="fas fa-file me-2 text-muted"></i>
                                                {{ $fichier->nom_original }} 
                                                <small class="text-muted">({{ number_format($fichier->taille / 1024, 1) }} KB)</small>
                                            </span>
                                            <div>
                                                <a href="{{ route('requetes.downloadFichier', $fichier->id_fichier) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="fas fa-download me-1"></i>Télécharger
                                                </a>
                                                <form action="{{ route('requetes.deleteFichier', $fichier->id_fichier) }}"
                                                    method="POST" class="d-inline delete-file-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                        class="btn btn-sm btn-outline-danger delete-file-btn"
                                                        data-file-name="{{ $fichier->nom_original }}">
                                                        <i class="fas fa-trash me-1"></i>Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="fichiers" class="form-label">Ajouter de nouveaux fichiers</label>
                            <input id="fichiers" name="fichiers[]" type="file" class="form-control" multiple
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Formats acceptés : PDF, JPG, PNG, DOC, DOCX (max 5MB par fichier)
                            </small>
                            @error('fichiers.*')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><span class="text-danger">*</span> Champs obligatoires</small>
                            <div>
                                <a href="{{ route('requetes.show', $requete->code_requete) }}"
                                    class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Mettre à jour la requête
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de mise à jour -->
    <div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="confirmUpdateModalLabel">
                        <i class="fas fa-edit me-2"></i>Confirmer la modification
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-question-circle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-3">Êtes-vous sûr de vouloir modifier cette requête ?</h6>
                    <p class="text-muted mb-0">
                        Cette action mettra à jour les informations de la requête avec les nouvelles données saisies.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmUpdateBtn">
                        <i class="fas fa-check me-1"></i>Confirmer la modification
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression de fichier -->

@endsection
@extends('sige_app.frontend.template.frontend')
@section('js')
    <script>
        // Gestion de l'upload de fichiers
        document.addEventListener('DOMContentLoaded', function() {
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

            // Compteur de caractères pour les champs texte
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

            // Gestion du formulaire avec confirmation
            const form = document.getElementById('editRequeteForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (confirm('Êtes-vous sûr de vouloir modifier cette requête ?')) {
                        // Désactiver le bouton pour éviter les double-clics
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mise à jour...';
                        }

                        // Soumettre le formulaire
                        form.submit();
                    }
                });
            }
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
                                            {{ $fichier->nom_original }} ({{ number_format($fichier->taille / 1024, 1) }}
                                            KB)
                                            <div>
                                                <a href="{{ route('requetes.downloadFichier', $fichier->id_fichier) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">Télécharger</a>
                                                <form action="{{ route('requetes.deleteFichier', $fichier->id_fichier) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">Supprimer</button>
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
                            <small class="form-text text-muted">Formats acceptés : PDF, JPG, PNG, DOC, DOCX (max 5MB par
                                fichier)</small>
                            @error('fichiers.*')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><span class="text-danger">*</span> Champs obligatoires</small>
                            <div>
                                <a href="{{ route('requetes.show', $requete->code_requete) }}"
                                    class="btn btn-secondary me-2">Annuler</a>
                                <button type="submit" class="btn btn-primary">Mettre à jour la requête</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
<<<<<<< HEAD
<form action="{{ route('requetes.update', $requete->code_requete) }}" method="POST" enctype="multipart/form-data" class="modal-body" id="editRequeteForm">
    @csrf
    @method('PUT')

    {{-- @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif --}}

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
    @endif

                <div class="mb-3">
                    <label for="titre_requete_edit" class="form-label">Titre de la requête <span class="text-danger">*</span></label>
                    <input type="text" name="titre_requete" id="titre_requete_edit" maxlength="180" class="form-control @error('titre_requete') is-invalid @enderror" value="{{ old('titre_requete', $requete->titre_requete) }}" placeholder="Entrez le titre de votre requête" required>
                    @error('titre_requete')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-3">
                    <label for="titre_requete" class="form-label">Titre de la requête <span class="text-danger">*</span></label>
                    <input type="text" name="titre_requete" id="titre_requete" maxlength="180" class="form-control @error('titre_requete') is-invalid @enderror" value="{{ old('titre_requete', $requete->titre_requete) }}" placeholder="Entrez le titre de votre requête" required>
                    @error('titre_requete')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                --}}

                <div class="mb-3">
                    <label for="desc_requete" class="form-label">Description détaillée <span class="text-danger">*</span></label>
                    <textarea name="desc_requete" id="desc_requete" rows="4" maxlength="180" class="form-control @error('desc_requete') is-invalid @enderror" placeholder="Décrivez votre requête en détail" required>{{ old('desc_requete', $requete->desc_requete) }}</textarea>
                    @error('desc_requete')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code_cat" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select name="code_cat" id="code_cat" class="form-select @error('code_cat') is-invalid @enderror" required>
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->code_cat }}" {{ old('code_cat', $requete->code_cat) == $categorie->code_cat ? 'selected' : '' }}>
                                    {{ $categorie->label_cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('code_cat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="col-md-6">
                        <label for="code_bureau" class="form-label">Bureau concerné <span class="text-danger">*</span></label>
                        <select name="code_bureau" id="code_bureau" class="form-select @error('code_bureau') is-invalid @enderror" required>
                            <option value="">Sélectionnez un bureau</option>
                            @foreach($bureaux as $bureau)
                                <option value="{{ $bureau->code_bureau }}" {{ old('code_bureau', $requete->code_bureau) == $bureau->code_bureau ? 'selected' : '' }}>
                                    {{ $bureau->nom_bureau }}
                                </option>
                            @endforeach
                        </select>
                        @error('code_bureau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}
                </div>

                {{-- <div class="mb-3">
                    <label class="form-label">Priorité</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="priorite" id="priorite_standard" value="standard" {{ old('priorite', $requete->priorite) == 'standard' ? 'checked' : '' }}>
                            <label class="form-check-label" for="priorite_standard">Standard</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="priorite" id="priorite_urgent" value="urgent" {{ old('priorite', $requete->priorite) == 'urgent' ? 'checked' : '' }}>
                            <label class="form-check-label" for="priorite_urgent">Urgent</label>
                        </div>
                    </div>
                </div> --}}

                @if($requete->fichiers->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Fichiers actuels</label>
                        <ul class="list-group">
                            @foreach($requete->fichiers as $fichier)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $fichier->nom_original }} ({{ number_format($fichier->taille / 1024, 1) }} KB)
                                    <div>
                                        <a href="{{ route('requetes.downloadFichier', $fichier->id_fichier) }}" class="btn btn-sm btn-outline-primary me-1">Télécharger</a>
                                        <form action="{{ route('requetes.deleteFichier', $fichier->id_fichier) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="fichiers" class="form-label">Ajouter de nouveaux fichiers</label>
                    <input id="fichiers" name="fichiers[]" type="file" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    @error('fichiers.*')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><span class="text-danger">*</span> Champs obligatoires</small>
                    <div>
                        <a href="{{ route('requetes.show', $requete->code_requete) }}" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour la requête</button>
                    </div>
                </div>
            </form>
=======
>>>>>>> 591467646a95aa96b7f84e92d66e127fca9a0624
        </div>
    </div>
@endsection

@extends('sige_app.backend.template.backend')

@section('css')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
        }
        #search_results div:last-child {
            border-bottom: none !important;
        }
        #search_results div:hover {
            background-color: #e9ecef !important;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script>
        let descEditor, axesEditor;
        let editorsReady = false;

        // Initialiser les éditeurs CKEditor
        Promise.all([
        ClassicEditor
            .create(document.querySelector('#desc_labo'))
                .then(editor => {
                    descEditor = editor;
                    console.log('Éditeur desc_labo initialisé');
                })
            .catch(error => {
                    console.error('Erreur initialisation desc_labo:', error);
                }),
        ClassicEditor
            .create(document.querySelector('#axes_recherche'))
                .then(editor => {
                    axesEditor = editor;
                    console.log('Éditeur axes_recherche initialisé');
                })
                .catch(error => {
                    console.error('Erreur initialisation axes_recherche:', error);
                })
        ]).then(() => {
            editorsReady = true;
            console.log('Tous les éditeurs sont prêts');
        });

                let currentType = '';
        let searchTimeout;

        // Fonction pour rechercher les personnes selon le type et le terme de recherche
        function rechercherPersonnes(type, searchTerm = '') {
            const searchInput = document.getElementById('search_personne');
            const resultsDiv = document.getElementById('search_results');

            if (!type) {
                searchInput.placeholder = 'Sélectionner d\'abord le type';
                searchInput.disabled = true;
                return;
            }

            searchInput.disabled = false;
            searchInput.placeholder = 'Rechercher une personne...';

            const params = new URLSearchParams();
            if (searchTerm) {
                params.append('search', searchTerm);
            }
            params.append('limit', 15);

            fetch(`/api/labo/personnes/${type}?${params}`)
                .then(response => response.json())
                .then(personnes => {
                    resultsDiv.innerHTML = '';

                    if (personnes.length === 0) {
                        resultsDiv.innerHTML = '<div class="p-2 text-muted">Aucun résultat trouvé</div>';
                        resultsDiv.style.display = 'block';
                        return;
                    }

                    personnes.forEach(personne => {
                        const div = document.createElement('div');
                        div.className = 'p-2 border-bottom cursor-pointer hover-bg-light';
                        div.style.cursor = 'pointer';
                        div.innerHTML = `
                            <div class="fw-bold">${personne.display}</div>
                            <small class="text-muted">ID: ${personne.id}</small>
                        `;

                        div.addEventListener('click', function() {
                            searchInput.value = personne.display;
                            document.getElementById('id_pers_lab').value = personne.id;
                            resultsDiv.style.display = 'none';
                        });

                        div.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = '#f8f9fa';
                        });

                        div.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = '';
                        });

                        resultsDiv.appendChild(div);
                    });

                    resultsDiv.style.display = 'block';
                })
            .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    resultsDiv.innerHTML = '<div class="p-2 text-danger">Erreur de chargement</div>';
                    resultsDiv.style.display = 'block';
                });
        }

        // Écouter le changement du type de personne
        document.getElementById('type_pers_lab').addEventListener('change', function() {
            currentType = this.value;
            const searchInput = document.getElementById('search_personne');
            const resultsDiv = document.getElementById('search_results');

            if (currentType) {
                searchInput.value = '';
                document.getElementById('id_pers_lab').value = '';
                searchInput.disabled = false;
                searchInput.placeholder = 'Rechercher une personne...';
                rechercherPersonnes(currentType);
            } else {
                searchInput.value = '';
                document.getElementById('id_pers_lab').value = '';
                searchInput.disabled = true;
                searchInput.placeholder = 'Sélectionner d\'abord le type';
                resultsDiv.style.display = 'none';
            }
        });

        // Écouter la saisie dans le champ de recherche
        document.getElementById('search_personne').addEventListener('input', function() {
            const searchTerm = this.value.trim();

            // Clear le timeout précédent
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Attendre 300ms après la dernière frappe pour éviter trop de requêtes
            searchTimeout = setTimeout(() => {
                if (currentType) {
                    rechercherPersonnes(currentType, searchTerm);
                }
            }, 300);
        });

        // Masquer les résultats quand on clique ailleurs
        document.addEventListener('click', function(e) {
            const resultsDiv = document.getElementById('search_results');
            const searchInput = document.getElementById('search_personne');

            if (!resultsDiv.contains(e.target) && e.target !== searchInput) {
                resultsDiv.style.display = 'none';
            }
        });

        // Gérer les touches clavier
        document.getElementById('search_personne').addEventListener('keydown', function(e) {
            const resultsDiv = document.getElementById('search_results');
            const items = resultsDiv.querySelectorAll('div[cursor="pointer"]');
            let currentIndex = -1;

            // Trouver l'élément actuellement sélectionné
            items.forEach((item, index) => {
                if (item.style.backgroundColor === 'rgb(248, 249, 250)') {
                    currentIndex = index;
                }
            });

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < items.length - 1) {
                        if (currentIndex >= 0) items[currentIndex].style.backgroundColor = '';
                        items[currentIndex + 1].style.backgroundColor = '#f8f9fa';
                    }
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) {
                        items[currentIndex].style.backgroundColor = '';
                        items[currentIndex - 1].style.backgroundColor = '#f8f9fa';
                    }
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (currentIndex >= 0) {
                        items[currentIndex].click();
                    }
                    break;
                case 'Escape':
                    resultsDiv.style.display = 'none';
                    break;
            }
        });

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM chargé');

            const typeSelect = document.getElementById('type_pers_lab');
            if (typeSelect.value) {
                currentType = typeSelect.value;
                document.getElementById('search_personne').disabled = false;
                document.getElementById('search_personne').placeholder = 'Rechercher une personne...';
            }

            // Ajouter un gestionnaire d'événement pour le formulaire
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Événement submit déclenché');
                });
            }

            // Ajouter un gestionnaire d'événement pour le bouton submit
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    console.log('Bouton submit cliqué');
                });
            }
        });

                        // Fonction de validation du formulaire
        function validateForm() {
            console.log('validateForm() appelée');

            // Vérifier tous les champs requis
            const requiredFields = [
                'code_lab',
                'label_labo',
                'email_labo',
                'tel_labo',
                'adresse_labo',
                'type_pers_lab',
                'id_pers_lab'
            ];

            for (let fieldName of requiredFields) {
                const field = document.getElementById(fieldName);
                if (!field || !field.value.trim()) {
                    console.log('Champ manquant:', fieldName);
                    alert(`Le champ ${fieldName} est requis`);
                    if (field) field.focus();
                    return false;
                }
            }

            const typePers = document.getElementById('type_pers_lab').value;
            const idPers = document.getElementById('id_pers_lab').value;

            console.log('Type personne:', typePers);
            console.log('ID personne:', idPers);

            // Vérifier que les éditeurs sont prêts
            if (!editorsReady) {
                console.log('Éditeurs pas encore prêts, attente...');
                setTimeout(() => {
                    if (validateForm()) {
                        document.querySelector('form').submit();
                    }
                }, 100);
                return false;
            }

            // Synchroniser le contenu des éditeurs CKEditor avec les textareas cachés
            if (descEditor) {
                const descContent = descEditor.getData();
                document.getElementById('desc_labo').value = descContent;
                console.log('Contenu desc_labo synchronisé:', descContent);

                if (!descContent.trim()) {
                    alert('La description est requise');
                    return false;
                }
            }
            if (axesEditor) {
                document.getElementById('axes_recherche').value = axesEditor.getData();
                console.log('Contenu axes_recherche synchronisé');
            }

            console.log('Validation réussie, soumission du formulaire...');
            return true;
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 m-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Créer un nouveau laboratoire</h4>
                    </div>

                    <div class="card-body">
                        {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif --}}

                        <form action="{{ route('labo.laboratoires.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf

                            <div class="mb-3">
                                    <label for="code_lab" class="form-label">Code du laboratoire <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code_lab') is-invalid @enderror"
                                        id="code_lab" name="code_lab" value="{{ old('code_lab') }}" required>
                                    @error('code_lab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>

                            <div class="mb-3">
                                <label for="label_labo" class="form-label">Nom du laboratoire <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('label_labo') is-invalid @enderror"
                                    id="label_labo" name="label_labo" value="{{ old('label_labo') }}" required>
                                @error('label_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="desc_labo" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('desc_labo') is-invalid @enderror" id="desc_labo" name="desc_labo" rows="5"
                                >{{ old('desc_labo') }}</textarea>
                                @error('desc_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="axes_recherche" class="form-label">Axes de recherche</label>
                                <textarea class="form-control @error('axes_recherche') is-invalid @enderror" id="axes_recherche" name="axes_recherche"
                                    rows="4">{{ old('axes_recherche') }}</textarea>
                                @error('axes_recherche')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email_labo" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email_labo') is-invalid @enderror"
                                        id="email_labo" name="email_labo" value="{{ old('email_labo') }}" required>
                                    @error('email_labo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tel_labo" class="form-label">Téléphone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tel_labo') is-invalid @enderror"
                                        id="tel_labo" name="tel_labo" value="{{ old('tel_labo') }}" required>
                                    @error('tel_labo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse_labo" class="form-label">Adresse <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('adresse_labo') is-invalid @enderror" id="adresse_labo" name="adresse_labo"
                                    rows="2" required>{{ old('adresse_labo') }}</textarea>
                                @error('adresse_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="logo_labo" class="form-label">Logo du laboratoire</label>
                                <input type="file" class="form-control @error('logo_labo') is-invalid @enderror"
                                    id="logo_labo" name="logo_labo" accept="image/*">
                                @error('logo_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Section Administrateur du laboratoire -->
                            <div class="card mt-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Administrateur du laboratoire</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="type_pers_lab" class="form-label">Type de personne <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type_pers_lab') is-invalid @enderror" id="type_pers_lab" name="type_pers_lab" required>
                                                <option value="">Sélectionner un type</option>
                                                <option value="personnel" {{ old('type_pers_lab') == 'personnel' ? 'selected' : '' }}>Personnel</option>
                                                <option value="users" {{ old('type_pers_lab') == 'users' ? 'selected' : '' }}>Utilisateur</option>
                                                <option value="user_externe" {{ old('type_pers_lab') == 'user_externe' ? 'selected' : '' }}>Utilisateur Externe</option>
                                            </select>
                                            @error('type_pers_lab')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="id_pers_lab" class="form-label">Sélectionner la personne <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control @error('id_pers_lab') is-invalid @enderror"
                                                       id="search_personne" placeholder="Rechercher une personne..." autocomplete="off">
                                                <input type="hidden" id="id_pers_lab" name="id_pers_lab" required>
                                                <div id="search_results" class="position-absolute w-100 bg-white border rounded shadow-sm"
                                                     style="top: 100%; left: 0; z-index: 1000; max-height: 200px; overflow-y: auto; display: none;">
                                                </div>
                                            </div>
                                            @error('id_pers_lab')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('labo.laboratoires.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

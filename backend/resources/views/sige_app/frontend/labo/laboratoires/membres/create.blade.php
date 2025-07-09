@extends('sige_app.backend.template.backend')

@section('title', 'Ajouter un membre - ' . $laboratoire->label_labo)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class='bx bx-user-plus'></i> Ajouter un membre au laboratoire : {{ $laboratoire->label_labo }}
                    </h4>
                </div>
                <div class="card-body">
                    {{-- @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                    <!-- Debug: Affichage des données -->
                    <div class="alert alert-info">
                        <h6>Données disponibles :</h6>
                        <p><strong>Personnel :</strong> {{ $personnel->count() }} personnes</p>
                        <p><strong>Étudiants :</strong> {{ $users->count() }} personnes</p>
                        <p><strong>Utilisateurs externes :</strong> {{ $userExternes->count() }} personnes</p>
                    </div>

                    <form method="POST" action="{{ route('labo.laboratoires.membres.store', $laboratoire) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type_pers_lab" class="form-label">Type de personne</label>
                                    <select class="form-select" id="type_pers_lab" name="type_pers_lab" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="personnel" {{ old('type_pers_lab') === 'personnel' ? 'selected' : '' }}>Personnel</option>
                                        <option value="users" {{ old('type_pers_lab') === 'users' ? 'selected' : '' }}>Étudiant</option>
                                        <option value="user_externe" {{ old('type_pers_lab') === 'user_externe' ? 'selected' : '' }}>Utilisateur externe</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_pers_lab" class="form-label">Personne</label>
                                    <select class="form-select" id="id_pers_lab" name="id_pers_lab" required>
                                        <option value="">Sélectionner d'abord un type</option>
                                    </select>
                                    <div id="search-container" class="mt-2" style="display: none;">
                                        <input type="text" class="form-control" id="search-input" placeholder="Rechercher une personne...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_rl" class="form-label">Rôle</label>
                                    <select class="form-select" id="id_rl" name="id_rl" required>
                                        <option value="">Sélectionner un rôle</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id_rl }}" {{ old('id_rl') == $role->id_rl ? 'selected' : '' }}>
                                                {{ $role->lib_rl }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select class="form-select" id="statut" name="statut" required>
                                        <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_affectation" class="form-label">Date d'affectation</label>
                                    <input type="date" class="form-control" id="date_affectation" name="date_affectation"
                                           value="{{ old('date_affectation', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_fin_affectation" class="form-label">Date de fin d'affectation (optionnel)</label>
                                    <input type="date" class="form-control" id="date_fin_affectation" name="date_fin_affectation"
                                           value="{{ old('date_fin_affectation') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('labo.laboratoires.membres.index', $laboratoire) }}" class="btn btn-outline-secondary">
                                <i class='bx bx-arrow-back'></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Ajouter le membre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_pers_lab');
    const personneSelect = document.getElementById('id_pers_lab');
    const searchContainer = document.getElementById('search-container');
    const searchInput = document.getElementById('search-input');

    // Données des personnes par type - directement dans le JavaScript
    const personnesData = {
        personnel: [
            @foreach($personnel as $p)
                {
                    id: '{{ addslashes($p->code_pers) }}',
                    text: '{{ addslashes($p->nom_pers . ' ' . $p->prenom_pers . ' (' . $p->code_pers . ')') }}'
                }@if(!$loop->last),@endif
            @endforeach
        ],
        users: [
            @foreach($users as $u)
                {
                    id: '{{ addslashes($u->code_user) }}',
                    text: '{{ addslashes($u->nom_user . ' ' . $u->prenom_user . ' (' . $u->code_user . ')') }}'
                }@if(!$loop->last),@endif
            @endforeach
        ],
        user_externe: [
            @foreach($userExternes as $ue)
                {
                    id: '{{ addslashes($ue->id_user_ext) }}',
                    text: '{{ addslashes($ue->nom_user_ext . ' ' . $ue->prenom_user_ext . ' (' . $ue->id_user_ext . ')') }}'
                }@if(!$loop->last),@endif
            @endforeach
        ]
    };

    console.log('Données chargées:', personnesData);
    console.log('Nombre de personnel:', personnesData.personnel.length);
    console.log('Nombre d\'étudiants:', personnesData.users.length);
    console.log('Nombre d\'utilisateurs externes:', personnesData.user_externe.length);

    // Fonction pour filtrer les options
    function filterOptions(searchTerm, options) {
        if (!searchTerm) return options;
        return options.filter(option =>
            option.text.toLowerCase().includes(searchTerm.toLowerCase())
        );
    }

    // Fonction pour remplir le select
    function populateSelect(options) {
        personneSelect.innerHTML = '<option value="">Sélectionner une personne</option>';

        if (options.length > 0) {
            options.forEach(function(personne) {
                const option = document.createElement('option');
                option.value = personne.id;
                option.textContent = personne.text;
                personneSelect.appendChild(option);
            });
        } else {
            personneSelect.innerHTML = '<option value="">Aucune personne trouvée</option>';
        }
    }

    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        console.log('Type sélectionné:', selectedType);

        // Masquer la recherche par défaut
        searchContainer.style.display = 'none';
        searchInput.value = '';

        if (selectedType && personnesData[selectedType]) {
            console.log('Données pour', selectedType, ':', personnesData[selectedType]);
            console.log('Nombre d\'éléments:', personnesData[selectedType].length);

            if (personnesData[selectedType].length > 0) {
                // Afficher la recherche si il y a beaucoup d'étudiants
                if (selectedType === 'users' && personnesData[selectedType].length > 20) {
                    searchContainer.style.display = 'block';
                }

                populateSelect(personnesData[selectedType]);
                console.log('Options ajoutées:', personnesData[selectedType].length);
            } else {
                console.log('Aucune donnée pour le type:', selectedType);
                personneSelect.innerHTML = '<option value="">Aucune personne disponible pour ce type</option>';
            }
        } else {
            console.log('Type non reconnu ou pas de données:', selectedType);
            personneSelect.innerHTML = '<option value="">Type non reconnu</option>';
        }
    });

    // Gestionnaire de recherche
    searchInput.addEventListener('input', function() {
        const selectedType = typeSelect.value;
        const searchTerm = this.value;

        if (selectedType && personnesData[selectedType]) {
            const filteredOptions = filterOptions(searchTerm, personnesData[selectedType]);
            populateSelect(filteredOptions);
        }
    });

    // Déclencher le changement si une valeur est déjà sélectionnée
    if (typeSelect.value) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

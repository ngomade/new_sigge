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

@if($userRole !== 'admin' && $userRole !== 'chef_projet')
    <div class="container py-4">
        <div class="alert alert-danger">
            <h4><i class='bx bx-error-circle'></i> Accès refusé</h4>
            <p>Vous n'avez pas les permissions nécessaires pour ajouter un membre.</p>
            <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour à la liste
            </a>
        </div>
    </div>
@else
<div class="container py-4">
    <h2 class="mb-4">Ajouter un membre au laboratoire : {{ $laboratoire->label_labo }}</h2>
    <form method="POST" action="{{ route('laboratoires.admin.membres.store', $laboratoire->code_lab) }}">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="type_pers_lab" class="form-label">Type de personne</label>
                <select class="form-select" id="type_pers_lab" name="type_pers_lab" required>
                    <option value="">Sélectionner un type</option>
                    <option value="personnel" {{ old('type_pers_lab') === 'personnel' ? 'selected' : '' }}>Personnel</option>
                    <option value="users" {{ old('type_pers_lab') === 'users' ? 'selected' : '' }}>Étudiant</option>
                    <option value="user_externe" {{ old('type_pers_lab') === 'user_externe' ? 'selected' : '' }}>Utilisateur externe</option>
                </select>
            </div>
            <div class="col-md-8">
                <label for="id_pers_lab" class="form-label">Personne</label>
                <input type="text" class="form-control mb-2" id="search-personne" placeholder="Rechercher une personne...">
                <select class="form-select" id="id_pers_lab" name="id_pers_lab" required>
                    <option value="">Sélectionner d'abord un type</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="id_rl" class="form-label">Rôle</label>
                <select class="form-select" id="id_rl" name="id_rl" required>
                    <option value="">Sélectionner un rôle</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id_rl }}" {{ old('id_rl') == $role->id_rl ? 'selected' : '' }}>{{ $role->lib_rl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="statut" class="form-label">Statut</label>
                <select class="form-select" id="statut" name="statut" required>
                    <option value="actif" {{ old('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="date_affectation" class="form-label">Date d'affectation</label>
                <input type="date" class="form-control" id="date_affectation" name="date_affectation" value="{{ old('date_affectation', date('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date_fin_affectation" class="form-label">Date de fin d'affectation (optionnel)</label>
                <input type="date" class="form-control" id="date_fin_affectation" name="date_fin_affectation" value="{{ old('date_fin_affectation') }}">
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-save"></i> Ajouter le membre
            </button>
        </div>
    </form>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_pers_lab');
    const personneSelect = document.getElementById('id_pers_lab');
    const searchInput = document.getElementById('search-personne');
    const personnesData = {
        personnel: [
            @foreach($personnel as $p)
                { id: '{{ $p->code_pers }}', text: '{{ $p->nom_pers }} {{ $p->prenom_pers }} ({{ $p->code_pers }})' },
            @endforeach
        ],
        users: [
            @foreach($users as $u)
                { id: '{{ $u->code_user }}', text: '{{ $u->nom_user }} {{ $u->prenom_user }} ({{ $u->code_user }})' },
            @endforeach
        ],
        user_externe: [
            @foreach($userExternes as $ue)
                { id: '{{ $ue->id_user_ext }}', text: '{{ $ue->nom_user_ext }} {{ $ue->prenom_user_ext }} ({{ $ue->id_user_ext }})' },
            @endforeach
        ]
    };
    let currentType = '';
    let currentList = [];
    function populateSelect(list) {
        personneSelect.innerHTML = '<option value="">Sélectionner une personne</option>';
        list.forEach(function(personne) {
            const option = document.createElement('option');
            option.value = personne.id;
            option.textContent = personne.text;
            personneSelect.appendChild(option);
        });
    }
    function filterList(searchTerm) {
        if (!currentList) return;
        const filtered = currentList.filter(function(personne) {
            return personne.text.toLowerCase().includes(searchTerm.toLowerCase());
        });
        populateSelect(filtered);
    }
    typeSelect.addEventListener('change', function() {
        currentType = this.value;
        currentList = personnesData[currentType] || [];
        populateSelect(currentList);
        searchInput.value = '';
    });
    searchInput.addEventListener('input', function() {
        filterList(this.value);
    });
});
</script>
@endpush
@endif
@endsection

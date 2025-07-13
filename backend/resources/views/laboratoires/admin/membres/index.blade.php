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
    <h2 class="mb-4">Gestion des membres du laboratoire : {{ $laboratoire->label_labo }}</h2>
    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Recherche (nom, id, etc.)" value="{{ $search }}">
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <option value="">Tous les rôles</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id_rl }}" {{ $role == $r->id_rl ? 'selected' : '' }}>{{ $r->lib_rl }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="statut" class="form-select">
                <option value="">Tous statuts</option>
                <option value="actif" {{ $statut == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ $statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">Tous types</option>
                <option value="personnel" {{ $type == 'personnel' ? 'selected' : '' }}>Personnel</option>
                <option value="user" {{ $type == 'user' ? 'selected' : '' }}>Étudiant</option>
                <option value="user_externe" {{ $type == 'user_externe' ? 'selected' : '' }}>Externe</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
        </div>
    </form>

    @if($userRole === 'admin')
    <form method="POST" action="{{ route('laboratoires.admin.membres.bulk', $laboratoire->code_lab) }}" id="bulkForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4 d-flex align-items-center gap-2">
                <select name="action" id="bulk-action" class="form-select"  style="max-width: 180px;">
                    <option value="">Action groupée</option>
                    <option value="delete">Supprimer</option>
                    <option value="role">Changer le rôle</option>
                    <option value="statut">Changer le statut</option>
                </select>
                <select name="role" id="bulk-role" class="form-select d-none" style="max-width: 180px;">
                    <option value="">Sélectionner un rôle</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->id_rl }}">{{ $r->lib_rl }}</option>
                    @endforeach
                </select>
                <select name="statut" id="bulk-statut" class="form-select d-none" style="max-width: 180px;">
                    <option value="">Sélectionner un statut</option>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">Appliquer</button>
            </div>
            <div class="col-md-8 text-end">
                <a href="{{ route('labo.roles.index') }}" class="btn btn-outline-secondary me-2"><i class="bx bx-cog"></i> Gérer les rôles</a>
                <a href="{{ route('laboratoires.admin.invitations', $laboratoire->code_lab) }}" class="btn btn-outline-info me-2"><i class="bx bx-link-alt"></i> Invitations</a>
                <a href="{{ route('laboratoires.admin.membres.create', $laboratoire->code_lab) }}" class="btn btn-success"><i class="bx bx-user-plus"></i> Ajouter un membre</a>
            </div>
        </div>
    @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                    @if($userRole === 'admin')
                        <th><input type="checkbox" id="select-all"></th>
                    @endif
                        <th>#</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date d'affectation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($membres as $membre)
                        <tr>
                        @if($userRole === 'admin')
                            <td><input type="checkbox" name="ids[]" value="{{ $membre->id_pers_lab ?? $membre->id_user_externe }}"></td>
                        @endif
                            <td>{{ $membre->id_pers_lab ?? $membre->id_user_externe }}</td>
                            <td>
                                @if($membre->userExterne)
                                    {{ $membre->userExterne->nom_user_ext }}
                                    {{ $membre->userExterne->prenom_user_ext }}
                                @elseif($membre->persLab)
                                    @if($membre->persLab->type_pers_lab === 'personnel')
                                        {{ optional(\App\Models\Personnel::find($membre->id_pers_lab))->nom_pers }}
                                        {{ optional(\App\Models\Personnel::find($membre->id_pers_lab))->prenom_pers }}
                                    @elseif($membre->persLab->type_pers_lab === 'user')
                                        {{ optional(\App\Models\Users::find($membre->id_pers_lab))->nom_user }}
                                        {{ optional(\App\Models\Users::find($membre->id_pers_lab))->prenom_user }}
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if($membre->userExterne)
                                    externe
                                @else
                                    {{ $membre->persLab->type_pers_lab ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $membre->roleLabo->lib_rl ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $membre->statut === 'actif' ? 'success' : 'secondary' }}">{{ ucfirst($membre->statut) }}</span>
                            </td>
                            <td>
                                @php
                                    $date = $membre->date_affectation;
                                @endphp
                                {{ $date && $date instanceof \Carbon\Carbon ? $date->format('d/m/Y') : ($date ? \Carbon\Carbon::parse($date)->format('d/m/Y') : '-') }}
                            </td>
                            <td>
                                @php
                                    $membreId = $membre->id_pers_lab ?? $membre->id_user_externe;
                                @endphp
                                @if($membreId)
                                    <a href="{{ route('laboratoires.admin.membres.show', [$laboratoire->code_lab, $membreId]) }}" class="btn btn-sm btn-info"><i class="bx bx-show"></i></a>
                                @if($userRole === 'admin' || $userRole === 'chef_projet')
                                    <a href="{{ route('laboratoires.admin.membres.edit', [$laboratoire->code_lab, $membreId]) }}" class="btn btn-sm btn-primary"><i class="bx bx-edit"></i></a>
                                @endif
                                @if($userRole === 'admin')
                                    <form method="POST" action="{{ route('laboratoires.admin.membres.destroy', [$laboratoire->code_lab, $membreId]) }}" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce membre ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
                                    </form>
                                @endif
                                @else
                                    <span class="text-muted">Actions indisponibles</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                        <td colspan="{{ $userRole === 'admin' ? '8' : '7' }}" class="text-center text-muted">Aucun membre trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $membres->withQueryString()->links() }}
        </div>
    @if($userRole === 'admin')
    </form>
    @endif
</div>
@push('scripts')
@if($userRole === 'admin')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner/désélectionner tout
    const selectAll = document.getElementById('select-all');
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = selectAll.checked);
    });
    // Affichage dynamique des sélecteurs selon l'action
    const bulkAction = document.getElementById('bulk-action');
    const bulkRole = document.getElementById('bulk-role');
    const bulkStatut = document.getElementById('bulk-statut');
    bulkAction.addEventListener('change', function() {
        bulkRole.classList.add('d-none');
        bulkStatut.classList.add('d-none');
        if (this.value === 'role') {
            bulkRole.classList.remove('d-none');
        } else if (this.value === 'statut') {
            bulkStatut.classList.remove('d-none');
        }
    });
});
</script>
@endif
@endpush
@endsection


@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-group'></i> Gestion des participants - {{ $projet->theme_projet }}</h2>
        <div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                <i class='bx bx-plus'></i> Ajouter un participant
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
            <h5><i class='bx bx-list-ul'></i> Liste des participants ({{ $projet->participants->count() }})</h5>
        </div>
        <div class="card-body">
            @if($projet->participants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Rôle</th>
                                <th>Période de participation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projet->participants as $participant)
                                <tr>
                                    <td>
                                        @if($participant->membre)
                                            <strong>{{ $participant->membre->nom_complet }}</strong>
                                            <br><small class="text-muted">{{ $participant->membre->email ?? 'N/A' }}</small>
                                        @elseif($participant->userExterne)
                                            <strong>{{ $participant->userExterne->nom_user_ext }} {{ $participant->userExterne->prenom_user_ext }}</strong>
                                            <br><small class="text-muted">{{ $participant->userExterne->email_user_ext ?? 'N/A' }}</small>
                                        @else
                                            <span class="text-muted">Participant supprimé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($participant->membre)
                                            <span class="badge bg-primary">{{ $participant->membre->type_label }}</span>
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
                                    <td>
                                        <form method="POST" action="{{ route('laboratoires.admin.projets.participants.destroy', [$laboratoire->code_lab, $projet->code_projet, $participant->id]) }}"
                                              onsubmit="return confirm('Confirmer la suppression de ce participant ?')" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class='bx bx-user-x' style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="text-muted mt-3">Aucun participant</h5>
                    <p class="text-muted">Commencez par ajouter des participants au projet.</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                        <i class='bx bx-plus'></i> Ajouter le premier participant
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Ajout Participant -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('laboratoires.admin.projets.participants.store', [$laboratoire->code_lab, $projet->code_projet]) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class='bx bx-plus'></i> Ajouter un participant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type_participant" class="form-label">Type de participant *</label>
                        <select class="form-select @error('type_participant') is-invalid @enderror" id="type_participant" name="type_participant" required>
                            <option value="">Sélectionner le type</option>
                            <option value="membre" {{ old('type_participant') == 'membre' ? 'selected' : '' }}>Membre du laboratoire</option>
                            <option value="externe" {{ old('type_participant') == 'externe' ? 'selected' : '' }}>Utilisateur externe</option>
                        </select>
                        @error('type_participant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="membre_section" style="display: none;">
                        <div class="mb-3">
                            <label for="membre_id" class="form-label">Membre du laboratoire *</label>
                            <select class="form-select @error('membre_id') is-invalid @enderror" id="membre_id" name="membre_id">
                                <option value="">Sélectionner un membre</option>
                                @foreach($membres as $membre)
                                    <option value="{{ $membre->id }}" {{ old('membre_id') == $membre->id ? 'selected' : '' }}>
                                        {{ $membre->nom_complet }} ({{ $membre->type_membre_label }})
                                    </option>
                                @endforeach</select>
                            @error('membre_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="externe_section" style="display: none;">
                        <div class="mb-3">
                            <label for="user_externe_id" class="form-label">Utilisateur externe *</label>
                            <select class="form-select @error('user_externe_id') is-invalid @enderror" id="user_externe_id" name="user_externe_id">
                                <option value="">Sélectionner un utilisateur externe</option>
                                @foreach($usersExternes as $user)
                                    <option value="{{ $user->id_user_ext }}" {{ old('user_externe_id') == $user->id_user_ext ? 'selected' : '' }}>
                                        {{ $user->nom_user_ext }} {{ $user->prenom_user_ext }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_externe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle dans le projet *</label>
                        <input type="text" class="form-control @error('role') is-invalid @enderror"
                               id="role" name="role" value="{{ old('role') }}"
                               placeholder="Ex: Chef de projet, Chercheur, Assistant..." required>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="debut_participation" class="form-label">Date de début de participation *</label>
                                <input type="date" class="form-control @error('debut_participation') is-invalid @enderror"
                                       id="debut_participation" name="debut_participation" value="{{ old('debut_participation') }}" required>
                                @error('debut_participation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fin_participation" class="form-label">Date de fin de participation (optionnel)</label>
                                <input type="date" class="form-control @error('fin_participation') is-invalid @enderror"
                                       id="fin_participation" name="fin_participation" value="{{ old('fin_participation') }}">
                                @error('fin_participation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class='bx bx-plus'></i> Ajouter le participant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('type_participant').addEventListener('change', function() {
    const membreSection = document.getElementById('membre_section');
    const externeSection = document.getElementById('externe_section');
    const membreSelect = document.getElementById('membre_id');
    const externeSelect = document.getElementById('user_externe_id');

    // Masquer toutes les sections
    membreSection.style.display = 'none';
    externeSection.style.display = 'none';

    // Réinitialiser les sélections
    membreSelect.value = '';
    externeSelect.value = '';

    // Afficher la section appropriée
    if (this.value === 'membre') {
        membreSection.style.display = 'block';
        membreSelect.required = true;
        externeSelect.required = false;
    } else if (this.value === 'externe') {
        externeSection.style.display = 'block';
        externeSelect.required = true;
        membreSelect.required = false;
    }
});

// Validation des dates
document.getElementById('fin_participation').addEventListener('change', function() {
    const debut = document.getElementById('debut_participation').value;
    const fin = this.value;

    if (debut && fin && fin <= debut) {
        this.setCustomValidity('La date de fin doit être postérieure à la date de début');
    } else {
        this.setCustomValidity('');
    }
});
</script>
<script>
// Trigger change event on page load to set required attributes correctly
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_participant');
    if (typeSelect) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

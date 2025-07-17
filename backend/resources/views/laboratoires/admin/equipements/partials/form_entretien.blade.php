{{-- Formulaire d'entretien d'équipement --}}
<form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.store', [$laboratoire->code_lab, $equipement->code_equip]) }}">
    @csrf
    @php
        $userType = session('user_type');
        $userId = session('user_id');
    @endphp
    <input type="hidden" name="participant_type" value="{{ $userType === 'externe' ? 'externe' : 'interne' }}">
    @if($userType === 'externe')
        <input type="hidden" name="id_user_ext" value="{{ $userId }}">
    @else
        <input type="hidden" name="id_pers_lab" value="{{ $userId }}">
    @endif
    <div class="mb-3">
        <label class="form-label">Responsable de l'entretien</label>
        <div class="alert alert-info mb-0">
            <i class="bx bx-user"></i>
            <strong>{{ session('user_name') }}</strong>
            <br><small class="text-muted">Vous effectuez cet entretien</small>
        </div>
    </div>
    <div class="mb-3">
        <label for="type_entretien" class="form-label">Type d'entretien</label>
        <select class="form-select" id="type_entretien" name="type_entretien" required>
            <option value="">-- Sélectionner --</option>
            <option value="entretien">Entretien</option>
            <option value="reparation">Réparation</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="debut_entretien" class="form-label">Date de début</label>
        <input type="date" class="form-control" id="debut_entretien" name="debut_entretien" required>
    </div>
    <div class="mb-3">
        <label for="fin_entretien" class="form-label">Date de fin</label>
        <input type="date" class="form-control" id="fin_entretien" name="fin_entretien" required>
    </div>
    <div class="mb-3">
        <label for="desc_entretien" class="form-label">Description</label>
        <textarea class="form-control" id="desc_entretien" name="desc_entretien" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="cout" class="form-label">Coût (FCFA)</label>
        <input type="number" class="form-control" id="cout" name="cout" min="0" step="1">
    </div>
    <button type="submit" class="btn btn-primary w-100">
        <i class="bx bx-save"></i> Enregistrer l'entretien
    </button>
</form>

{{-- Formulaire d'entretien d'équipement --}}
<form method="POST" action="">
    @csrf
    <div class="mb-3">
        @if(isset($userRole) && $userRole === 'technicien')
            <label class="form-label">Responsable</label>
            <input type="hidden" name="id_pers_lab" value="{{ $affectation->id }}">
            <input type="text" class="form-control" value="{{ $affectation->nom_complet }}" readonly>
        @else
            <label for="id_pers_lab" class="form-label">Responsable</label>
            <select class="form-select" id="id_pers_lab" name="id_pers_lab" required>
                <option value="">-- Sélectionner --</option>
                @foreach($personnel as $pers)
                    <option value="{{ $pers->id }}">{{ $pers->nom_complet }}</option>
                @endforeach
            </select>
        @endif
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
    <button type="submit" class="btn btn-primary">Enregistrer l'entretien</button>
</form>

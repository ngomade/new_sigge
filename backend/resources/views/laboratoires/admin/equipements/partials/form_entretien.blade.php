{{-- Formulaire d'entretien d'équipement --}}
<form method="POST" action="">
    @csrf
    <div class="mb-3">
        <label for="participant_type" class="form-label">Type de participant</label>
        <select class="form-select" id="participant_type" name="participant_type" required onchange="toggleParticipantSelect()">
            <option value="">-- Sélectionner --</option>
            <option value="interne">Membre interne</option>
            <option value="externe">User externe</option>
        </select>
    </div>
    <div class="mb-3" id="select_interne" style="display:none;">
        <label for="id_pers_lab" class="form-label">Membre interne</label>
        <select class="form-select" id="id_pers_lab" name="id_pers_lab">
            <option value="">-- Sélectionner --</option>
            @foreach($personnel as $pers)
                <option value="{{ $pers->id }}">{{ $pers->nom_complet }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3" id="select_externe" style="display:none;">
        <label for="id_user_ext" class="form-label">User externe</label>
        <select class="form-select" id="id_user_ext" name="id_user_ext">
            <option value="">-- Sélectionner --</option>
            @foreach($externes as $ext)
                <option value="{{ $ext->id_user_ext }}">{{ $ext->nom_user_ext }} {{ $ext->prenom_user_ext }}</option>
            @endforeach
        </select>
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
<script>
function toggleParticipantSelect() {
    var type = document.getElementById('participant_type').value;
    document.getElementById('select_interne').style.display = (type === 'interne') ? '' : 'none';
    document.getElementById('select_externe').style.display = (type === 'externe') ? '' : 'none';
    if(type === 'interne') {
        document.getElementById('id_user_ext').value = '';
    } else if(type === 'externe') {
        document.getElementById('id_pers_lab').value = '';
    }
}
</script>

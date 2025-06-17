// ...existing code...
@extends("sige_app.frontend.template.frontend")
@section('title', 'Nouvelle Requête')

@section("content")
<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.03);">
    <div class="modal-dialog modal-lg"> {{-- Ajout modal-lg pour largeur identique à maintenance --}}
        <div class="modal-content border-primary shadow"> {{-- Ajout border-primary et shadow --}}
            <div class="modal-header bg-primary p-2" style="color: white">
                <h5 class="modal-title" style="color: white">Ajout d'une requête</h5>
                <a href="{{ route('requetes.index') }}" class="btn-close" aria-label="Close"></a>
            </div>
            <form action="{{ route('requetes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if(session('error'))
                        <div class="mb-3 alert alert-danger p-2">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($errors) && $errors->any())
                        <div class="mb-3 alert alert-danger p-2">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <input type="text" class="form-control form-control-lg border-primary" placeholder="Titre de la requête *"
                                   name="titre_requete" id="titre_requete" maxlength="180"
                                   value="{{ old('titre_requete') }}" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <textarea class="form-control form-control-lg border-primary" name="desc_requete" id="desc_requete" rows="4"
                                      placeholder="Description détaillée *" maxlength="180" required>{{ old('desc_requete') }}</textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <select name="code_cat" id="code_cat" class="form-select form-select-lg border-primary" required>
                                <option value="">Catégorie *</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->code_cat }}" {{ old('code_cat') == $category->code_cat ? 'selected' : '' }}>
                                        {{ $category->label_cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <select name="code_bureau" id="code_bureau" class="form-select form-select-lg border-primary" required>
                                <option value="">Bureau destinataire *</option>
                                @foreach($bureaux as $bureau)
                                    <option value="{{ $bureau->code_bureau }}" {{ old('code_bureau') == $bureau->code_bureau ? 'selected' : '' }}>
                                        {{ $bureau->label_bureau }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <label class="form-label mb-1">Niveau de priorité</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="priorite" id="priorite_standard" value="standard"
                                           {{ old('priorite', 'standard') == 'standard' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="priorite_standard">Standard</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="priorite" id="priorite_urgent" value="urgent"
                                           {{ old('priorite') == 'urgent' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="priorite_urgent">Urgent</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-11 m-auto">
                            <label for="fichiers" class="form-label">Documents joints (optionnel)</label>
                            <input id="fichiers" name="fichiers[]" type="file" class="form-control border-primary" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <div class="form-text">PDF, DOC, DOCX, JPG, PNG • Maximum 5MB par fichier</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <a href="{{ route('requetes.index') }}" class="btn btn-secondary btn-lg">Annuler</a>
                    <button type="submit" class="btn btn-success btn-lg">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
// ...existing code...
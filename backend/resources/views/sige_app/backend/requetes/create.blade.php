@extends("sige_app.frontend.template.frontend")
@section('js')
 <script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
    document.getElementById('closeCreateModalBtn').addEventListener('click', function() {
        const modal = this.closest('.modal');
        modal.classList.remove('show', 'd-block');
    });
</script>
@endsection
@section('title', 'Nouvelle Requête')

@section("content")
<div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);"> {{-- Darker backdrop for better focus --}}
    <div class="modal-dialog modal-xl modal-dialog-centered"> {{-- Increased modal size and centered --}}
<div class="modal-content border-danger shadow">
    <div class="modal-header bg-danger p-2 d-flex justify-content-between align-items-center" style="color: white">
        <h5 class="modal-title mb-0" style="color: white">Ajout d'une requête</h5>
        <button type="button" class="btn-close" aria-label="Close" style="filter: invert(1);" id="closeCreateModalBtn"></button> {{-- Changed to button to close modal without navigation --}}
    </div>
            <form action="{{ route('requetes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                 <div class="modal-body">
                    {{-- @if(session('error'))
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
                    @endif  --}}

                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <input type="text" class="form-control form-control-lg border-primary" placeholder="Titre de la requête *"
                                   name="titre_requete" id="titre_requete_create" maxlength="180"
                                   value="{{ old('titre_requete') }}" required>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <textarea class="form-control form-control-lg border-primary" name="desc_requete" id="desc_requete" rows="4"
                                      placeholder="Description détaillée *" maxlength="180" required>{{ old('desc_requete') }}</textarea>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
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
                    {{-- <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
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
                    </div> --}}
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <label for="fichiers" class="form-label">Documents joints (optionnel)</label>
                            <input id="fichiers" name="fichiers[]" type="file" class="form-control border-primary" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <div class="form-text">PDF, DOC, DOCX, JPG, PNG • Maximum 5MB par fichier</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0 justify-content-center">
                    <a href="{{ route('requetes.index') }}" class="btn btn-secondary btn-lg me-3">Annuler</a>
                    <button type="submit" class="btn btn-success btn-lg">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

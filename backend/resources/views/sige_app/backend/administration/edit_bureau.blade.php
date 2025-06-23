@extends("sige_app.backend.template.backend")
@section('title', 'Modifier ' . $type_bureau)

@section("content")
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier le {{$type_bureau}} : {{$bureau->label_bureau}}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Affichage de la hiérarchie si elle existe -->
                    @if(isset($hierarchy) && count($hierarchy) > 0)
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">Hiérarchie du bureau</h6>
                        @foreach($hierarchy as $level)
                            @if($level['niveau'] === 'Parent')
                                <p class="mb-1">
                                    <strong>Bureau parent :</strong> 
                                    {{ $level['label'] }} ({{ $level['type'] }})
                                    <code>{{ $level['code'] }}</code>
                                </p>
                            @elseif($level['niveau'] === 'Sous-bureaux')
                                <p class="mb-0">
                                    <strong>Sous-bureaux :</strong> 
                                    {{ count($level['bureaux']) }} bureau(x)
                                </p>
                                <ul class="mb-0">
                                    @foreach($level['bureaux'] as $sb)
                                        <li>{{ $sb['label'] }} ({{ $sb['type'] }}) <code>{{ $sb['code'] }}</code></li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <form action="/update_bureau/{{ $bureau->code_bureau }}" method="post" id="formEditBureau">
                        {{ csrf_field() }}
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="code_bureau" class="form-label">Code du {{$type_bureau}}</label>
                                <input type="text" class="form-control" value="{{ $bureau->code_bureau }}" disabled>
                                <small class="text-muted">Le code ne peut pas être modifié</small>
                            </div>
                        </div>

                        @if($type_bureau === 'Service' && isset($bureaux_parents))
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="bureau_parent" class="form-label">
                                    Bureau parent (Division ou Cellule) <span class="text-danger">*</span>
                                </label>
                                <select name="bureau_parent" id="bureau_parent" class="form-select" required>
                                    <option value="">Sélectionner un bureau parent</option>
                                    @foreach($bureaux_parents as $parent)
                                        <option value="{{ $parent->code_bureau }}" 
                                                {{ $parent_actuel && $parent_actuel->code_bureau == $parent->code_bureau ? 'selected' : '' }}>
                                            {{ $parent->label_bureau }} ({{ $parent->type_bureau }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="label_bureau" class="form-label">
                                    Libellé du {{$type_bureau}} <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="label_bureau" id="label_bureau" 
                                       value="{{ old('label_bureau', $bureau->label_bureau) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="desc_bureau" class="form-label">Description</label>
                                <textarea class="form-control" name="desc_bureau" id="desc_bureau" rows="5">{{ old('desc_bureau', $bureau->desc_bureau) }}</textarea>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informations</h6>
                                        <p class="mb-1">
                                            <i class="fas fa-calendar-plus text-primary"></i> 
                                            <strong>Créé le :</strong> 
                                            {{ $bureau->created_at ? $bureau->created_at->format('d/m/Y à H:i') : 'N/A' }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-calendar-check text-success"></i> 
                                            <strong>Modifié le :</strong> 
                                            {{ $bureau->updated_at ? $bureau->updated_at->format('d/m/Y à H:i') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Statistiques</h6>
                                        <p class="mb-1">
                                            <i class="fas fa-users text-info"></i> 
                                            <strong>Personnel actif :</strong> 
                                            @php
                                                $personnelActif = \App\Models\PersRole::where('code_bureau', $bureau->code_bureau)
                                                    ->where('statut_role', \App\Models\PersRole::STATUT_ACTIF)
                                                    ->count();
                                            @endphp
                                            {{ $personnelActif }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-sitemap text-warning"></i> 
                                            <strong>Sous-bureaux :</strong> 
                                            {{ $bureau->sousBureau->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="/bureau/{{ $type_bureau }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </div>
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
    $(document).ready(function() {
        // Validation du formulaire
        $('#formEditBureau').on('submit', function(e) {
            const labelBureau = $('#label_bureau').val().trim();
            
            if (labelBureau.length < 3) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Le libellé doit contenir au moins 3 caractères'
                });
                return false;
            }

            @if($type_bureau === 'Service')
            const bureauParent = $('#bureau_parent').val();
            if (!bureauParent) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Veuillez sélectionner un bureau parent'
                });
                return false;
            }
            @endif
        });

        // Avertissement si changement du parent avec des sous-bureaux
        @if($type_bureau === 'Service' && $bureau->sousBureau->count() > 0)
        $('#bureau_parent').on('change', function() {
            const originalValue = '{{ $parent_actuel ? $parent_actuel->code_bureau : "" }}';
            if ($(this).val() !== originalValue) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: 'Ce bureau a des sous-bureaux. Le changement de parent pourrait affecter la hiérarchie.',
                    showCancelButton: true,
                    confirmButtonText: 'Je comprends',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        $(this).val(originalValue);
                    }
                });
            }
        });
        @endif
    });
</script>
@endsection
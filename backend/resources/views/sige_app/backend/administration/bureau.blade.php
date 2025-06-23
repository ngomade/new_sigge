@php use App\Models\Bureau; @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
@extends("sige_app.backend.template.backend")
@section('title', 'Gestion des ' . $type_bureau . 's')
<?php $user = \Session::get("user"); ?>

<style>
    .swal2-container-custom {
        z-index: 9999 !important;
    }

    .swal2-popup {
        z-index: 10000 !important;
    }

    .hierarchy-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        margin: 0 0.25rem;
    }

    .code-preview {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-family: monospace;
        font-size: 0.9rem;
    }

    .stats-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

@section("content")
    <!-- Statistiques -->
    @if(isset($stats))
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total {{$type_bureau}}s</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Avec Personnel</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avec_personnel'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avec Sous-bureaux</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avec_sous_bureaux'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal d'ajout avec génération intelligente -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary p-2" style="color: white">
                    <h5 class="modal-title" style="color: white">Ajout d'un {{$type_bureau}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/ajouter_bureau" method="post" id="formAjoutBureau">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @if($type_bureau === 'Service' && isset($bureaux_parents))
                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <label for="bureau_parent">Bureau parent (Division ou Cellule) <span class="text-danger">*</span></label>
                                <select name="bureau_parent" id="bureau_parent" class="form-select" required>
                                    <option value="">Sélectionner un bureau parent</option>
                                    @foreach($bureaux_parents as $parent)
                                        <option value="{{ $parent->code_bureau }}" data-type="{{ $parent->type_bureau }}">
                                            {{ $parent->label_bureau }} ({{ $parent->type_bureau }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <label for="label_bureau">Libellé du {{$type_bureau}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Ex: {{$type_bureau}} des Ressources Humaines"
                                       name="label_bureau" id="label_bureau" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <label for="code_bureau">Code {{$type_bureau}}
                                    <span class="text-muted">(Généré automatiquement)</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="code_bureau" id="code_bureau" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="btnRegenerateCode">
                                        <i class="fas fa-sync-alt"></i> Régénérer
                                    </button>
                                </div>
                                <small class="text-muted">Le code sera généré automatiquement en fonction du libellé</small>
                            </div>
                        </div>

                        <input type="hidden" value="{{$type_bureau}}" name="type_bureau">

                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <label for="desc_bureau">Description</label>
                                <textarea class="form-control" name="desc_bureau" id="desc_bureau"
                                          placeholder="Description détaillée du {{$type_bureau}}" rows="4"></textarea>
                            </div>
                        </div>

                        <!-- Aperçu du code -->
                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <div class="alert alert-info d-none" id="codePreview">
                                    <strong>Aperçu du code :</strong> <span class="code-preview" id="previewCodeText"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de présentation (pour les départements) -->
    @if($type_bureau === 'Departement')
    <div class="modal fade" id="presentModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary p-2" style="color: white">
                    <h5 class="modal-title" style="color: white">Présentation d'un {{$type_bureau}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/presentation_bureau" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row mt-2">
                            <div class="col-sm-8 m-auto">
                                <select name="code_bureau" id="code_bureau_presentation" class="form-select">
                                    @foreach (Bureau::where('type_bureau', $type_bureau)->get() as $bureau)
                                        <option value="{{$bureau->code_bureau}}"> {{$bureau->label_bureau}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row m-auto justify-content-center">
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-5 m-auto">
                                        <label for="nom_chef">Grade et nom du CD<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="text" class="form-control" name="nom_chef" id="nom_chef" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-4 m-auto">
                                        <label for="photo_chef">Image du chef<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-8 m-auto">
                                        <input type="file" class="form-control" name="photo_chef" id="photo_chef" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-6 m-auto">
                                        <label for="depliant_ingenieur">Dépliant:Cursus Ingenieur<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-6 m-auto">
                                        <input type="file" class="form-control" name="depliant_ingenieur" id="depliant_ingenieur" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-6 m-auto">
                                        <label for="depliant_science">Dépliant:Science de l'ingenieur<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-6 m-auto">
                                        <input type="file" class="form-control" name="depliant_science" id="depliant_science" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="message_chef" id="message_chef" placeholder="Message du chef de département" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="cursus_ing" id="cursus_ing" placeholder="Filière du Cursus Ingénieur" rows="8"></textarea>
                            </div>
                        </div>
                        <!-- Flyers 1-5 pour Ingénieur -->
                        @for($i = 1; $i <= 5; $i++)
                        <div class="row m-auto mt-2">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="document_{{$i}}">Flyer {{$i}} {{ $i <= 2 ? '*' : '' }}</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_{{$i}}" id="document_{{$i}}" {{ $i <= 2 ? 'required' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @if($i < 5)
                            @php $j = $i + 1 @endphp
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="document_{{$j}}">Flyer {{$j}} {{ $j <= 2 ? '*' : '' }}</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_{{$j}}" id="document_{{$j}}" {{ $j <= 2 ? 'required' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @php $i++ @endphp
                            @endif
                        </div>
                        @endfor
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="grille_ing" id="grille_ing" placeholder="Grille des programmes du Cursus Ingénieur" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="science_ing" id="science_ing" placeholder="Filière du Cursus Science de l'Ingenieur" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                                <textarea class="tinymce-editor w-100" name="grille_science" id="grille_science" placeholder="Grille des programmes Science de l'Ingenieur" rows="8"></textarea>
                            </div>
                        </div>
                        <!-- Flyers 6-10 pour Science -->
                        @for($i = 6; $i <= 10; $i++)
                        <div class="row m-auto mt-2">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="document_{{$i}}">Flyer {{$i-5}} {{ $i <= 7 ? '*' : '' }}</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_{{$i}}" id="document_{{$i}}" {{ $i <= 7 ? 'required' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @if($i < 10)
                            @php $j = $i + 1 @endphp
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="document_{{$j}}">Flyer {{$j-5}} {{ $j <= 7 ? '*' : '' }}</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_{{$j}}" id="document_{{$j}}" {{ $j <= 7 ? 'required' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @php $i++ @endphp
                            @endif
                        </div>
                        @endfor
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal d'arborescence -->
    <div class="modal fade" id="arborescenceModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Arborescence des bureaux</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="arborescenceContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal des sous-bureaux -->
    <div class="modal fade" id="sousBureauxModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Sous-bureaux</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="sousBureauxContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte principale -->
    <div class="card" style="width: 95%; margin:auto;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-building me-2"></i>Liste des {{$type_bureau}}s
            </h5>
            <div>
                @if(isset($bureaux) && $bureaux->count() > 0)
                    <button class="btn btn-outline-secondary me-2" onclick="window.location.href='/bureau/{{$type_bureau}}/export'">
                        <i class="fas fa-file-csv me-1"></i> Exporter CSV
                    </button>
                    <button class="btn btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#arborescenceModal">
                        <i class="fas fa-sitemap me-1"></i> Voir l'arborescence
                    </button>
                    <a href="/bureau/{{$type_bureau}}/affectation/?bureau_code={{ $bureaux->first()->code_bureau }}"
                       class="btn btn-primary me-2">
                        <i class="fas fa-user-plus me-1"></i> Gérer les affectations
                    </a>
                @endif

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="ri-add-circle-fill"></i> Ajouter un {{$type_bureau}}
                </button>

                @if($type_bureau === 'Departement')
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#presentModal">
                    <i class="ri-presentation-fill"></i> Présentation
                </button>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="tableBureaux">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">N°</th>
                            <th width="15%">Code</th>
                            <th width="25%">Libellé</th>
                            <th width="15%">Hiérarchie</th>
                            <th width="10%">Personnel</th>
                            <th width="15%">Créé le</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($bureaux))
                        @foreach ($bureaux as $bureau)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>
                                <code class="badge bg-secondary">{{ $bureau->code_bureau }}</code>
                            </td>
                            <td>
                                <strong>{{ $bureau->label_bureau }}</strong>
                                @if($bureau->desc_bureau)
                                    <i class="fas fa-info-circle text-info ms-2"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="{{ strip_tags($bureau->desc_bureau) }}"></i>
                                @endif
                            </td>
                            <td>
                                @if($bureau->bureauParents && $bureau->bureauParents->count() > 0)
                                    <span class="badge bg-info hierarchy-badge">
                                        <i class="fas fa-level-up-alt"></i>
                                        {{ $bureau->bureauParents->first()->label_bureau }}
                                    </span>
                                @endif

                                @if($bureau->sousBureau && $bureau->sousBureau->count() > 0)
                                    <span class="badge bg-warning hierarchy-badge">
                                        <i class="fas fa-level-down-alt"></i>
                                        {{ $bureau->sousBureau->count() }} sous-bureau(x)
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $nbPersonnel = \App\Models\PersRole::where('code_bureau', $bureau->code_bureau)
                                        ->where('statut_role', \App\Models\PersRole::STATUT_ACTIF ?? 1)
                                        ->count();
                                @endphp
                                <span class="badge {{ $nbPersonnel > 0 ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas fa-users"></i> {{ $nbPersonnel }}
                                </span>
                            </td>
                            <td>
                                {{ $bureau->created_at ? $bureau->created_at->format("d/m/Y H:i") : "" }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('affectation_personnel', ['type' => $type_bureau, 'bureau_code' => $bureau->code_bureau]) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Affecter du personnel">
                                        <i class="fa fa-user"></i>
                                    </a>

                                    <a href="/update_bureau/{{ $bureau->code_bureau }}"
                                       class="btn btn-sm btn-outline-success"
                                       data-bs-toggle="tooltip"
                                       title="Modifier">
                                        <i class="bx bx-pencil"></i>
                                    </a>

                                    @if($bureau->sousBureau && $bureau->sousBureau->count() > 0)
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info"
                                            onclick="voirSousBureaux('{{ $bureau->code_bureau }}')"
                                            data-bs-toggle="tooltip"
                                            title="Voir les sous-bureaux">
                                        <i class="fas fa-sitemap"></i>
                                    </button>
                                    @endif

                                    <a href="#"
                                       onclick="confirmerSuppression('{{ $bureau->code_bureau }}', '{{ $bureau->label_bureau }}')"
                                       class="btn btn-sm btn-outline-danger"
                                       data-bs-toggle="tooltip"
                                       title="Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        @foreach (Bureau::where("type_bureau", $type_bureau)->get() as $bureau)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $bureau->code_bureau }}</td>
                            <td>{{ $bureau->label_bureau }}</td>
                            <td title="{!!$bureau->desc_bureau!!}" style="width: 30%; overflow: hidden;">{!!$bureau->desc_bureau!!}</td>
                            <td>{{ $bureau->created_at != null ? $bureau->created_at->format("d/m/Y H:i") : "" }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('affectation_personnel', ['type' => $type_bureau, 'bureau_code' => $bureau->code_bureau]) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user-plus"></i> Affecter du personnel
                                </a>
                                <a href="/delete_bureau/{{$type_bureau}}/{{$bureau->code_bureau}}"
                                   class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i></a>
                                <a href="/update_bureau/{{$bureau->code_bureau}}"
                                   class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i></a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Initialisation de TinyMCE
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '.tinymce-editor',
                    height: 300,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | bold italic backcolor | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent | removeformat | help',
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                    setup: function (editor) {
                        editor.on('init', function () {
                            editor.getContainer().style.transition = "border-color 0.15s ease-in-out";
                        });
                    }
                });
            }

            // Initialisation des tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // DataTable pour la liste des bureaux
            if ($.fn.DataTable) {
                $('#tableBureaux').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
                    },
                    order: [[1, 'asc']],
                    pageLength: 25
                });
            }

            // Génération automatique du code
            let debounceTimer;
            $('#label_bureau').on('input', function() {
                clearTimeout(debounceTimer);
                const label = $(this).val();
                const typeBureau = '{{ $type_bureau }}';
                const bureauParent = $('#bureau_parent').val();

                if (label.length > 2) {
                    $('#codePreview').removeClass('d-none');
                    debounceTimer = setTimeout(() => {
                        genererCode(typeBureau, label, bureauParent);
                    }, 500);
                } else {
                    $('#codePreview').addClass('d-none');
                }
            });

            // Régénérer le code
            $('#btnRegenerateCode').on('click', function() {
                const label = $('#label_bureau').val();
                const typeBureau = '{{ $type_bureau }}';
                const bureauParent = $('#bureau_parent').val();

                if (label) {
                    genererCode(typeBureau, label, bureauParent);
                }
            });

            // Si c'est un service, régénérer le code quand le parent change
            @if($type_bureau === 'Service')
            $('#bureau_parent').on('change', function() {
                const label = $('#label_bureau').val();
                if (label) {
                    genererCode('{{ $type_bureau }}', label, $(this).val());
                }
            });
            @endif

            // Charger l'arborescence
            $('#arborescenceModal').on('show.bs.modal', function() {
                chargerArborescence();
            });
        });

        // Fonction pour générer le code
        function genererCode(typeBureau, label, bureauParent = null) {
            $.ajax({
                url: '/api/bureau/generate-code',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type_bureau: typeBureau,
                    label_bureau: label,
                    bureau_parent: bureauParent
                },
                success: function(response) {
                    $('#code_bureau').val(response.code);
                    $('#previewCodeText').text(response.code);
                },
                error: function(xhr) {
                    console.error('Erreur lors de la génération du code:', xhr);
                }
            });
        }

        // Fonction pour voir les sous-bureaux
        function voirSousBureaux(codeBureau) {
            $('#sousBureauxModal').modal('show');
            $('#sousBureauxContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>');

            $.ajax({
                url: `/api/bureau/${codeBureau}/sous-bureaux`,
                type: 'GET',
                success: function(response) {
                    let html = '';
                    if (response.length > 0) {
                        html = '<div class="list-group">';
                        response.forEach(function(sb) {
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">${sb.label_bureau}</h6>
                                            <p class="mb-1">
                                                <code>${sb.code_bureau}</code> -
                                                <span class="badge bg-secondary">${sb.type_bureau}</span>
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-users"></i> ${sb.nb_personnel} personnel(s) -
                                                <i class="fas fa-sitemap"></i> ${sb.nb_sous_bureaux} sous-bureau(x)
                                            </small>
                                        </div>
                                        <div>
                                            <a href="/update_bureau/${sb.code_bureau}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                    } else {
                        html = '<div class="alert alert-info">Aucun sous-bureau trouvé</div>';
                    }
                    $('#sousBureauxContainer').html(html);
                },
                error: function(xhr) {
                    $('#sousBureauxContainer').html('<div class="alert alert-danger">Erreur lors du chargement</div>');
                }
            });
        }

        // Fonction pour charger l'arborescence
        function chargerArborescence() {
            $('#arborescenceContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>');

            $.ajax({
                url: '/api/bureau/arborescence',
                type: 'GET',
                success: function(response) {
                    let html = '<div class="tree">';
                    response.forEach(function(node) {
                        html += construireNoeud(node, 0);
                    });
                    html += '</div>';
                    $('#arborescenceContainer').html(html);
                },
                error: function(xhr) {
                    $('#arborescenceContainer').html('<div class="alert alert-danger">Erreur lors du chargement</div>');
                }
            });
        }

        // Fonction récursive pour construire les noeuds de l'arbre
        function construireNoeud(node, niveau) {
            let padding = niveau * 30;
            let html = `
                <div style="margin-left: ${padding}px; margin-bottom: 10px;">
                    <div class="card">
                        <div class="card-body py-2">
                            <strong>${node.label}</strong>
                            <code class="ms-2">${node.code}</code>
                            <span class="badge bg-info ms-2">${node.type}</span>
                        </div>
                    </div>
                </div>
            `;

            if (node.enfants && node.enfants.length > 0) {
                node.enfants.forEach(function(enfant) {
                    html += construireNoeud(enfant, niveau + 1);
                });
            }

            return html;
        }

        // Fonction pour confirmer la suppression
        function confirmerSuppression(codeBureau, labelBureau) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: `Voulez-vous vraiment supprimer "${labelBureau}" ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/delete_bureau/{{ $type_bureau }}/${codeBureau}`;
                }
            });
        }
    </script>
@endsection
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
@extends("sige_app.backend.template.backend")
@section('title', 'Bureaux')
<?php $user = \Session::get("user"); ?>

<style>
    .swal2-container-custom {
        z-index: 9999 !important;
    }

    .swal2-popup {
        z-index: 10000 !important;
    }
</style>

@section("content")
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary p-2" style="color: white">
                    <h5 class="modal-title" style="color: white">Ajout d'un {{$type_bureau}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/ajouter_bureau" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <input type="text" class="form-control" placeholder="Code " name="code_bureau"
                                       id="code_bureau" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-11 m-auto">
                                <input type="text" class="form-control" placeholder="Label" name="label_bureau"
                                       id="label_bureau" required>
                            </div>
                        </div>
                        <input type="hidden" value="{{$type_bureau}}" name="type_bureau">
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                            <textarea class="tinymce-editor w-100" name="desc_bureau" id="desc_bureau"
                                      placeholder="Veuillez faire une brève description ici" rows="8">
                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                                <select name="code_bureau" id="code_bureau" class="form-select">
                                    @foreach (\App\Models\Bureau::all() as $bureau)
                                        <option value="{{$bureau->code_bureau}}"> {{$bureau->label_bureau}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row m-auto justify-content-center">
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-5 m-auto">
                                        <label for="depliant">Grade et nom du CD<span
                                                class="text-danger">*</span></label>
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
                                        <input type="file" class="form-control" name="photo_chef" id="photo_chef"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-6 m-auto">
                                        <label for="depliant">Dépliant:Cursus Ingenieur<span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-6 m-auto">
                                        <input type="file" class="form-control" name="depliant_ingenieur"
                                               id="depliant_ingenieur" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row m-auto mt-3">
                                    <div class="col-sm-6 m-auto">
                                        <label for="depliant">Dépliant:Science de l'ingenieur<span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-6 m-auto">
                                        <input type="file" class="form-control" name="depliant_science"
                                               id="depliant_science" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="message_chef" id="message_chef"
                                  placeholder="Message du chef de département" rows="8">
                        </textarea>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="cursus_ing" id="cursus_ing"
                                  placeholder="Filière du Cursus Ingénieur" rows="8">
                        </textarea>
                            </div>
                        </div>
                        <div class="row m-auto mt-2 w-1">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 1 <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_1" id="document_1"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 2 <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_2" id="document_2"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-auto mt-2 w-1">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 3</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_3" id="document_3">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 4</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_4" id="document_4">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-auto mt-2 w-1">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 5</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_5" id="document_5">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="grille_ing" id="grille_ing"
                                  placeholder="Grille des programmes du Cursus Ingénieur" rows="8">
                        </textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="science_ing" id="science_ing"
                                  placeholder="Filière du Cursus Science de l'Ingenieur" rows="8">
                        </textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-11 m-auto">
                        <textarea class="tinymce-editor w-100" name="grille_science" id="grille_science"
                                  placeholder="Grille des programmes Science de l'Ingenieur" rows="8">
                        </textarea>
                            </div>
                        </div>
                        <div class="row m-auto mt-2 w-1">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 1 <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_6" id="document_6"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 2 <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_7" id="document_7"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-auto mt-2 w-1">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 3</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_8" id="document_8">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 4 </label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_9" id="document_9">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row m-auto mt-2 w-1">
                            <div class="col-sm-6 m-auto">
                                <div class="row m-auto">
                                    <div class="col-sm-3 m-auto">
                                        <label for="photo_chef">Flyer 5</label>
                                    </div>
                                    <div class="col-sm-7 m-auto">
                                        <input type="file" class="form-control" name="document_10" id="document_10">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-0">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal pour l'affectation du personnel -->
    <div class="modal fade" id="affecterPersonnelModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Affecter du personnel au {{$type_bureau}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="searchPersonnel"
                                   placeholder="Rechercher un personnel...">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" id="btnAffecterSelection">
                                <i class="fas fa-user-plus me-2"></i>Affecter la sélection
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Nom & Prénoms</th>
                                <th>Numero CNI</th>
                                <th>First phone</th>
                                <th>Second phone</th>
                                <th>Rôle</th>
                                <th>Date de fin</th>
                                <th>Statut</th>
                            </tr>
                            </thead>
                            <tbody id="personnelList">
                            <!-- La liste du personnel sera chargée ici via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="width: 90%; margin:auto;">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des {{$type_bureau}}s</h5>
            <div>
                <a href="/bureau/{{$type_bureau}}/affectation/?bureau_code={{ \App\Models\Bureau::where('type_bureau', $type_bureau)->first('code_bureau')->code_bureau }}" class="btn btn-primary me-2">
                    <i class="fas fa-user-plus me-1"></i> Gérer les affectations
                </a>
                <button class="btn btn-primary" style="font-size: 1.08em;" data-bs-toggle="modal"
                        data-bs-target="#addModal">Ajouter &nbsp; <i class="ri-add-circle-fill"></i></button>
                <button class="btn btn-success" style="font-size: 1.08em;" data-bs-toggle="modal"
                        data-bs-target="#presentModal">Présentation &nbsp; <i class="ri-add-circle-fill"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>N°</th>
                        <th>Code</th>
                        <th>Label</th>
                        <th>Description</th>
                        <th>date de création</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach (\App\Models\Bureau::where("type_bureau", $type_bureau)->get() as $bureau)
                        <tr>
                            <td> {{$loop->index +1}}  </td>
                            <td> {{$bureau->code_bureau}}  </td>
                            <td>{{$bureau->label_bureau}}</td>
                            <td title="{!!$bureau->desc_bureau!!}"
                                style="width: 30%; overflow: hidden;">{!!$bureau->desc_bureau!!}  </td>
                            <td> {{$bureau->created_at !=null? $bureau->created_at->format("d/m/Y H:i"): "" }}  </td>
                            <td style="text-align: center;">
                                <a href="{{ route('affectation_personnel', ['type' => $type_bureau, 'bureau_code' => $bureau->code_bureau]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user-plus"></i> Affecter du personnel
                                </a>
                                <a href="/delete_bureau/{{$type_bureau}}/{{$bureau->code_bureau}}"
                                   class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                <a href="/update_bureau/{{$bureau->code_bureau}}"
                                   class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                            </td>
                        </tr>
                    @endforeach
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

            let selectedPersonnel = [];
            let currentBureauCode = '';

            // Initialisation de la modal
            $('#affecterPersonnelModal').on('show.bs.modal', function (event) {
                const typeBureau = '{{ $type_bureau }}';

                // Récupérer le code du bureau en fonction du type
                $.ajax({
                    url: `/api/bureau/${typeBureau}/code`,
                    type: 'GET',
                    success: function (response) {
                        if (response.code_bureau) {
                            currentBureauCode = response.code_bureau;
                            // Réinitialiser la sélection
                            selectedPersonnel = [];
                            // Vider le champ de recherche
                            $('#searchPersonnel').val('');
                            // Charger d'abord le personnel affecté
                            loadAffectedPersonnel();
                        }
                    },
                    error: function (xhr) {
                        const tbody = $('#personnelList');
                        tbody.empty();
                        tbody.html('<tr><td colspan="8" class="text-center">Aucun bureau enregistré pour le type {{$type_bureau}}</td></tr>');
                    }
                });
            });

            // Réinitialiser la modal d'affectation à la fermeture
            $('#affecterPersonnelModal').on('hidden.bs.modal', function () {
                selectedPersonnel = [];
                currentBureauCode = '';
                $('#searchPersonnel').val('');
                $('#personnelList').empty();
                // Réinitialiser tous les champs de formulaire
                $('.personnel-checkbox').prop('checked', false);
                $('.role-select').prop('disabled', true).val('');
                $('.date-fin-select').prop('disabled', true).val('');
                $('.statut-select').prop('disabled', true).val('1');
            });

            // Charger le personnel déjà affecté
            function loadAffectedPersonnel() {
                if (!currentBureauCode) return;

                $.ajax({
                    url: `/api/bureau/${currentBureauCode}/personnel`,
                    type: 'GET',
                    success: function (response) {
                        // Mettre à jour selectedPersonnel avec toutes les données nécessaires
                        selectedPersonnel = response.map(personnel => ({
                            id: personnel.id,
                            role_id: personnel.role_id,
                            date_fin_role: personnel.date_fin,
                            statut_role: personnel.statut === 'Actif' ? '1' : '0'
                        }));
                        const tbody = $('#personnelList');
                        tbody.empty();
                        if (response.length === 0) {
                            tbody.html('<tr><td colspan="8" class="text-center">Aucun personnel trouvé pour ce bureau</td></tr>');
                        }
                    },
                    error: function (xhr) {
                        console.error('Erreur:', xhr);
                        toastr.error('Erreur lors du chargement des affectations');
                    }
                });
            }

            // Charger la liste du personnel
            function loadPersonnelList(search = '') {
                $.ajax({
                    url: '{{ route("api.personnel.search") }}',
                    type: 'GET',
                    data: {search: search},
                    success: function (response) {
                        updatePersonnelList(response);
                    },
                    error: function (xhr) {
                        console.error('Erreur:', xhr);
                        toastr.error('Erreur lors du chargement du personnel');
                    }
                });
            }

            // Mettre à jour le tableau du personnel
            function updatePersonnelList(personnelList = []) {
                const tbody = $('#personnelList');
                tbody.empty();

                if (personnelList.length === 0) {
                    tbody.html('<tr><td colspan="8" class="text-center">Aucun personnel trouvé pour ce bureau</td></tr>');
                    return;
                }

                personnelList.forEach(personnel => {
                    const isSelected = selectedPersonnel.some(p => p.id === personnel.id);
                    const selectedRole = isSelected
                        ? selectedPersonnel.find(p => p.id === personnel.id)
                        : null;

                    const row = `
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input personnel-checkbox"
                                       data-id="${personnel.id}" ${isSelected ? 'checked' : ''}>
                            </td>
                            <td>${personnel.nom} ${personnel.prenom}</td>
                            <td>${personnel.num_cni || 'N/A'}</td>
                            <td>${personnel.first_phone || 'N/A'}</td>
                            <td>${personnel.second_phone || 'N/A'}</td>
                            <td>
                                <select class="form-select form-select-sm role-select"
                                        data-id="${personnel.id}"
                                        ${!isSelected ? 'disabled' : ''}>
                                    <option value="">Sélectionner un rôle</option>
                                    @foreach(\App\Models\Role::all() as $role)
                                        <option value="{{ $role->id }}"
                                            ${selectedRole && selectedRole.role_id == '{{ $role->id }}' ? 'selected' : ''}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date" class="form-control form-control-sm date-fin-select"
                                       data-id="${personnel.id}"
                                       value="${selectedRole ? selectedRole.date_fin_role : ''}"
                                       ${!isSelected ? 'disabled' : ''}>
                                    ${ selectedRole && selectedRole.date_fin_role ? selectedRole.date_fin_role  : '<span><span/>' }
                            </td>
                            <td>
                                <select class="form-select form-select-sm statut-select"
                                        data-id="${personnel.id}"
                                        ${!isSelected ? 'disabled' : ''}>
                                    <option value="1" ${selectedRole && selectedRole.statut_role === '1' ? 'selected' : ''}>Actif</option>
                                    <option value="0" ${selectedRole && selectedRole.statut_role === '0' ? 'selected' : ''}>Inactif</option>
                                </select>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }

            // Gestion de la sélection/désélection
            $(document).on('change', '.personnel-checkbox', function () {
                const personnelId = $(this).data('id');
                const isChecked = $(this).is(':checked');
                const roleSelect = $(`.role-select[data-id="${personnelId}"]`);
                const dateFinSelect = $(`.date-fin-select[data-id="${personnelId}"]`);
                const statutSelect = $(`.statut-select[data-id="${personnelId}"]`);

                roleSelect.prop('disabled', !isChecked);
                dateFinSelect.prop('disabled', !isChecked);
                statutSelect.prop('disabled', !isChecked);

                if (isChecked) {
                    // Ajouter à la sélection
                    if (!selectedPersonnel.some(p => p.id === personnelId)) {
                        selectedPersonnel.push({
                            id: personnelId,
                            role_id: roleSelect.val() || null,
                            date_fin_role: dateFinSelect.val() || null,
                            statut_role: statutSelect.val() || 1
                        });
                    }
                } else {
                    // Retirer de la sélection
                    selectedPersonnel = selectedPersonnel.filter(p => p.id !== personnelId);
                }
            });

            // Gestion du changement de rôle
            $(document).on('change', '.role-select, .date-fin-select, .statut-select', function () {
                const personnelId = $(this).data('id');
                const personnel = selectedPersonnel.find(p => p.id === personnelId);

                if (personnel) {
                    if ($(this).hasClass('role-select')) {
                        personnel.role_id = $(this).val();
                    } else if ($(this).hasClass('date-fin-select')) {
                        personnel.date_fin_role = $(this).val();
                    } else if ($(this).hasClass('statut-select')) {
                        personnel.statut_role = $(this).val();
                    }
                }
            });

            // Recherche de personnel
            $('#searchPersonnel').on('keyup', debounce(function () {
                loadPersonnelList($(this).val());
            }, 300));

            // Fonction de debounce pour la recherche
            function debounce(func, wait) {
                let timeout;
                return function () {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            // Enregistrer les affectations
            $('#btnAffecterSelection').on('click', function () {
                if (selectedPersonnel.length === 0) {
                    toastr.warning('Veuillez sélectionner au moins un membre du personnel');
                    return;
                }

                // Vérifier qu'un rôle est sélectionné pour chaque personnel
                const hasMissingRoles = selectedPersonnel.some(p => !p.role_id);
                if (hasMissingRoles) {
                    toastr.error('Veuillez sélectionner un rôle pour chaque membre du personnel');
                    return;
                }

                // Afficher la confirmation sans fermer le modal
                Swal.fire({
                    title: 'Confirmer l\'affectation',
                    text: `Voulez-vous vraiment affecter ${selectedPersonnel.length} membre(s) du personnel ?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, confirmer',
                    cancelButtonText: 'Annuler',
                    customClass: {
                        container: 'swal2-container-custom'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Fermer le modal seulement après la confirmation
                        $('#affecterPersonnelModal').modal('hide');
                        saveAffectations();
                    }
                });
            });

            // Enregistrer les affectations
            function saveAffectations() {
                $.ajax({
                    url: '{{ route("api.bureau.affecter-personnel") }}',
                    type: 'POST',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        bureau_code: currentBureauCode,
                        personnels: selectedPersonnel
                    }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message || 'Affectations enregistrées avec succès');
                            // Recharger la page ou mettre à jour l'interface
                            window.location.reload();
                        } else {
                            toastr.error(response.message || 'Une erreur est survenue');
                            // Réouvrir le modal en cas d'erreur
                            $('#affecterPersonnelModal').modal('show');
                        }
                    },
                    error: function (xhr) {
                        console.error('Erreur:', xhr);
                        let errorMessage = 'Une erreur est survenue';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                        // Réouvrir le modal en cas d'erreur
                        $('#affecterPersonnelModal').modal('show');
                    }
                });
            }
        });
    </script>
@endsection
</body>
</html>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gestion des Affectations - {{$type_bureau}}</title>
</head>
<body>
@extends("sige_app.backend.template.backend")
@section('title', 'Gestion des Affectations - ' . $type_bureau)

<style>
    .personnel-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .personnel-card.selected {
        border-color: #007bff;
        background-color: #f8f9fa;
    }

    .role-badge {
        font-size: 0.8em;
        margin: 2px;
    }

    .search-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .affectation-form {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .personnel-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .role-selection {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }

    .date-input-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .bulk-actions {
        background: #e3f2fd;
        border-radius: 10px;
        padding: 15px;
        margin: 20px 0;
    }
</style>

@section("content")
<div class="container-fluid">
    <!-- Sélecteur de bureau -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="selectBureau" class="form-label fw-bold">Choisir le bureau :</label>
            <select id="selectBureau" class="form-select">
                @foreach($bureaux as $b)
                    <option value="{{ $b->code_bureau }}" {{ $bureau->code_bureau == $b->code_bureau ? 'selected' : '' }}>
                        {{ $b->label_bureau }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-container text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Gestion des Affectations - {{$type_bureau}}
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">Recherchez et affectez du personnel aux bureaux</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-light" onclick="window.history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card text-center">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h4 id="totalPersonnel">0</h4>
                <p class="text-muted mb-0">Personnel Total</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                <h4 id="personnelAffecte">0</h4>
                <p class="text-muted mb-0">Personnel Affecté</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <i class="fas fa-user-clock fa-2x text-warning mb-2"></i>
                <h4 id="personnelSelectionne">0</h4>
                <p class="text-muted mb-0">Sélectionné</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center">
                <i class="fas fa-shield-alt fa-2x text-info mb-2"></i>
                <h4 id="totalRoles">{{ \App\Models\Role::count() }}</h4>
                <p class="text-muted mb-0">Rôles Disponibles</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Section de recherche et sélection -->
        <div class="col-md-8">
            <div class="affectation-form">
                <h5 class="mb-3">
                    <i class="fas fa-search me-2"></i>Recherche et Sélection
                </h5>

                <!-- Barre de recherche -->
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchPersonnel"
                           placeholder="Rechercher par nom, prénom, code personnel, CNI ou téléphone...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Actions en lot -->
                <div class="bulk-actions" id="bulkActions" style="display: none;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">
                                <i class="fas fa-users me-2"></i>
                                <span id="selectedCount">0</span> personnel(s) sélectionné(s)
                            </h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-sm btn-outline-secondary me-2" id="selectAll">
                                <i class="fas fa-check-double me-1"></i>Tout sélectionner
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                <i class="fas fa-times me-1"></i>Tout désélectionner
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Liste du personnel -->
                <div class="personnel-list" id="personnelList">
                    <!-- Le personnel sera chargé ici -->
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3" id="pagination">
                    <!-- La pagination sera générée ici -->
                </div>
            </div>
        </div>

        <!-- Section de configuration des rôles -->
        <div class="col-md-4">
            <div class="affectation-form">
                <h5 class="mb-3">
                    <i class="fas fa-cog me-2"></i>Configuration des Rôles
                </h5>

                <!-- Sélection des rôles -->
                <div class="role-selection">
                    <label class="form-label fw-bold">Rôles à affecter :</label>
                    <div id="rolesSelection">
                        @foreach(\App\Models\Role::all() as $role)
                        <div class="form-check mb-2">
                            <input class="form-check-input role-checkbox" type="checkbox"
                                   value="{{ $role->id }}" id="role_{{ $role->id }}">
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Dates d'affectation -->
                <div class="mt-3">
                    <label class="form-label fw-bold">Période d'affectation :</label>
                    <div class="date-input-group">
                        <div class="flex-grow-1">
                            <label class="form-label small">Date de début</label>
                            <input type="date" class="form-control" id="dateDebut"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label small">Date de fin</label>
                            <input type="date" class="form-control" id="dateFin">
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="mt-3">
                    <label class="form-label fw-bold">Statut :</label>
                    <select class="form-select" id="statutRole">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>

                <!-- Boutons d'action -->
                <div class="mt-4">
                    <button class="btn btn-primary w-100 mb-2" id="btnAffecter">
                        <i class="fas fa-user-plus me-2"></i>Affecter les rôles
                    </button>
                    <button class="btn btn-outline-secondary w-100" id="btnVoirAffectations">
                        <i class="fas fa-list me-2"></i>Voir les affectations
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les affectations -->
<div class="modal fade" id="affectationsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-list me-2"></i>Affectations actuelles
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Barre de recherche -->
                <div class="mb-2">
                    <input type="text" id="searchAffectation" class="form-control" placeholder="Rechercher un personnel, rôle...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Personnel</th>
                                <th>Rôles</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="affectationsList">
                            <!-- Les affectations seront chargées ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    let selectedPersonnel = [];
    let currentPage = 1;
    let searchTerm = '';
    let currentBureauCode = '{{ $bureau->code_bureau ?? "" }}';
    // Initialisation
    loadPersonnel();
    updateStats();

    // Recherche
    $('#searchPersonnel').on('keyup', debounce(function() {
        searchTerm = $(this).val();
        currentPage = 1;
        loadPersonnel();
    }, 300));

    $('#clearSearch').on('click', function() {
        $('#searchPersonnel').val('');
        searchTerm = '';
        currentPage = 1;
        loadPersonnel();
    });

    // Sélection en lot
    $('#selectAll').on('click', function() {
        $('.personnel-checkbox').prop('checked', true).trigger('change');
    });

    $('#deselectAll').on('click', function() {
        $('.personnel-checkbox').prop('checked', false).trigger('change');
    });

    // Gestion de la sélection
    $(document).on('change', '.personnel-checkbox', function() {
        const personnelId = $(this).val();
        const isChecked = $(this).is(':checked');

        if (isChecked) {
            if (!selectedPersonnel.includes(personnelId)) {
                selectedPersonnel.push(personnelId);
            }
        } else {
            selectedPersonnel = selectedPersonnel.filter(id => id !== personnelId);
        }

        updateSelectionUI();
    });

    // Affectation des rôles
    $('#btnAffecter').on('click', function() {
        if (selectedPersonnel.length === 0) {
            toastr.warning('Veuillez sélectionner au moins un personnel');
            return;
        }

        const selectedRoles = $('.role-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedRoles.length === 0) {
            toastr.warning('Veuillez sélectionner au moins un rôle');
            return;
        }

        const dateDebut = $('#dateDebut').val();
        const dateFin = $('#dateFin').val();
        const statut = $('#statutRole').val();

        if (!dateDebut) {
            toastr.warning('Veuillez spécifier une date de début');
            return;
        }

        if (dateFin && dateFin <= dateDebut) {
            toastr.warning('La date de fin doit être postérieure à la date de début');
            return;
        }

        // Confirmation
        Swal.fire({
            title: 'Confirmer l\'affectation',
            html: `
                <p>Vous allez affecter <strong>${selectedRoles.length}</strong> rôle(s) à <strong>${selectedPersonnel.length}</strong> personnel(s)</p>
                <p><strong>Période :</strong> ${dateDebut} ${dateFin ? 'à ' + dateFin : '(sans date de fin)'}</p>
                <p><strong>Statut :</strong> ${statut == 1 ? 'Actif' : 'Inactif'}</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                affecterRoles();
            }
        });
    });

    // Voir les affectations
    $('#btnVoirAffectations').on('click', function() {
        loadAffectations();
        $('#affectationsModal').modal('show');
    });

    // Recherche sur la liste des affectations
    $('#searchAffectation').on('keyup', function() {
        const search = $(this).val().toLowerCase();
        $('#affectationsList tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.indexOf(search) !== -1);
        });
    });

    // Fonctions
    function loadPersonnel() {
        $.ajax({
            url: '{{ route("api.personnel.search") }}',
            type: 'GET',
            data: {
                search: searchTerm,
                page: currentPage
            },
            success: function(response) {
                updatePersonnelList(response.data);
                updatePagination(response.pagination);
                updateStats();
            },
            error: function(xhr) {
                console.error('Erreur:', xhr);
                toastr.error('Erreur lors du chargement du personnel');
            }
        });
    }

    function updatePersonnelList(personnelList) {
        const container = $('#personnelList');
        container.empty();

        if (personnelList.length === 0) {
            container.html(`
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun personnel trouvé</p>
                </div>
            `);
            return;
        }

        personnelList.forEach(personnel => {
            const isSelected = selectedPersonnel.includes(personnel.id);
            const card = `
                <div class="card personnel-card mb-2 ${isSelected ? 'selected' : ''}" data-id="${personnel.id}">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input type="checkbox" class="form-check-input personnel-checkbox"
                                       value="${personnel.id}" ${isSelected ? 'checked' : ''}>
                            </div>
                            <div class="col">
                                <h6 class="mb-1">${personnel.nom} ${personnel.prenom}</h6>
                                <div class="row text-muted small">
                                    <div class="col-md-3">
                                        <i class="fas fa-id-card me-1"></i>${personnel.num_cni || 'N/A'}
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-phone me-1"></i>${personnel.first_phone || 'N/A'}
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-mobile-alt me-1"></i>${personnel.second_phone || 'N/A'}
                                    </div>
                                    <div class="col-md-3">
                                        <i class="fas fa-user-tag me-1"></i>${personnel.id}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }

    function updateSelectionUI() {
        const count = selectedPersonnel.length;
        $('#selectedCount').text(count);
        $('#personnelSelectionne').text(count);

        if (count > 0) {
            $('#bulkActions').show();
        } else {
            $('#bulkActions').hide();
        }
    }

    function updateStats() {
        // Charger les statistiques via API
        $.ajax({
            url: `/api/bureau/${currentBureauCode}/stats`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#totalPersonnel').text(response.data.total_personnel);
                    $('#personnelAffecte').text(response.data.personnel_affecte);
                }
            },
            error: function(xhr) {
                console.error('Erreur lors du chargement des statistiques:', xhr);
            }
        });

        $('#personnelSelectionne').text(selectedPersonnel.length);
    }

    function affecterRoles() {
        const selectedRoles = $('.role-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        const affectations = selectedPersonnel.map(personnelId => ({
            id: personnelId,
            roles: selectedRoles.map(roleId => ({
                role_id: roleId,
                date_debut_role: $('#dateDebut').val(),
                date_fin_role: $('#dateFin').val() || null,
                statut_role: $('#statutRole').val()
            }))
        }));

        $.ajax({
            url: '{{ route("api.bureau.affecter-personnel-multiple") }}',
            type: 'POST',
            data: JSON.stringify({
                _token: '{{ csrf_token() }}',
                bureau_code: currentBureauCode,
                affectations: affectations
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    selectedPersonnel = [];
                    updateSelectionUI();
                    loadPersonnel();
                    updateStats();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                console.error('Erreur:', xhr);
                let errorMessage = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            }
        });
    }

    function loadAffectations() {
        $.ajax({
            url: `/api/bureau/${currentBureauCode}/personnel`,
            type: 'GET',
            success: function(response) {
                updateAffectationsList(response);
            },
            error: function(xhr) {
                console.error('Erreur:', xhr);
                toastr.error('Erreur lors du chargement des affectations');
            }
        });
    }

    function updateAffectationsList(affectations) {
        const tbody = $('#affectationsList');
        tbody.empty();

        if (affectations.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-info-circle text-muted me-2"></i>
                        Aucune affectation trouvée
                    </td>
                </tr>
            `);
            return;
        }

        affectations.forEach(affectation => {
            const isActif = affectation.statut === 'Actif';
            const row = `
                <tr>
                    <td>
                        <strong>${affectation.nom} ${affectation.prenom}</strong><br>
                        <small class="text-muted">${affectation.id}</small>
                    </td>
                    <td>
                        <span class="badge bg-primary role-badge">${affectation.role_libelle}</span>
                    </td>
                    <td>${affectation.date_debut || 'N/A'}</td>
                    <td>${affectation.date_fin || 'Sans limite'}</td>
                    <td>
                        <span class="badge bg-${isActif ? 'success' : 'secondary'}">
                            ${affectation.statut}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-${isActif ? 'warning' : 'success'}"
                                    onclick="toggleRole('${affectation.id}', '${affectation.role_id}')"
                                    title="${isActif ? 'Désactiver' : 'Activer'} le rôle">
                                <i class="fas fa-${isActif ? 'pause' : 'play'}"></i>
                            </button>
                            <button class="btn btn-outline-danger"
                                    onclick="supprimerAffectation('${affectation.id}', '${affectation.role_id}')"
                                    title="Supprimer l'affectation">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function toggleRole(personnelId, roleId) {
        Swal.fire({
            title: 'Modifier le statut du rôle ?',
            text: 'Cette action changera le statut du rôle pour ce personnel',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("api.bureau.toggle-role") }}',
                    type: 'POST',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        bureau_code: currentBureauCode,
                        personnel_id: personnelId,
                        role_id: roleId
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            loadAffectations();
                            updateStats();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Erreur:', xhr);
                        toastr.error('Erreur lors de la modification');
                    }
                });
            }
        });
    }

    function supprimerAffectation(personnelId, roleId) {
        Swal.fire({
            title: 'Supprimer l\'affectation ?',
            text: 'Cette action supprimera l\'affectation pour ce personnel',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("api.bureau.supprimer-affectation") }}',
                    type: 'POST',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        bureau_code: currentBureauCode,
                        personnel_id: personnelId,
                        role_id: roleId
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            loadAffectations();
                            updateStats();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Erreur:', xhr);
                        toastr.error('Erreur lors de la suppression');
                    }
                });
            }
        });
    }

    function updatePagination(pagination) {
        const container = $('#pagination');
        container.empty();

        if (pagination.last_page <= 1) return;

        let paginationHtml = '<ul class="pagination">';

        // Bouton précédent
        if (pagination.current_page > 1) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${pagination.current_page - 1})">Précédent</a>
            </li>`;
        }

        // Pages
        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === pagination.current_page) {
                paginationHtml += `<li class="page-item active">
                    <span class="page-link">${i}</span>
                </li>`;
            } else {
                paginationHtml += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                </li>`;
            }
        }

        // Bouton suivant
        if (pagination.current_page < pagination.last_page) {
            paginationHtml += `<li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${pagination.current_page + 1})">Suivant</a>
            </li>`;
        }

        paginationHtml += '</ul>';
        container.html(paginationHtml);
    }

    function changePage(page) {
        currentPage = page;
        loadPersonnel();
    }

    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Exposer la fonction globalement
    window.toggleRole = toggleRole;
    window.supprimerAffectation = supprimerAffectation;

    // Gestion du changement de bureau
    $('#selectBureau').on('change', function() {
        const bureauCode = $(this).val();
        const url = new URL(window.location.href);
        url.searchParams.set('bureau_code', bureauCode);
        window.location.href = url.toString();
    });
});
</script>
@endsection
</body>
</html>

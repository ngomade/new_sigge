@extends('sige_app.backend.template.backend')

@section('title', 'Membres du laboratoire - ' . $laboratoire->label_labo)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class='bx bx-group'></i> Membres du laboratoire : {{ $laboratoire->label_labo }}
                    </h4>
                    <a href="{{ route('labo.laboratoires.membres.create', $laboratoire) }}" class="btn btn-primary">
                        <i class='bx bx-user-plus'></i> Ajouter un membre
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class='bx bx-check-circle'></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class='bx bx-error-circle'></i> {{ session('error') }}
                        </div>
                    @endif

                    @if($membres->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Rôle</th>
                                        <th>Date d'affectation</th>
                                        <th>Date de fin</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($membres as $membre)
                                        <tr>
                                            <td>
                                                @if($membre->persLab)
                                                    @if($membre->persLab->type_pers_lab === 'personnel')
                                                        @php
                                                            $personnel = \App\Models\Personnel::where('code_pers', $membre->persLab->id_pers_lab)->first();
                                                        @endphp
                                                        {{ $personnel ? $personnel->nom_pers . ' ' . $personnel->prenom_pers : 'N/A' }}
                                                    @elseif($membre->persLab->type_pers_lab === 'users')
                                                        @php
                                                            $user = \App\Models\Users::where('code_user', $membre->persLab->id_pers_lab)->first();
                                                        @endphp
                                                        {{ $user ? $user->nom_user . ' ' . $user->prenom_user : 'N/A' }}
                                                    @elseif($membre->persLab->type_pers_lab === 'user_externe')
                                                        @php
                                                            $userExt = \App\Models\laboratoires\UserExterne::where('id_user_ext', $membre->persLab->id_pers_lab)->first();
                                                        @endphp
                                                        {{ $userExt ? $userExt->nom_user_ext . ' ' . $userExt->prenom_user_ext : 'N/A' }}
                                                    @else
                                                        <span class="text-muted">Type inconnu</span>
                                                    @endif
                                                @elseif($membre->userExterne)
                                                    {{ $membre->userExterne->nom_user_ext }} {{ $membre->userExterne->prenom_user_ext }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($membre->persLab)
                                                    <span class="badge bg-{{ $membre->persLab->type_pers_lab === 'personnel' ? 'primary' : ($membre->persLab->type_pers_lab === 'users' ? 'success' : 'warning') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $membre->persLab->type_pers_lab)) }}
                                                    </span>
                                                @elseif($membre->userExterne)
                                                    <span class="badge bg-warning">Externe</span>
                                                @else
                                                    <span class="badge bg-secondary">Inconnu</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($membre->roleLabo)
                                                    <span class="badge bg-info">{{ $membre->roleLabo->lib_rl }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Aucun rôle</span>
                                                @endif
                                            </td>
                                            <td>{{ $membre->date_affectation ? $membre->date_affectation->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $membre->date_fin_affectation ? $membre->date_fin_affectation->format('d/m/Y') : 'En cours' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $membre->statut === 'actif' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($membre->statut) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $membreId = $membre->id_pers_lab ?? $membre->id_user_externe;
                                                    $isExterne = $membre->userExterne || (isset($membre->persLab) && $membre->persLab->type_pers_lab === 'user_externe');
                                                    $nomMembre = '';
                                                    if(isset($personnel)) $nomMembre = $personnel->nom_pers ?? '';
                                                    elseif(isset($user)) $nomMembre = $user->nom_user ?? '';
                                                    elseif(isset($userExt)) $nomMembre = $userExt->nom_user_ext ?? '';
                                                    elseif($membre->userExterne) $nomMembre = $membre->userExterne->nom_user_ext ?? '';
                                                @endphp
                                                <div class="btn-group" role="group">
                                                    @if($membreId)
                                                        <a href="{{ route('labo.laboratoires.membres.edit', [$laboratoire, $membreId]) }}"
                                                           class="btn btn-sm btn-outline-primary" title="Modifier">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                        <form action="{{ route('labo.laboratoires.membres.destroy', [$laboratoire, $membreId]) }}"
                                                              method="POST" class="d-inline" id="delete-form-{{ $membreId }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-membre" title="Supprimer"
                                                                    data-id="{{ $membreId }}"
                                                                    data-name="{{ $nomMembre }}">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">Actions indisponibles</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class='bx bx-group' style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucun membre dans ce laboratoire pour le moment.</p>
                            <a href="{{ route('labo.laboratoires.membres.create', $laboratoire) }}" class="btn btn-primary">
                                <i class='bx bx-user-plus'></i> Ajouter le premier membre
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('labo.laboratoires.show', $laboratoire) }}" class="btn btn-outline-secondary">
                        <i class='bx bx-arrow-back'></i> Retour au laboratoire
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer le membre <strong id="memberName"></strong> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
      </div>
    </div>
  </div>
</div>

 @push('scripts')
<script>
$(document).ready(function () {
    var deleteFormId = null;
    var $confirmDeleteModal = $('#confirmDeleteModal');
    var $memberNameElem = $('#memberName');
    var $confirmDeleteBtn = $('#confirmDeleteBtn');

    console.log('Modal element:', $confirmDeleteModal.length);

    $(document).on('click', '.btn-delete-membre', function() {
        console.log('Delete button clicked');
        deleteFormId = 'delete-form-' + $(this).data('id');
        console.log('Form ID to delete:', deleteFormId);
        $memberNameElem.text($(this).data('name') || 'ce membre');
        $confirmDeleteModal.modal('show');
        console.log('Modal should be shown now');
    });

    $confirmDeleteBtn.on('click', function() {
        console.log('Confirm delete button clicked');
        if (deleteFormId) {
            var form = document.getElementById(deleteFormId);
            if (form) {
                form.submit();
            }
        }
    });
});
</script>
 @endpush

@endsection

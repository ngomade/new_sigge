@php
    $userId = session('user_id');
    $userType = session('user_type');
    $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
        ->where('statut', 'actif')
        ->where(function ($q) use ($userId, $userType) {
            if ($userType === 'externe') {
                $q->where('id_user_externe', $userId);
            } else {
                $q->where('id_pers_lab', $userId);
            }
        })
        ->with('roleLabo')
        ->first();
    $userRole = $affectation && $affectation->roleLabo ? strtolower($affectation->roleLabo->lib_rl) : null;
@endphp
@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-user-plus'></i> Gestion des candidatures - {{ $laboratoire->label_labo }}</h2>
        <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back'></i> Retour au dashboard
        </a>
    </div>

    {{-- @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif --}}

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Recherche (nom, prénom, email)" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ $statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="actif" {{ $statut == 'actif' ? 'selected' : '' }}>Approuvé</option>
                        <option value="rejeté" {{ $statut == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laboratoires.admin.candidatures', $laboratoire->code_lab) }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des candidatures -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Candidatures ({{ $candidatures->total() }})</h5>
        </div>
        <div class="card-body">
            @if($candidatures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th>Date candidature</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidatures as $candidature)
                                <tr>
                                    <td>
                                        <strong>{{ $candidature->nom_user_ext }} {{ $candidature->prenom_user_ext }}</strong>
                                    </td>
                                    <td>{{ $candidature->email_user_ext }}</td>
                                    <td>{{ $candidature->tel_user_ext }}</td>
                                    <td>
                                        @if($candidature->statut == 'inactif')
                                            <span class="badge bg-warning">Inactif</span>
                                        @elseif($candidature->statut == 'actif')
                                            <span class="badge bg-success">Approuvé</span>
                                        @else
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                    <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('laboratoires.admin.candidatures.show', [$laboratoire->code_lab, $candidature->id_user_ext]) }}" class="btn btn-sm btn-info">
                                            <i class='bx bx-show'></i> Voir
                                        </a>
                                        @if($candidature->statut == 'en_attente')
                                            <form method="POST" action="{{ route('laboratoires.admin.candidatures.approve', [$laboratoire->code_lab, $candidature->id_user_ext]) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approuver cette candidature ?')">
                                                    <i class='bx bx-check'></i> Approuver
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="openRejectModal('{{ $candidature->id_user_ext }}')">
                                                <i class='bx bx-x'></i> Rejeter
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $candidatures->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class='bx bx-user-x' style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">Aucune candidature trouvée</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal globale pour saisir le motif du rejet -->
<div class="modal fade" id="modalMotifRejetGlobal" tabindex="-1" aria-labelledby="modalMotifRejetLabelGlobal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalMotifRejetLabelGlobal">Motif du rejet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form id="formRejetGlobal" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="motif_rejet_global" class="form-label">Veuillez indiquer le motif du rejet <span class="text-danger">*</span></label>
            <textarea name="motif_rejet" id="motif_rejet_global" class="form-control" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
    function openRejectModal(idUserExt) {
        if(confirm('Rejeter cette candidature ?')) {
            var modal = new bootstrap.Modal(document.getElementById('modalMotifRejetGlobal'));
            var form = document.getElementById('formRejetGlobal');
            // Met à jour dynamiquement l'action du formulaire
            form.action = "{{ route('laboratoires.admin.candidatures.reject', [$laboratoire->code_lab, 'IDUSER']) }}".replace('IDUSER', idUserExt);
            // Vide le champ motif
            document.getElementById('motif_rejet_global').value = '';
            modal.show();
        }
    }
</script>
@endpush
@endsection

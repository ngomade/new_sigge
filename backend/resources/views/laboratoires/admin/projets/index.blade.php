@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-folder'></i> Gestion des projets - {{ $laboratoire->label_labo }}</h2>
        <div>
            @php
                $userId = session('user_id');
                $userType = session('user_type');
                $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', session('laboratoire_code'))
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

            @if($userRole === 'admin' || $userRole === 'chef_projet')
            <a href="{{ route('laboratoires.admin.projets.create', $laboratoire->code_lab) }}" class="btn btn-success">
                <i class='bx bx-plus'></i> Nouveau projet
            </a>
            @endif
            <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                <i class='bx bx-arrow-back'></i> Retour au dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Recherche (thème, description)" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="En cours" {{ $statut == 'En cours' ? 'selected' : '' }}>En cours</option>
                        <option value="Terminé" {{ $statut == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                        <option value="En pause" {{ $statut == 'En pause' ? 'selected' : '' }}>En pause</option>
                        <option value="Annulé" {{ $statut == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('laboratoires.admin.projets', $laboratoire->code_lab) }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Projets ({{ $projets->total() }})</h5>
        </div>
        <div class="card-body">
            @if($projets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Thème</th>
                                <th>Statut</th>
                                <th>Période</th>
                                <th>Participants</th>
                                <th>Documents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projets as $projet)
                                <tr>
                                    <td>
                                        <strong>{{ $projet->theme_projet }}</strong>
                                        <br><small class="text-muted">{{ Str::limit(strip_tags($projet->description_projet), 100) }}</small>
                                    </td>
                                    <td>
                                        @if($projet->statut_projet == 'En cours')
                                            <span class="badge bg-success">En cours</span>
                                        @elseif($projet->statut_projet == 'Terminé')
                                            <span class="badge bg-secondary">Terminé</span>
                                        @elseif($projet->statut_projet == 'En pause')
                                            <span class="badge bg-warning">En pause</span>
                                        @else
                                            <span class="badge bg-danger">Annulé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            Du {{ $projet->debut_projet ? \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') : 'N/A' }}
                                            @if($projet->fin_projet)
                                                <br>Au {{ \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $projet->participants->count() }} participant(s)</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $projet->docs->count() }} document(s)</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('laboratoires.admin.projets.show', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-info">
                                                <i class='bx bx-show'></i>
                                            </a>

                                            @if($userRole === 'admin' || $userRole === 'chef_projet')
                                            <a href="{{ route('laboratoires.admin.projets.edit', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-primary">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            @endif

                                            @if($userRole === 'admin' || $userRole === 'chef_projet')
                                            <a href="{{ route('laboratoires.admin.projets.participants', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-warning">
                                                <i class='bx bx-group'></i>
                                            </a>
                                            @endif
                                            <a href="{{ route('laboratoires.admin.projets.documents', [$laboratoire->code_lab, $projet->code_projet]) }}" class="btn btn-sm btn-secondary">
                                                <i class='bx bx-file'></i>
                                            </a>

                                            @if($userRole === 'admin' || $userRole === 'chef_projet')
                                            <form method="POST" action="{{ route('laboratoires.admin.projets.destroy', [$laboratoire->code_lab, $projet->code_projet]) }}" class="d-inline" onsubmit="return confirm('Confirmer la suppression de ce projet ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $projets->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class='bx bx-folder-open' style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">Aucun projet trouvé</p>
                    @if($userRole === 'admin' || $userRole === 'chef_projet')
                    <a href="{{ route('laboratoires.admin.projets.create', $laboratoire->code_lab) }}" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Créer le premier projet
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

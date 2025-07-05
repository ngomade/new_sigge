@extends('sige_app.backend.template.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestion des rôles de laboratoire</h4>
                    <a href="{{ route('labo.roles.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Ajouter un rôle
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Libellé</th>
                                    <th>Personnes assignées</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->id_rl }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $role->lib_rl }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $role->affectations ? $role->affectations->count() : 0 }}</span>
                                            affectations
                                        </td>
                                        <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('labo.roles.show', $role->id_rl) }}"
                                                   class="btn btn-sm btn-info" title="Voir">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('labo.roles.edit', $role->id_rl) }}"
                                                   class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('labo.roles.destroy', $role->id_rl) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"
                                                            {{ ($role->affectations && $role->affectations->count() > 0) ? 'disabled' : '' }}>
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="bx bx-info-circle"></i> Aucun rôle trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($roles->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $roles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('sige_app.backend.template.backend')

@section('js')
    <script>
        function confirmDelete(form) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce laboratoire ?')) {
                form.submit();
            }
        }
    </script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Gestion des Laboratoires</h4>
                        <a href="{{ route('labo.laboratoires.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Nouveau Laboratoire
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Projets</th>
                                        <th>Membres</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laboratoires as $laboratoire)
                                        <tr>
                                            <td>
                                                <strong>{{ $laboratoire->code_lab }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $laboratoire->label_labo }}</strong>
                                                    @if($laboratoire->logo_labo)
                                                        <br><small class="text-muted">Logo disponible</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <i class="bx bx-envelope text-muted me-1"></i>
                                                {{ $laboratoire->email_labo }}
                                            </td>
                                            <td>
                                                <i class="bx bx-phone text-muted me-1"></i>
                                                {{ $laboratoire->tel_labo }}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="bx bx-folder me-1"></i>
                                                    {{ $laboratoire->projets ? $laboratoire->projets->count() : 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="bx bx-group me-1"></i>
                                                    {{ $laboratoire->membres ? $laboratoire->membres->count() : 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('labo.laboratoires.show', $laboratoire->code_lab) }}"
                                                        class="btn btn-sm btn-info" title="Voir les détails">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('labo.laboratoires.edit', $laboratoire->code_lab) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('labo.laboratoires.destroy', $laboratoire->code_lab) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete(this.form)" title="Supprimer">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bx bx-info-circle fs-1"></i>
                                                <p class="mt-2">Aucun laboratoire trouvé</p>
                                                <a href="{{ route('labo.laboratoires.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="bx bx-plus"></i> Créer le premier laboratoire
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($laboratoires->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $laboratoires->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

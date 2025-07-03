@extends('sige_app.frontend.template.frontend')

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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Gestion des Laboratoires</h4>
                            <a href="{{ route('labo.laboratoires.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> Nouveau Laboratoire
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Sigle</th>
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
                                            <td>{{ $laboratoire->code_lab }}</td>
                                            <td>{{ $laboratoire->label_labo }}</td>
                                            <td>{{ $laboratoire->sigle }}</td>
                                            <td>{{ $laboratoire->email_labo }}</td>
                                            <td>{{ $laboratoire->tel_labo }}</td>
                                            <td><span class="badge bg-info">{{ $laboratoire->projets->count() }}</span></td>
                                            <td><span class="badge bg-success">{{ $laboratoire->membres->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('labo.laboratoires.show', $laboratoire->code_lab) }}"
                                                        class="btn btn-sm btn-info" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('labo.laboratoires.edit', $laboratoire->code_lab) }}"
                                                        class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('labo.laboratoires.destroy', $laboratoire->code_lab) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete(this.form)" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun laboratoire trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $laboratoires->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

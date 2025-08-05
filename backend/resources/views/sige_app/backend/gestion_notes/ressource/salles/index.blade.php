@extends('sige_app.backend.template.backend')

@section('title', 'Gestion des Salles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Salles</h3>
                    <div class="card-tools">
                        <a href="{{ route('ressources.salles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouvelle Salle
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Nombre de places</th>
                                    <th>État</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salles as $salle)
                                    <tr>
                                        <td>{{ $salle->id }}</td>
                                        <td>{{ $salle->code_salle }}</td>
                                        <td>{{ $salle->nb_place_salle }}</td>
                                        <td>
                                            @if($salle->etat_salle)
                                                <span class="badge badge-success">Disponible</span>
                                            @else
                                                <span class="badge badge-danger">Indisponible</span>
                                            @endif
                                        </td>
                                        <td>{{ $salle->desc_salle ?? 'Aucune description' }}</td>
                                        <td>
                                            <a href="{{ route('ressources.salles.edit', $salle->code_salle) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('ressources.salles.destroy', $salle->code_salle) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune salle trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $salles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

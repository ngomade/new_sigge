@extends('sige_app.backend.template.backend')

@section('title', 'Gestion des Documents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Documents</h3>
                    <div class="card-tools">
                        <a href="{{ route('ressources.documents.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Document
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

                    <!-- Filtres -->
                    <form method="GET" action="{{ route('ressources.documents.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Session</label>
                                <select name="session" class="form-control">
                                    <option value="">Toutes les sessions</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->code_session }}" 
                                            {{ request('session') == $session->code_session ? 'selected' : '' }}>
                                            {{ $session->code_session }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Bureau</label>
                                <select name="bureau" class="form-control">
                                    <option value="">Tous les bureaux</option>
                                    @foreach($bureaux as $bureau)
                                        <option value="{{ $bureau->code_bureau }}" 
                                            {{ request('bureau') == $bureau->code_bureau ? 'selected' : '' }}>
                                            {{ $bureau->code_bureau }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Date début</label>
                                <input type="date" name="date_debut" class="form-control" 
                                       value="{{ request('date_debut') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Date fin</label>
                                <input type="date" name="date_fin" class="form-control" 
                                       value="{{ request('date_fin') }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="{{ route('ressources.documents.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Table des documents -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Libellé</th>
                                    <th>Type</th>
                                    <th>Session</th>
                                    <th>Bureau</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td>{{ $document->id }}</td>
                                        <td>{{ $document->label_doc }}</td>
                                        <td>{{ $document->type_doc }}</td>
                                        <td>{{ $document->sessionExamen->code_session ?? '-' }}</td>
                                        <td>{{ $document->bureau->code_bureau ?? '-' }}</td>
                                        <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('ressources.documents.show', $document->code_doc) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('ressources.documents.edit', $document->code_doc) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('ressources.documents.download', $document->code_doc) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('ressources.documents.destroy', $document->code_doc) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun document trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>
@endsection

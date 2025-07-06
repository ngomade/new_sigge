@extends('laboratoires.public.layout')

@section('title', 'Liste des publications')

@section('content')
<div class="container mt-4">
    <h1>Liste des publications</h1>

    @include('laboratoires.partials.alerts')

    <div class="mb-3">
        <a href="{{ route('labo.publications.create') }}" class="btn btn-primary">Ajouter une publication</a>
    </div>

    @if($publications->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Domaine</th>
                    <th>Créateur</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($publications as $publication)
                <tr>
                    <td>{{ $publication->titre_publi }}</td>
                    <td>{{ ucfirst($publication->type_publi) }}</td>
                    <td>{{ $publication->domaine }}</td>
                    <td>{{ $publication->createur->nom ?? 'N/A' }}</td>
                    <td>{{ $publication->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('labo.publications.show', $publication->code_publi) }}" class="btn btn-sm btn-info">Voir</a>
                        <a href="{{ route('labo.publications.edit', $publication->code_publi) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('labo.publications.destroy', $publication->code_publi) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $publications->links() }}
    @else
        <p>Aucune publication trouvée.</p>
    @endif
</div>
@endsection

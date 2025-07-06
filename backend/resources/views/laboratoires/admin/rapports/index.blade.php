@extends('laboratoires.public.layout')

@section('title', 'Liste des rapports')

@section('content')
<div class="container mt-4">
    <h1>Liste des rapports</h1>

    @include('laboratoires.partials.alerts')

    <div class="mb-3">
        <a href="{{ route('labo.rapports.create') }}" class="btn btn-primary">Ajouter un rapport</a>
    </div>

    @if($rapports->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapports as $rapport)
                <tr>
                    <td>{{ $rapport->titre }}</td>
                    <td>{{ $rapport->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('labo.rapports.show', $rapport->code_rapport) }}" class="btn btn-sm btn-info">Voir</a>
                        <a href="{{ route('labo.rapports.edit', $rapport->code_rapport) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('labo.rapports.destroy', $rapport->code_rapport) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $rapports->links() }}
    @else
        <p>Aucun rapport trouvé.</p>
    @endif
</div>
@endsection

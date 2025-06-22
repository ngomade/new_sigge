@extends("sige_app.backend.template.backend")
@section("js")
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
             alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection

@section('content')
<div class="w-full py-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Requêtes</h1>
            <p class="text-gray-600 mt-1">Administration et suivi des demandes</p>
        </div>
    </div>

    <!-- Messages de notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Inline filter form above the table -->
    <form method="GET" action="{{ route('admin.requetes.index') }}" class="row g-3 mb-3">
        <div class="col-md-3">
            <label for="status" class="form-label">Statut</label>
            <select name="status" id="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="en attente" {{ request('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                <option value="en cours" {{ request('status') == 'en cours' ? 'selected' : '' }}>En cours</option>
                <option value="traitée" {{ request('status') == 'traitée' ? 'selected' : '' }}>Traitée</option>
                <option value="rejetée" {{ request('status') == 'rejetée' ? 'selected' : '' }}>Rejetée</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label">Catégorie</label>
            <select name="category" id="category" class="form-select">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->code_cat }}" {{ request('category') == $category->code_cat ? 'selected' : '' }}>
                        {{ $category->label_cat }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="bureau" class="form-label">Bureau</label>
            <select name="bureau" id="bureau" class="form-select">
                <option value="">Tous les bureaux</option>
                @foreach($bureaux as $bureau)
                    <option value="{{ $bureau->code_bureau }}" {{ request('bureau') == $bureau->code_bureau ? 'selected' : '' }}>
                        {{ $bureau->label_bureau }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- <div class="col-md-3">
            <label for="priorite" class="form-label">Priorité</label>
            <select name="priorite" id="priorite" class="form-select">
                <option value="">Toutes les priorités</option>
                <option value="basse" {{ request('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                <option value="normale" {{ request('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                <option value="urgente" {{ request('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
            </select>
        </div> --}}
        <div class="col-md-3">
            <label for="date_from" class="form-label">Date de début</label>
            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="date_to" class="form-label">Date de fin</label>
            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-secondary">Filtrer</button>
            <a href="{{ route('admin.requetes.index') }}" class="btn btn-light">Réinitialiser</a>
        </div>
    </form>

    <!-- Tableau des requêtes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 text-center">
            <h3 class="text-lg font-medium text-gray-900">
                Liste des Requêtes ({{ $requetes->total() }} résultats)
            </h3>
        </div>

        @if($requetes->count() > 0)
            <div class="grid grid-cols-1 gap-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'code_requete', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="hover:text-gray-700">
                                        Code Requête
                                         @if(request('sort') === 'code_requete')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Utilisateur
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bureau
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="hover:text-gray-700">
                                        Statut
                                        @if(request ('sort') === 'status')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                {{-- <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priorité
                                </th> --}}
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_sousmis', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="hover:text-gray-700">
                                        Date soumise
                                        @if(request('sort ') === 'date_sousmis')
                                            <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requetes as $requete)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $requete->code_requete }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requete->user->nom_user ?? 'N/A' }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requete->category->label_cat ?? 'N/A' }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requete->bureau->label_bureau ?? 'N/A' }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'en attente' => 'bg-yellow-100 text-yellow-800',
                                                'en cours' => 'bg-blue-100 text-blue-800',
                                                'traitée' => 'bg-green-100 text-green-800',
                                                'rejetée' => 'bg-red-100 text-red-800',

                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$requete->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($requete->status) }}
                                        </span>
                                    </td>
                                    {{-- <td class="px-2 py-4 whitespace-nowrap">
                                        @php
                                            $priorityColors = [
                                                'basse' => 'bg-gray-100 text-gray-800',
                                                'normale' => 'bg-blue-100 text-blue-800',
                                                'haute' => 'bg-orange-100 text-orange-800',
                                                'urgente' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$requete->priorite] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($requete->priorite) }}
                                        </span>
                                    </td> --}}
                                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requete->date_sousmis ? $requete->date_sousmis->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900" style="position: relative; z-index: 10;">
                                    <a href="{{ route('admin.requetes.show', $requete->code_requete) }}"
                                  class="btn btn-primary" style="position: relative; z-index: 10; display: inline-block; visibility: visible; opacity: 1;">
                                   Voir détails
                                     </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-4 border-t border-gray-200">
                    {{ $requetes->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>

            {{-- <!-- Pagination -->
            <div class="py-4 border-t border-gray-200">
                {{ $requetes->withQueryString()->links('pagination::bootstrap-5') }}
            </div> --}}
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune requête trouvée</h3>
                <p class="mt-1 text-sm text-gray-500">Aucune requête ne correspond aux critères de recherche.</p>
            </div>
        @endif
    </div>
</div>
@endsection

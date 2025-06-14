@extends("sige_app.backend.template.backend")
@section("js")
    
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Requêtes</h1>
            <p class="text-gray-600 mt-1">Administration et suivi des demandes</p>
        </div>
    </div>

    <!-- Messages de notification -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.requetes.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Filtre Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="en attente" {{ request('status') === 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en cours" {{ request('status') === 'en cours' ? 'selected' : '' }}>En cours</option>
                        <option value="traitée" {{ request('status') === 'traitée' ? 'selected' : '' }}>Traitée</option>
                        <option value="rejetée" {{ request('status') === 'rejetée' ? 'selected' : '' }}>Rejetée</option>
                        <option value="escaladée" {{ request('status') === 'escaladée' ? 'selected' : '' }}>Escaladée</option>
                    </select>
                </div>

                <!-- Filtre Catégorie -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->code_cat }}" {{ request('category') === $category->code_cat ? 'selected' : '' }}>
                                {{ $category->nom_cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre Bureau -->
                @if(!Auth::Personnel()->hasRole('agent'))
                <div>
                    <label for="bureau" class="block text-sm font-medium text-gray-700 mb-2">Bureau</label>
                    <select name="bureau" id="bureau" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous les bureaux</option>
                        @foreach($bureaux as $bureau)
                            <option value="{{ $bureau->code_bureau }}" {{ request('bureau') === $bureau->code_bureau ? 'selected' : '' }}>
                                {{ $bureau->nom_bureau }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Filtre Priorité -->
                <div>
                    <label for="priorite" class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                    <select name="priorite" id="priorite" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Toutes les priorités</option>
                        <option value="basse" {{ request('priorite') === 'basse' ? 'selected' : '' }}>Basse</option>
                        <option value="normale" {{ request('priorite') === 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="haute" {{ request('priorite') === 'haute' ? 'selected' : '' }}>Haute</option>
                        <option value="urgente" {{ request('priorite') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
            </div>

            <!-- Filtres de date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.requetes.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Réinitialiser
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des requêtes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Liste des Requêtes ({{ $requetes->total() }} résultats)
            </h3>
        </div>

        @if($requetes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'code_requete', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="hover:text-gray-700">
                                    Code Requête
                                    @if(request('sort') === 'code_requete')
                                        <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catégorie
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bureau
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="hover:text-gray-700">
                                    Statut
                                    @if(request('sort') === 'status')
                                        <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priorité
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_sousmis', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                   class="hover:text-gray-700">
                                    Date soumise
                                    @if(request('sort') === 'date_sousmis')
                                        <span class="ml-1">{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requetes as $requete)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $requete->code_requete }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $requete->user->nom_user ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $requete->category->nom_cat ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $requete->bureau->nom_bureau ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'en attente' => 'bg-yellow-100 text-yellow-800',
                                            'en cours' => 'bg-blue-100 text-blue-800',
                                            'traitée' => 'bg-green-100 text-green-800',
                                            'rejetée' => 'bg-red-100 text-red-800',
                                            'escaladée' => 'bg-purple-100 text-purple-800'
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$requete->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($requete->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $requete->date_sousmis ? $requete->date_sousmis->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('admin.requetes.show', $requete->code_requete) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Voir détails
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $requetes->withQueryString()->links() }}
            </div>
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
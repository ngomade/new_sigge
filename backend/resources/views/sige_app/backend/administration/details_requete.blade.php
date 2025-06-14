@extends("sige_app.backend.template.backend")
@section("js")
    <!-- Script pour le compteur de caractères -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('text_repone');
    const charCount = document.getElementById('char-count');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const remaining = 180 - this.value.length;
            charCount.textContent = remaining;
            charCount.className = remaining < 20 ? 'text-red-500' : remaining < 50 ? 'text-yellow-500' : 'text-gray-500';
        });
    }
});
</script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- En-tête avec navigation -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.requetes.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $requete->code_requete }}</h1>
                <p class="text-gray-600 mt-1">Détail de la requête</p>
            </div>
        </div>
    </div>

    <!-- Messages de notification -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la requête -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de la requête</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Code de la requête</h3>
                        <p class="text-sm text-gray-900 font-mono">{{ $requete->code_requete }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Statut actuel</h3>
                        @php
                            $statusColors = [
                                'en attente' => 'bg-yellow-100 text-yellow-800',
                                'en cours' => 'bg-blue-100 text-blue-800',
                                'traitée' => 'bg-green-100 text-green-800',
                                'rejetée' => 'bg-red-100 text-red-800',
                                'escaladée' => 'bg-purple-100 text-purple-800'
                            ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$requete->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($requete->status) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Utilisateur</h3>
                        <p class="text-sm text-gray-900">{{ $requete->user->nom_user ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $requete->user->email_user ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Catégorie</h3>
                        <p class="text-sm text-gray-900">{{ $requete->category->nom_cat ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Bureau assigné</h3>
                        <p class="text-sm text-gray-900">{{ $requete->bureau->nom_bureau ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Priorité</h3>
                        @php
                            $priorityColors = [
                                'basse' => 'bg-gray-100 text-gray-800',
                                'normale' => 'bg-blue-100 text-blue-800',
                                'haute' => 'bg-orange-100 text-orange-800',
                                'urgente' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$requete->priorite] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($requete->priorite) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Date de soumission</h3>
                        <p class="text-sm text-gray-900">{{ $requete->date_sousmis ? $requete->date_sousmis->format('d/m/Y à H:i') : 'N/A' }}</p>
                    </div>

                    @if($requete->date_asignation)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Date d'assignation</h3>
                        <p class="text-sm text-gray-900">{{ $requete->date_asignation->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif

                    @if($requete->date_traitement)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Date de traitement</h3>
                        <p class="text-sm text-gray-900">{{ $requete->date_traitement->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Description de la requête -->
                @if($requete->description)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                    <div class="bg-gray-50 rounded-md p-4">
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $requete->description }}</p>
                    </div>
                </div>
                @endif

                <!-- Note interne -->
                @if($requete->note_interne)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Note interne</h3>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">{{ $requete->note_interne }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Fichiers joints -->
            @if($requete->fichiers && $requete->fichiers->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Fichiers joints</h2>
                <div class="space-y-3">
                    @foreach($requete->fichiers as $fichier)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="text-sm text-gray-900">{{ $fichier->nom_fichier }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Télécharger
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Réponses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Réponses et suivi</h2>
                
                @if($requete->reponses && $requete->reponses->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($requete->reponses as $reponse)
                            <div class="border-l-4 border-blue-400 bg-blue-50 p-4 rounded-r-md">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="text-sm font-medium text-blue-900">{{ $reponse->createdBy->nom_pers ?? 'Admin' }}</p>
                                    <span class="text-xs text-blue-600">{{ $reponse->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                                <p class="text-sm text-blue-800">{{ $reponse->text_repone }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm mb-6">Aucune réponse pour le moment.</p>
                @endif

                <!-- Formulaire d'ajout de réponse -->
                <form action="{{ route('admin.requetes.addResponse', $requete->code_requete) }}" method="POST" class="border-t pt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="text_repone" class="block text-sm font-medium text-gray-700 mb-2">Ajouter une réponse</label>
                        <textarea name="text_repone" id="text_repone" rows="3" required maxlength="180"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('text_repone') border-red-500 @enderror"
                                  placeholder="Votre réponse (max 180 caractères)...">{{ old('text_repone') }}</textarea>
                        @error('text_repone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Caractères restants: <span id="char-count">180</span></p>
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Ajouter la réponse
                    </button>
                </form>
            </div>
        </div>

        <!-- Panneau latéral - Actions rapides -->
        <div class="space-y-6">
            <!-- Mise à jour du statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
                
                <form action="{{ route('admin.requetes.updateStatus', $requete->code_requete) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Changer le statut</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="en attente" {{ $requete->status === 'en attente' ? 'selected' : '' }}>En attente</option>
                            <option value="en cours" {{ $requete->status === 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="traitée" {{ $requete->status === 'traitée' ? 'selected' : '' }}>Traitée</option>
                            <option value="rejetée" {{ $requete->status === 'rejetée' ? 'selected' : '' }}>Rejetée</option>
                            <option value="escaladée" {{ $requete->status === 'escaladée' ? 'selected' : '' }}>Escaladée</option>
                        </select>
                    </div>

                    <div>
                        <label for="note_interne" class="block text-sm font-medium text-gray-700 mb-2">Note interne (optionnel)</label>
                        <textarea name="note_interne" id="note_interne" rows="3" maxlength="191"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Note interne pour l'équipe...">{{ old('note_interne') }}</textarea>
                    </div>

                    <div>
                        <label for="nouveau_bureau" class="block text-sm font-medium text-gray-700 mb-2">Réassigner à un bureau (optionnel)</label>
                        <select name="nouveau_bureau" id="nouveau_bureau" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Garder le bureau actuel</option>
                            @foreach(App\Models\Bureau::all() as $bureau)
                                <option value="{{ $bureau->code_bureau }}">{{ $bureau->nom_bureau }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" 
                            class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Mettre à jour
                    </button>
                </form>
            </div>

            <!-- Statistiques rapides -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations rapides</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Temps écoulé:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $requete->date_sousmis ? $requete->date_sousmis->diffForHumans() : 'N/A' }}
                        </span>
                    </div>
                    
                    @if($requete->date_asignation)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Temps de traitement:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $requete->date_asignation->diffForHumans($requete->date_sousmis, true) }}
                        </span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Nombre de réponses:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $requete->reponses ? $requete->reponses->count() : 0 }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Fichiers joints:</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $requete->fichiers ? $requete->fichiers->count() : 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions avancées -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions avancées</h3>
                
                <div class="space-y-3">
                    <button onclick="window.print()" 
                            class="w-full px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimer
                    </button>

                    <a href="mailto:{{ $requete->user->email_user ?? '' }}?subject=Concernant votre requête {{ $requete->code_requete }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contacter l'utilisateur
                    </a>

                    {{-- @if(Auth::Personnel()->hasRole('admin'))
                    <form action="{{ route('admin.requetes.delete', $requete->code_requete) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette requête ? Cette action est irréversible.')" 
                          class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer la requête
                        </button>
                    </form> 
                    @endif --}}
                </div>
            </div>

            <!-- Timeline des événements -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chronologie</h3>
                
                <div class="flow-root">
                    <ul class="-mb-8">
                        <!-- Soumission -->
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Requête soumise par <span class="font-medium text-gray-900">{{ $requete->user->nom_user ?? 'N/A' }}</span></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $requete->date_sousmis ? $requete->date_sousmis->format('d/m H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Assignation -->
                        @if($requete->date_asignation)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Assignée au bureau <span class="font-medium text-gray-900">{{ $requete->bureau->nom_bureau ?? 'N/A' }}</span></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $requete->date_asignation->format('d/m H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif

                        <!-- Réponses -->
                        @foreach($requete->reponses as $reponse)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last || $requete->date_traitement)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Réponse ajoutée</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ Str::limit($reponse->text_repone, 50) }}</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $reponse->created_at->format('d/m H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach

                        <!-- Traitement -->
                        @if($requete->date_traitement)
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $requete->status === 'traitée' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                            @if($requete->status === 'traitée')
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Requête {{ $requete->status }}</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $requete->date_traitement->format('d/m H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@extends("sige_app.backend.template.backend")
@section("js")
    
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('requetes.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $requete->titre_requete }}</h1>
                        <p class="mt-2 text-gray-600">Code: {{ $requete->code_requete }}</p>
                    </div>
                </div>
                
                @if($requete->status === 'en attente')
                    <div class="flex space-x-3">
                        <a href="{{ route('requetes.edit', $requete->code_requete) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                        <form action="{{ route('requetes.destroy', $requete->code_requete) }}" 
                              method="POST" class="inline" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette requête ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Détails de la requête -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Détails de la requête</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-md">{{ $requete->desc_requete }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                                <p class="mt-1 text-gray-900">{{ $requete->category->nom_cat ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bureau</label>
                                <p class="mt-1 text-gray-900">{{ $requete->bureau->nom_bureau ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Statut</label>
                                @php
                                    $statusClasses = [
                                        'en attente' => 'bg-yellow-100 text-yellow-800',
                                        'en cours' => 'bg-blue-100 text-blue-800',
                                        'traité' => 'bg-green-100 text-green-800',
                                        'rejeté' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex mt-1 px-3 py-1 text-sm font-medium rounded-full {{ $statusClasses[$requete->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($requete->status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priorité</label>
                                <span class="inline-flex mt-1 px-3 py-1 text-sm font-medium rounded-full {{ $requete->priorite === 'urgent' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($requete->priorite) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date de soumission</label>
                                <p class="mt-1 text-gray-900">{{ $requete->date_sousmis->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fichiers joints -->
                @if($requete->fichiers->count() > 0)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Fichiers joints</h2>
                        <div class="space-y-3">
                            @foreach($requete->fichiers as $fichier)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $fichier->nom_original }}</p>
                                            <p class="text-xs text-gray-500">{{ number_format($fichier->taille / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ Storage::url($fichier->chemin) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Télécharger
                                        </a>
                                        @if($requete->status === 'en attente')
                                            <form action="{{ route('requetes.fichiers.delete', $fichier->id_fichier) }}" 
                                                  method="POST" class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Réponses -->
                @if($requete->reponses && $requete->reponses->count() > 0)
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Réponses</h2>
                        <div class="space-y-4">
                            @foreach($requete->reponses as $reponse)
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $reponse->user->nom ?? 'Administrateur' }}</p>
                                        <p class="text-xs text-gray-500">{{ $reponse->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                    <p class="text-gray-700">{{ $reponse->contenu }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statut timeline -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Suivi de la requête</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Requête soumise</p>
                                <p class="text-xs text-gray-500">{{ $requete->date_sousmis->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        @if($requete->status === 'en cours' || $requete->status === 'traité' || $requete->status === 'rejeté')
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">En cours de traitement</p>
                                    <p class="text-xs text-gray-500">Prise en charge par le bureau</p>
                                </div>
                            </div>
                        @endif

                        @if($requete->status === 'traité')
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Requête traitée</p>
                                    <p class="text-xs text-gray-500">Votre demande a été résolue</p>
                                </div>
                            </div>
                        @elseif($requete->status === 'rejeté')
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Requête rejetée</p>
                                    <p class="text-xs text-gray-500">Voir les détails dans les réponses</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center opacity-50">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">En attente de traitement</p>
                                    <p class="text-xs text-gray-400">Votre requête sera bientôt prise en charge</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations utilisateur -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Demandeur</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $requete->user->nom_user ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $requete->user->email_user ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Code utilisateur</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $requete->code_user }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('requetes.index') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Retour à la liste
                        </a>
                        
                        @if($requete->status !== 'traité' && $requete->status !== 'rejeté')
                            <button onclick="window.print()" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Imprimer
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print\:hidden {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .bg-gray-50 {
        background: white !important;
    }
}
</style>
@endsection
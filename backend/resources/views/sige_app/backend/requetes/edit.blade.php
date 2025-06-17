
@extends("sige_app.frontend.template.frontend")
@section("js")
<script>
// Gestion de l'upload de fichiers
document.getElementById('fichiers').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    
    let hasError = false;
    
    files.forEach(file => {
        if (file.size > maxSize) {
            alert(`Le fichier "${file.name}" est trop volumineux. Taille maximum : 5MB`);
            hasError = true;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert(`Le fichier "${file.name}" n'est pas dans un format autorisé.`);
            hasError = true;
        }
    });
    
    if (hasError) {
        e.target.value = '';
    }
});

// Compteur de caractères pour les champs texte
document.getElementById('titre_requete').addEventListener('input', function(e) {
    const maxLength = 180;
    const currentLength = e.target.value.length;
    const remaining = maxLength - currentLength;
    
    // Trouve ou crée l'élément de compteur
    let counter = e.target.parentNode.querySelector('.char-counter');
    if (!counter) {
        counter = document.createElement('p');
        counter.className = 'char-counter mt-1 text-xs text-gray-500';
        e.target.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${remaining} caractères restants`;
    counter.className = remaining < 20 ? 'char-counter mt-1 text-xs text-red-500' : 'char-counter mt-1 text-xs text-gray-500';
});

document.getElementById('desc_requete').addEventListener('input', function(e) {
    const maxLength = 180;
    const currentLength = e.target.value.length;
    const remaining = maxLength - currentLength;
    
    // Trouve ou crée l'élément de compteur
    let counter = e.target.parentNode.querySelector('.char-counter');
    if (!counter) {
        counter = document.createElement('p');
        counter.className = 'char-counter mt-1 text-xs text-gray-500';
        e.target.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${remaining} caractères restants`;
    counter.className = remaining < 20 ? 'char-counter mt-1 text-xs text-red-500' : 'char-counter mt-1 text-xs text-gray-500';
});
</script>
    
@endsection
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Modifier la requête</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Référence: <span class="font-semibold text-indigo-600">{{ $requete->code_requete }}</span>
                    </p>
                </div>
                <a href="{{ route('requetes.show', $requete->code_requete) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        <!-- Messages d'alerte -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <form action="{{ route('requetes.update', $requete->code_requete) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informations de la requête</h2>
                    <p class="mt-1 text-sm text-gray-500">Modifiez les détails de votre requête ci-dessous.</p>
                </div>

                <div class="px-6 space-y-6">
                    <!-- Titre de la requête -->
                    <div>
                        <label for="titre_requete" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de la requête <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titre_requete" 
                               id="titre_requete"
                               value="{{ old('titre_requete', $requete->titre_requete) }}"
                               maxlength="180"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('titre_requete') border-red-300 @enderror"
                               placeholder="Entrez le titre de votre requête">
                        @error('titre_requete')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Maximum 180 caractères</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="desc_requete" class="block text-sm font-medium text-gray-700 mb-2">
                            Description détaillée <span class="text-red-500">*</span>
                        </label>
                        <textarea name="desc_requete" 
                                  id="desc_requete"
                                  rows="4"
                                  maxlength="180"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('desc_requete') border-red-300 @enderror"
                                  placeholder="Décrivez votre requête en détail">{{ old('desc_requete', $requete->desc_requete) }}</textarea>
                        @error('desc_requete')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Maximum 180 caractères</p>
                    </div>

                    <!-- Catégorie et Bureau -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Catégorie -->
                        <div>
                            <label for="code_cat" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select name="code_cat" 
                                    id="code_cat"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('code_cat') border-red-300 @enderror">
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->code_cat }}" 
                                            {{ old('code_cat', $requete->code_cat) == $categorie->code_cat ? 'selected' : '' }}>
                                        {{ $categorie->nom_cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_cat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bureau -->
                        <div>
                            <label for="code_bureau" class="block text-sm font-medium text-gray-700 mb-2">
                                Bureau concerné <span class="text-red-500">*</span>
                            </label>
                            <select name="code_bureau" 
                                    id="code_bureau"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('code_bureau') border-red-300 @enderror">
                                <option value="">Sélectionnez un bureau</option>
                                @foreach($bureaux as $bureau)
                                    <option value="{{ $bureau->code_bureau }}" 
                                            {{ old('code_bureau', $requete->code_bureau) == $bureau->code_bureau ? 'selected' : '' }}>
                                        {{ $bureau->nom_bureau }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_bureau')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Priorité -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Priorité</label>
                        <div class="flex space-x-6">
                            <div class="flex items-center">
                                <input id="priorite_standard" 
                                       name="priorite" 
                                       type="radio" 
                                       value="standard"
                                       {{ old('priorite', $requete->priorite) == 'standard' ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <label for="priorite_standard" class="ml-2 text-sm text-gray-700">
                                    Standard
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="priorite_urgent" 
                                       name="priorite" 
                                       type="radio" 
                                       value="urgent"
                                       {{ old('priorite', $requete->priorite) == 'urgent' ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                                <label for="priorite_urgent" class="ml-2 text-sm text-gray-700">
                                    <span class="text-red-600 font-medium">Urgent</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Fichiers existants -->
                    @if($requete->fichiers->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Fichiers actuels</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($requete->fichiers as $fichier)
                                    <div class="relative bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $fichier->nom_original }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ number_format($fichier->taille / 1024, 1) }} KB
                                                </p>
                                            </div>
                                            <div class="flex space-x-2 ml-2">
                                                <a href="{{ route('requetes.download-fichier', $fichier->id_fichier) }}" 
                                                   class="text-indigo-600 hover:text-indigo-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('requetes.delete-fichier', $fichier->id_fichier) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')"
                                                            class="text-red-600 hover:text-red-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Nouveaux fichiers -->
                    <div>
                        <label for="fichiers" class="block text-sm font-medium text-gray-700 mb-2">
                            Ajouter de nouveaux fichiers
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="fichiers" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Télécharger des fichiers</span>
                                        <input id="fichiers" name="fichiers[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC, DOCX jusqu'à 5MB chacun</p>
                            </div>
                        </div>
                        @error('fichiers.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <span class="text-red-500">*</span> Champs obligatoires
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('requetes.show', $requete->code_requete) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mettre à jour la requête
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">À noter</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Vous ne pouvez modifier que les requêtes en attente de traitement</li>
                            <li>Les fichiers ajoutés s'ajoutent aux fichiers existants</li>
                            <li>La modification réinitialisera la date de dernière mise à jour</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
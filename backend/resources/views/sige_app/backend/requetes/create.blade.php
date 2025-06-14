@extends("sige_app.backend.template.backend")
@section("js")
    
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('requetes.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Nouvelle Requête</h1>
                    <p class="mt-2 text-gray-600">Créez une nouvelle demande</p>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <form action="{{ route('requetes.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Titre de la requête -->
                <div>
                    <label for="titre_requete" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de la requête <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="titre_requete" 
                           id="titre_requete" 
                           value="{{ old('titre_requete') }}"
                           maxlength="180"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('titre_requete') border-red-300 @enderror"
                           placeholder="Entrez le titre de votre requête">
                    <p class="mt-1 text-sm text-gray-500">Maximum 180 caractères</p>
                    @error('titre_requete')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="desc_requete" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="desc_requete" 
                              id="desc_requete" 
                              rows="4" 
                              maxlength="180"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('desc_requete') border-red-300 @enderror"
                              placeholder="Décrivez votre demande en détail">{{ old('desc_requete') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Maximum 180 caractères</p>
                    @error('desc_requete')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Catégorie -->
                    <div>
                        <label for="code_cat" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="code_cat" 
                                id="code_cat" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('code_cat') border-red-300 @enderror">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->code_cat }}" {{ old('code_cat') == $category->code_cat ? 'selected' : '' }}>
                                    {{ $category->nom_cat }}
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
                            Bureau <span class="text-red-500">*</span>
                        </label>
                        <select name="code_bureau" 
                                id="code_bureau" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('code_bureau') border-red-300 @enderror">
                            <option value="">Sélectionnez un bureau</option>
                            @foreach($bureaux as $bureau)
                                <option value="{{ $bureau->code_bureau }}" {{ old('code_bureau') == $bureau->code_bureau ? 'selected' : '' }}>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                    <div class="space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="priorite" 
                                   value="standard" 
                                   class="form-radio text-blue-600"
                                   {{ old('priorite', 'standard') == 'standard' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Standard</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="priorite" 
                                   value="urgent" 
                                   class="form-radio text-red-600"
                                   {{ old('priorite') == 'urgent' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Urgent</span>
                        </label>
                    </div>
                    @error('priorite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fichiers joints -->
                <div>
                    <label for="fichiers" class="block text-sm font-medium text-gray-700 mb-2">
                        Fichiers joints
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="fichiers" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Télécharger des fichiers</span>
                                    <input id="fichiers" name="fichiers[]" type="file" class="sr-only" multiple 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG jusqu'à 5MB chacun</p>
                        </div>
                    </div>
                    @error('fichiers.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Zone d'aperçu des fichiers -->
                <div id="file-preview" class="hidden">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Fichiers sélectionnés :</h4>
                    <div id="file-list" class="space-y-2"></div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('requetes.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        Soumettre la requête
                    </button>
                </div>
            </form>
        </div>

        <!-- Information supplémentaire -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>• Vous recevrez un email de confirmation après soumission</p>
                        <p>• Vous pourrez suivre l'évolution de votre requête dans votre tableau de bord</p>
                        <p>• Les requêtes urgentes sont traitées en priorité</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fichiers');
    const filePreview = document.getElementById('file-preview');
    const fileList = document.getElementById('file-list');

    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        if (files.length > 0) {
            filePreview.classList.remove('hidden');
            fileList.innerHTML = '';
            
            files.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-md';
                fileItem.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700">${file.name}</span>
                        <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                    </div>
                `;
                fileList.appendChild(fileItem);
            });
        } else {
            filePreview.classList.add('hidden');
        }
    });
});
</script>
@endsection
@extends('sige_app.backend.template.backend')

@section('title', 'Modifier le Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier le document</h3>
                    <div class="card-tools">
                        <a href="{{ route('ressources.documents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('ressources.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="label_doc">Libellé du document *</label>
                                    <input type="text" class="form-control @error('label_doc') is-invalid @enderror" 
                                           id="label_doc" name="label_doc" value="{{ old('label_doc', $document->label_doc) }}" required>
                                    @error('label_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_doc">Type de document *</label>
                                    <input type="text" class="form-control @error('type_doc') is-invalid @enderror" 
                                           id="type_doc" name="type_doc" value="{{ old('type_doc', $document->type_doc) }}" required>
                                    @error('type_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code_session">Session</label>
                                    <select class="form-control @error('code_session') is-invalid @enderror" 
                                            id="code_session" name="code_session">
                                        <option value="">Sélectionner une session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->code_session }}" 
                                                {{ old('code_session', $document->code_session) == $session->code_session ? 'selected' : '' }}>
                                                {{ $session->code_session }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('code_session')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code_bureau">Bureau</label>
                                    <select class="form-control @error('code_bureau') is-invalid @enderror" 
                                            id="code_bureau" name="code_bureau">
                                        <option value="">Sélectionner un bureau</option>
                                        @foreach($bureaux as $bureau)
                                            <option value="{{ $bureau->code_bureau }}" 
                                                {{ old('code_bureau', $document->code_bureau) == $bureau->code_bureau ? 'selected' : '' }}>
                                                {{ $bureau->code_bureau }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('code_bureau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description_doc">Description</label>
                                    <textarea class="form-control @error('description_doc') is-invalid @enderror" 
                                              id="description_doc" name="description_doc" 
                                              rows="3">{{ old('description_doc', $document->description_doc) }}</textarea>
                                    @error('description_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fichier">Nouveau fichier (optionnel)</label>
                                    <input type="file" class="form-control @error('fichier') is-invalid @enderror" 
                                           id="fichier" name="fichier">
                                    <small class="form-text text-muted">
                                        Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG. Taille max: 10MB
                                        <br>
                                        <strong>Fichier actuel :</strong> {{ $document->nom_fichier }}
                                    </small>
                                    @error('fichier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fichier actuel :</label>
                                    <p class="form-control-static">
                                        <i class="fas fa-file"></i> {{ $document->nom_fichier }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                        <a href="{{ route('ressources.documents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('fichier').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        
        if (file) {
            const fileType = file.type;
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            let previewHTML = `
                <div class="border p-3 rounded">
                    <h6>Nouveau fichier: ${fileName}</h6>
                    <p class="mb-1">Type: ${fileType}</p>
                    <p class="mb-1">Taille: ${fileSize} MB</p>
            `;
            
            if (fileType.startsWith('image/')) {
                previewHTML += `<img src="${URL.createObjectURL(file)}" class="img-fluid" style="max-height: 200px;">`;
            } else {
                previewHTML += `<p class="text-muted">Aperçu non disponible pour ce type de fichier</p>`;
            }
            
            previewHTML += `</div>`;
            previewContainer.innerHTML = previewHTML;
        }
    });
</script>
@endsection

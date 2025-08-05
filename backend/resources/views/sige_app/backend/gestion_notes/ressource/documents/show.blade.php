@extends('sige_app.backend.template.backend')

@section('title', 'Détails du Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du document</h3>
                    <div class="card-tools">
                        <a href="{{ route('ressources.documents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <a href="{{ route('ressources.documents.edit', $document->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('ressources.documents.download', $document->id) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>ID :</strong></label>
                                <p class="form-control-static">{{ $document->id }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Libellé :</strong></label>
                                <p class="form-control-static">{{ $document->label_doc }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Type :</strong></label>
                                <p class="form-control-static">{{ $document->type_doc }}</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Nom du fichier :</strong></label>
                                <p class="form-control-static">
                                    <i class="fas fa-file"></i> {{ $document->nom_fichier }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Session :</strong></label>
                                <p class="form-control-static">
                                    {{ $document->sessionExamen->code_session ?? 'Non spécifié' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Bureau :</strong></label>
                                <p class="form-control-static">
                                    {{ $document->bureau->code_bureau ?? 'Non spécifié' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Description :</strong></label>
                                <p class="form-control-static">
                                    {{ $document->description_doc ?? 'Aucune description' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Date de création :</strong></label>
                                <p class="form-control-static">
                                    {{ $document->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Date de modification :</strong></label>
                                <p class="form-control-static">
                                    {{ $document->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><strong>Aperçu du fichier :</strong></label>
                                <div class="border p-3 rounded text-center">
                                    <i class="fas fa-file fa-3x text-primary mb-2"></i>
                                    <p>{{ $document->nom_fichier }}</p>
                                    <a href="{{ route('ressources.documents.download', $document->id) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-download"></i> Télécharger le fichier
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <a href="{{ route('ressources.documents.edit', $document->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('ressources.documents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

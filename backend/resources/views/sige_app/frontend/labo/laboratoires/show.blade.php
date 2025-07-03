@extends('sige_app.frontend.template.frontend')

@section('js')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>{{ $laboratoire->label_labo }} ({{ $laboratoire->code_lab }})</h4>
                        <p class="mb-0">{{ $laboratoire->sigle }}</p>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Description</h5>
                                <div style="text-align: justify; line-height: 30px;">
                                    {!! $laboratoire->desc_labo !!}
                                </div>

                                @if ($laboratoire->axes_recherche)
                                    <h5 class="mt-4">Axes de recherche</h5>
                                    <div style="text-align: justify;">
                                        {!! $laboratoire->axes_recherche !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                @if ($laboratoire->logo_labo)
                                    <div class="text-center mb-3">
                                        <img src="{{ Storage::url($laboratoire->logo_labo) }}" alt="Logo"
                                            class="img-fluid" style="max-height: 200px;">
                                    </div>
                                @endif

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Informations de contact</h6>
                                        <hr>
                                        <p><i class="fas fa-envelope"></i> {{ $laboratoire->email_labo }}</p>
                                        <p><i class="fas fa-phone"></i> {{ $laboratoire->tel_labo }}</p>
                                        <p><i class="fas fa-map-marker-alt"></i> {{ $laboratoire->adresse_labo }}</p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('labo.laboratoires.edit', $laboratoire->code_lab) }}"
                                        class="btn btn-warning w-100 mb-2">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="{{ route('labo.laboratoires.index') }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projets de recherche -->
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Projets de recherche ({{ $laboratoire->projets->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <div id="accordionProjets">
                            @forelse($laboratoire->projets as $projet)
                                <div class="card mb-2">
                                    <div class="card-header">
                                        <a data-bs-toggle="collapse" href="#projet{{ $loop->index }}"
                                            title="Cliquez pour voir la description">
                                            <h6 class="mb-0">
                                                <span
                                                    class="badge bg-{{ $projet->statut_projet == 'en_cours' ? 'success' : ($projet->statut_projet == 'termine' ? 'secondary' : 'warning') }}">
                                                    {{ ucfirst($projet->statut_projet) }}
                                                </span>
                                                {{ $projet->theme_projet }}
                                            </h6>
                                        </a>
                                    </div>
                                    <div id="projet{{ $loop->index }}" class="collapse"
                                        data-bs-parent="#accordionProjets">
                                        <div class="card-body">
                                            <div style="text-align: justify;">
                                                {!! $projet->description_projet !!}
                                            </div>
                                            <hr>
                                            <small>
                                                <i class="fas fa-calendar"></i>
                                                Du {{ \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') }}
                                                @if ($projet->fin_projet)
                                                    au {{ \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">Aucun projet de recherche pour ce laboratoire.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Membres -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Membres du laboratoire ({{ $laboratoire->membres->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Date d'entrée</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laboratoire->membres as $membre)
                                        <tr>
                                            <td>{{ $membre->id_pers_lab }}</td>
                                            <td>{{ ucfirst($membre->type_pers_lab) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($membre->date_entree)->format('d/m/Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $membre->statut == 'actif' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($membre->statut) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun membre dans ce laboratoire.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Équipements -->
                <div class="card">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">Équipements ({{ $laboratoire->equipements->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($laboratoire->equipements as $equipement)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{ $equipement->nom_equip }}</h6>
                                            <p class="mb-1"><small>Ref: {{ $equipement->ref_equip }}</small></p>
                                            <p class="mb-0">
                                                <span
                                                    class="badge bg-{{ $equipement->etat == 'disponible' ? 'success' : ($equipement->etat == 'en_maintenance' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $equipement->etat)) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">Aucun équipement enregistré pour ce laboratoire.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

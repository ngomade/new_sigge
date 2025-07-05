@extends('sige_app.backend.template.backend')

@section('js')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bx bx-building-house me-2"></i>
                            {{ $laboratoire->label_labo }}
                            <small class="text-muted">({{ $laboratoire->code_lab }})</small>
                        </h4>
                        <div>
                            <a href="{{ route('labo.laboratoires.edit', $laboratoire->code_lab) }}"
                                class="btn btn-warning btn-sm">
                                <i class="bx bx-edit"></i> Modifier
                            </a>
                            <a href="{{ route('labo.laboratoires.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> Retour
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5><i class="bx bx-file-text me-2"></i>Description</h5>
                                <div class="text-justify" style="line-height: 1.6;">
                                    {!! $laboratoire->desc_labo !!}
                                </div>

                                @if ($laboratoire->axes_recherche)
                                    <h5 class="mt-4"><i class="bx bx-target-lock me-2"></i>Axes de recherche</h5>
                                    <div class="text-justify">
                                        {!! $laboratoire->axes_recherche !!}
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                @if ($laboratoire->logo_labo)
                                    <div class="text-center mb-3">
                                        <img src="{{ Storage::url($laboratoire->logo_labo) }}" alt="Logo"
                                            class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                @endif

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="bx bx-info-circle me-2"></i>Informations de contact</h6>
                                        <hr>
                                        <p><i class="bx bx-envelope text-muted me-2"></i> {{ $laboratoire->email_labo }}</p>
                                        <p><i class="bx bx-phone text-muted me-2"></i> {{ $laboratoire->tel_labo }}</p>
                                        <p><i class="bx bx-map text-muted me-2"></i> {{ $laboratoire->adresse_labo }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projets de recherche -->
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bx bx-folder me-2"></i>
                            Projets de recherche ({{ $laboratoire->projets ? $laboratoire->projets->count() : 0 }})
                        </h4>
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
                                            <div class="text-justify">
                                                {!! $projet->description_projet !!}
                                            </div>
                                            <hr>
                                            <small>
                                                <i class="bx bx-calendar text-muted me-1"></i>
                                                Du {{ \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') }}
                                                @if ($projet->fin_projet)
                                                    au {{ \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bx bx-info-circle fs-1"></i>
                                    <p class="mt-2">Aucun projet de recherche pour ce laboratoire.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Membres du laboratoire -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="bx bx-group me-2"></i>
                            Membres du laboratoire ({{ $laboratoire->membres ? $laboratoire->membres->count() : 0 }})
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Rôle</th>
                                        <th>Date d'affectation</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($laboratoire->membres as $membre)
                                        <tr>
                                            <td><strong>{{ $membre->id_pers_lab }}</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($membre->type_pers_lab) }}</span>
                                            </td>
                                            <td>
                                                @if($membre->pivot->roleLabo)
                                                    <span class="badge bg-{{ $membre->pivot->roleLabo->lib_rl == 'admin' ? 'danger' : 'primary' }}">
                                                        {{ ucfirst($membre->pivot->roleLabo->lib_rl) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Aucun rôle</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="bx bx-calendar text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($membre->pivot->date_affectation)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $membre->pivot->statut == 'actif' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($membre->pivot->statut) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bx bx-info-circle fs-1"></i>
                                                <p class="mt-2">Aucun membre dans ce laboratoire.</p>
                                            </td>
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
                        <h4 class="mb-0">
                            <i class="bx bx-cog me-2"></i>
                            Équipements ({{ $laboratoire->equipements ? $laboratoire->equipements->count() : 0 }})
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($laboratoire->equipements as $equipement)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6><i class="bx bx-cog me-2"></i>{{ $equipement->nom_equip }}</h6>
                                            <p class="mb-1"><small class="text-muted">Ref: {{ $equipement->ref_equip }}</small></p>
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
                                <div class="col-12">
                                    <div class="text-center text-muted py-4">
                                        <i class="bx bx-info-circle fs-1"></i>
                                        <p class="mt-2">Aucun équipement enregistré pour ce laboratoire.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

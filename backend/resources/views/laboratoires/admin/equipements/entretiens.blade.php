@extends('laboratoires.public.layout')

@section('content')
@php
    $userId = session('user_id');
    $userType = session('user_type');
    $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', session('laboratoire_code'))
        ->where('statut', 'actif')
        ->where(function ($q) use ($userId, $userType) {
            if ($userType === 'externe') {
                $q->where('id_user_externe', $userId);
            } else {
                $q->where('id_pers_lab', $userId);
            }
        })
        ->with('roleLabo')
        ->first();
    $userRole = $affectation && $affectation->roleLabo ? strtolower($affectation->roleLabo->lib_rl) : null;
@endphp
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class='bx bx-wrench'></i> Gestion des Entretiens - {{ $equipement->nom_equip }}</h2>
        <a href="{{ route('laboratoires.admin.equipements.show', [$laboratoire->code_lab, $equipement->code_equip]) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back"></i> Retour aux détails
        </a>
    </div>
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
    <div class="row">
        <!-- Informations de l'équipement -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    @if($equipement->image_path)
                        <img src="{{ asset('storage/' . $equipement->image_path) }}" alt="Image de l'équipement" class="img-fluid rounded shadow" style="max-height: 60px; max-width: 80px;">
                    @else
                        <i class='bx bx-cog' style="font-size: 2rem; color: var(--primary-color);"></i>
                    @endif
                    <div>
                        <h5 class="card-title mb-1">{{ $equipement->nom_equip }}</h5>
                        <p class="text-muted mb-0">Code: {{ $equipement->code_equip }}</p>
                        <div class="mb-1">
                            <span class="badge bg-{{ $equipement->etat_badge }} fs-6">
                                {{ $equipement->etat_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
            <!-- Formulaire de création/modification d'entretien -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Programmer un entretien</h5>
                    @include('laboratoires.admin.equipements.partials.form_entretien')
                </div>
            </div>
            @endif
        </div>
        <!-- Liste des entretiens -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historique des entretiens</h5>
                    @if($entretiens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Responsable</th>
                                    <th>Statut</th>
                                    <th>Coût</th>
                                    @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                                    <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entretiens as $entretien)
                                <tr>
                                    <td>{{ $entretien->debut_formatted }}</td>
                                    <td><span class="badge bg-{{ $entretien->type_badge }}">{{ $entretien->type_label }}</span></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $entretien->responsable['nom'] }}</strong>
                                            <small class="text-muted">{{ $entretien->responsable['email'] }}</small>
                                            @if($entretien->responsable['telephone'] !== 'Non défini')
                                                <small class="text-muted">{{ $entretien->responsable['telephone'] }}</small>
                                        @endif
                                        </div>
                                    </td>
                                    <td><span class="badge bg-{{ $entretien->statut_badge }}">{{ $entretien->statut_entretien }}</span></td>
                                    <td>{{ $entretien->cout_formatted }}</td>
                                    @if($userRole === 'admin' || $userRole === 'chef_projet' || $userRole === 'technicien')
                                    <td>
                                        @if($entretien->statut_entretien === 'En cours')
                                        <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$laboratoire->code_lab, $equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="statut_entretien" value="Terminé">
                                            <input type="hidden" name="keep_dates" value="true">
                                            <button type="submit" class="btn btn-success btn-sm" title="Terminer"><i class="bx bx-check"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$laboratoire->code_lab, $equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="statut_entretien" value="En pause">
                                            <input type="hidden" name="keep_dates" value="true">
                                            <button type="submit" class="btn btn-warning btn-sm" title="Mettre en pause"><i class="bx bx-pause"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$laboratoire->code_lab, $equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="statut_entretien" value="Annulé">
                                            <input type="hidden" name="keep_dates" value="true">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Annuler"><i class="bx bx-x"></i></button>
                                        </form>
                                        @elseif($entretien->statut_entretien === 'En pause')
                                        <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$laboratoire->code_lab, $equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="statut_entretien" value="En cours">
                                            <input type="hidden" name="keep_dates" value="true">
                                            <button type="submit" class="btn btn-info btn-sm" title="Reprendre"><i class="bx bx-play"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('laboratoires.admin.equipements.entretien.update', [$laboratoire->code_lab, $equipement->code_equip, $entretien->id]) }}" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="statut_entretien" value="Annulé">
                                            <input type="hidden" name="keep_dates" value="true">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Annuler"><i class="bx bx-x"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                                @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bx bx-wrench font-size-48 text-muted"></i>
                        <p class="text-muted mt-2">Aucun entretien enregistré</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('laboratoires.public.layout')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class='bx bx-folder'></i> Gestion des projets - {{ $laboratoire->label_labo }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class='bx bx-info-circle'></i>
                        La gestion des projets sera implémentée prochainement.
                    </div>

                    <a href="{{ route('laboratoires.admin.dashboard', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                        <i class='bx bx-arrow-back'></i> Retour au dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

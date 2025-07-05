@extends('sige_app.backend.template.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Modifier le rôle : {{ $role->lib_rl }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('labo.roles.update', $role->id_rl) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="lib_rl" class="form-label">Libellé du rôle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lib_rl') is-invalid @enderror"
                                   id="lib_rl" name="lib_rl" value="{{ old('lib_rl', $role->lib_rl) }}"
                                   placeholder="Ex: admin, chercheur, technicien..." required>
                            @error('lib_rl')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Le libellé doit être unique et descriptif du rôle.
                            </div>
                        </div>



                        <div class="d-flex justify-content-between">
                            <a href="{{ route('labo.roles.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

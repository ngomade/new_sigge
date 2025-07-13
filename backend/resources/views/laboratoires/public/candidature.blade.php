@extends('laboratoires.public.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class='bx bx-user-plus' style="font-size: 3rem; color: var(--primary-color);"></i>
                        <h3 class="mt-3">Candidature</h3>
                        <p class="text-muted">Rejoindre {{ $laboratoire->label_labo }}</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @php
                        $userId = session('user_id');
                        $userType = session('user_type');
                        $affectation = null;
                        if($userId) {
                            $affectation = \App\Models\laboratoires\LaboratoirePersLab::where('code_lab', $laboratoire->code_lab)
                                ->where('statut', 'actif')
                                ->where(function ($q) use ($userId, $userType) {
                                    if ($userType === 'externe') {
                                        $q->where('id_user_externe', $userId);
                                    } else {
                                        $q->where('id_pers_lab', $userId);
                                    }
                                })
                                ->first();
                        }
                    @endphp

                    @if($affectation)
                        <div class="alert alert-warning text-center my-5">
                            <i class='bx bx-lock'></i> Vous êtes déjà membre de ce laboratoire. Vous ne pouvez pas déposer une nouvelle candidature.
                        </div>
                    @else
                        <form method="POST" action="{{ route('laboratoires.candidature.store', $laboratoire->code_lab) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom_user_ext" class="form-label">Nom *</label>
                                        <input type="text" class="form-control @error('nom_user_ext') is-invalid @enderror"
                                               id="nom_user_ext" name="nom_user_ext" value="{{ old('nom_user_ext') }}" required>
                                        @error('nom_user_ext')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prenom_user_ext" class="form-label">Prénom *</label>
                                        <input type="text" class="form-control @error('prenom_user_ext') is-invalid @enderror"
                                               id="prenom_user_ext" name="prenom_user_ext" value="{{ old('prenom_user_ext') }}" required>
                                        @error('prenom_user_ext')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_user_ext" class="form-label">Adresse email *</label>
                                        <input type="email" class="form-control @error('email_user_ext') is-invalid @enderror"
                                               id="email_user_ext" name="email_user_ext" value="{{ old('email_user_ext') }}" required>
                                        @error('email_user_ext')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tel_user_ext" class="form-label">Téléphone *</label>
                                        <input type="tel" class="form-control @error('tel_user_ext') is-invalid @enderror"
                                               id="tel_user_ext" name="tel_user_ext" value="{{ old('tel_user_ext') }}" required>
                                        @error('tel_user_ext')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="motivation_path" class="form-label">Lettre de motivation *</label>
                                <input type="file" class="form-control @error('motivation_path') is-invalid @enderror"
                                       id="motivation_path" name="motivation_path" accept=".pdf,.doc,.docx" required>
                                <div class="form-text">Formats acceptés : PDF, DOC, DOCX (max 2MB)</div>
                                @error('motivation_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="cv" class="form-label">CV (optionnel)</label>
                                <input type="file" class="form-control @error('cv') is-invalid @enderror"
                                       id="cv" name="cv" accept=".pdf,.doc,.docx">
                                <div class="form-text">Formats acceptés : PDF, DOC, DOCX (max 2MB)</div>
                                @error('cv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class='bx bx-info-circle'></i>
                                <strong>Information :</strong> Votre candidature sera examinée par l'administrateur du laboratoire.
                                Vous recevrez une notification par email une fois votre demande traitée.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class='bx bx-send'></i> Soumettre ma candidature
                                </button>
                                <a href="{{ route('laboratoires.show', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                                    <i class='bx bx-arrow-back'></i> Retour au laboratoire
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

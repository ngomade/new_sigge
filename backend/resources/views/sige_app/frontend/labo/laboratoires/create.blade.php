@extends('sige_app.backend.template.backend')

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#desc_labo'))
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#axes_recherche'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 m-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Créer un nouveau laboratoire</h4>
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

                        <form action="{{ route('labo.laboratoires.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code_lab" class="form-label">Code du laboratoire <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code_lab') is-invalid @enderror"
                                        id="code_lab" name="code_lab" value="{{ old('code_lab') }}" required>
                                    @error('code_lab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sigle" class="form-label">Sigle <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sigle') is-invalid @enderror"
                                        id="sigle" name="sigle" value="{{ old('sigle') }}" required>
                                    @error('sigle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="label_labo" class="form-label">Nom du laboratoire <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('label_labo') is-invalid @enderror"
                                    id="label_labo" name="label_labo" value="{{ old('label_labo') }}" required>
                                @error('label_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="desc_labo" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('desc_labo') is-invalid @enderror" id="desc_labo" name="desc_labo" rows="5"
                                    required>{{ old('desc_labo') }}</textarea>
                                @error('desc_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="axes_recherche" class="form-label">Axes de recherche</label>
                                <textarea class="form-control @error('axes_recherche') is-invalid @enderror" id="axes_recherche" name="axes_recherche"
                                    rows="4">{{ old('axes_recherche') }}</textarea>
                                @error('axes_recherche')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email_labo" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email_labo') is-invalid @enderror"
                                        id="email_labo" name="email_labo" value="{{ old('email_labo') }}" required>
                                    @error('email_labo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tel_labo" class="form-label">Téléphone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tel_labo') is-invalid @enderror"
                                        id="tel_labo" name="tel_labo" value="{{ old('tel_labo') }}" required>
                                    @error('tel_labo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse_labo" class="form-label">Adresse <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('adresse_labo') is-invalid @enderror" id="adresse_labo" name="adresse_labo"
                                    rows="2" required>{{ old('adresse_labo') }}</textarea>
                                @error('adresse_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="logo_labo" class="form-label">Logo du laboratoire</label>
                                <input type="file" class="form-control @error('logo_labo') is-invalid @enderror"
                                    id="logo_labo" name="logo_labo" accept="image/*">
                                @error('logo_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('labo.laboratoires.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

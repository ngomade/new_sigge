@extends('sige_app.frontend.template.frontend')

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
                    <div class="card-header bg-warning">
                        <h4>Modifier le laboratoire : {{ $laboratoire->label_labo }}</h4>
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

                        <form action="{{ route('labo.laboratoires.update', $laboratoire->code_lab) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code_lab" class="form-label">Code du laboratoire</label>
                                    <input type="text" class="form-control" id="code_lab"
                                        value="{{ $laboratoire->code_lab }}" disabled>
                                    <small class="text-muted">Le code ne peut pas être modifié</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sigle" class="form-label">Sigle <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sigle') is-invalid @enderror"
                                        id="sigle" name="sigle" value="{{ old('sigle', $laboratoire->sigle) }}"
                                        required>
                                    @error('sigle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="label_labo" class="form-label">Nom du laboratoire <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('label_labo') is-invalid @enderror"
                                    id="label_labo" name="label_labo"
                                    value="{{ old('label_labo', $laboratoire->label_labo) }}" required>
                                @error('label_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="desc_labo" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('desc_labo') is-invalid @enderror" id="desc_labo" name="desc_labo" rows="5"
                                    required>{{ old('desc_labo', $laboratoire->desc_labo) }}</textarea>
                                @error('desc_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="axes_recherche" class="form-label">Axes de recherche</label>
                                <textarea class="form-control @error('axes_recherche') is-invalid @enderror" id="axes_recherche" name="axes_recherche"
                                    rows="4">{{ old('axes_recherche', $laboratoire->axes_recherche) }}</textarea>
                                @error('axes_recherche')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email_labo" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email_labo') is-invalid @enderror"
                                        id="email_labo" name="email_labo"
                                        value="{{ old('email_labo', $laboratoire->email_labo) }}" required>
                                    @error('email_labo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tel_labo" class="form-label">Téléphone <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tel_labo') is-invalid @enderror"
                                        id="tel_labo" name="tel_labo"
                                        value="{{ old('tel_labo', $laboratoire->tel_labo) }}" required>
                                    @error('tel_labo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse_labo" class="form-label">Adresse <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('adresse_labo') is-invalid @enderror" id="adresse_labo" name="adresse_labo"
                                    rows="2" required>{{ old('adresse_labo', $laboratoire->adresse_labo) }}</textarea>
                                @error('adresse_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="logo_labo" class="form-label">Logo du laboratoire</label>
                                @if ($laboratoire->logo_labo)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($laboratoire->logo_labo) }}" alt="Logo actuel"
                                            style="max-height: 100px;">
                                        <small class="d-block text-muted">Logo actuel</small>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('logo_labo') is-invalid @enderror"
                                    id="logo_labo" name="logo_labo" accept="image/*">
                                @error('logo_labo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Laissez vide pour conserver le logo actuel</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('labo.laboratoires.show', $laboratoire->code_lab) }}"
                                    class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

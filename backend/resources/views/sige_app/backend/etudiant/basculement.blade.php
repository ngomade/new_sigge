@php use Carbon\Carbon; @endphp
@extends("sige_app.backend.template.backend")
@section('js')
    <script>
        document.getElementById('select-all').addEventListener('change', function (e) {

            const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
        });
    </script>
@endsection
@section("content")
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header row">
                        <h4 class="card-title col-2">Basculement</h4>
                        <div class=" col-10">
                            <form action="/search_user/basculement" method="post">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-2">
                                        <select name="code_filiere" id="code_filiere" class="form-select">
                                            <option value="GLTCO">GLTCO</option>
                                            <option value="TTL">TTL</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select name="code_ecole" id="code_ecole" class="form-select">
                                            <option value="ESTLC">ESTLC</option>
                                            <option value="ISLAPE">ISLAPE</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select name="code_annee" id="code_annee" class="form-select">
                                            @foreach (\App\Models\Anneescolaire::orderBy("debut_annee", "desc")->get() as $anneescolaire)
                                                <option
                                                        value="{{$anneescolaire->code_annee}}"> {{Carbon::parse($anneescolaire->debut_annee)->format('Y')}}
                                                    - {{Carbon::parse($anneescolaire->fin_annee)->format('Y')}}  </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select name="niveau" id="niveau" class="form-select">
                                            @foreach (\App\Models\notes\Niveau::all() as $niveau)
                                                <option
                                                        value="{{$niveau->code_niveau}}"> {{$niveau->label_niveau}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-outline-primary"><i
                                                    class="ri-search-line"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="/basculement_save" method="post">
                            {{ csrf_field() }}
                            @isset($users)
                                @if ($users->count() >=1)
                                    <table class="table table-bordered table-hover datatable" id="filterTable">
                                        <thead>
                                        <tr style="text-transform: uppercase;">
                                            <th>N°</th>
                                            <th>Matricule</th>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>Nom</th>
                                            <th>Prenom</th>
                                            <th>Filiere</th>
                                            <th>Né le</th>
                                            <th>à</th>
                                            <th>Sexe</th>
                                            <th>Téléphone</th>
                                            <th>Région</th>
                                            <th>Departement</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{-- <input type="hidden" name="niveau" value="{{$users->first()->code_niveau+1}}"> --}}
                                        @foreach ($users as $user)
                                            <tr style="text-transform: uppercase;">
                                                <td>{{ $loop->index +1 }}</td>
                                                <td>{{ $user->code_user }}</td>
                                                <td><input type="checkbox" name="selected_users[]"
                                                           value="{{ $user->code_user }}"></td>
                                                <td>{{ $user->nom_user }}</td>
                                                <td>{{ $user->prenom_user }}</td>
                                                <td>{{ $user->code_filiere }}</td>
                                                <td>{{ $user->date_naissance_user->format("d/m/Y") }}</td>
                                                <td>{{ $user->lieu_naissance_user }}</td>
                                                <td>{{ \Str::substr($user->sexe_user , 0, 1) }}</td>
                                                <td>{{ $user->first_phone_user }}</td>
                                                <td>{{ $user->region_origine_user }}</td>
                                                <td>{{ $user->depart_origine_user }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="code_filiere" value="{{$users->first()->code_filiere}}">
                                    <div class="row mt-2">
                                        <div class="col-4">
                                            <div class="row">
                                                <label for="niveau_c" class="col-sm-4 col-form-label">Niveau Cible<span
                                                            class="text-danger">*</span></label>
                                                <div class="col-5">
                                                    <select name="niveau_c" id="niveau_c" class="form-select">
                                                        @foreach (\App\Models\notes\Niveau::orderBy("code_niveau", "desc")->get() as $niveau)
                                                            <option
                                                                    value="{{$niveau->code_niveau}}"> {{$niveau->label_niveau}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="row">
                                                <label for="option_b" class="col-sm-6 col-form-label">Option de
                                                    Basculement<span class="text-danger">*</span></label>
                                                <div class="col-6">
                                                    <select name="option_b" id="option_b" class="form-select">
                                                        <option value="misajour">Mis à jour Inscription</option>
                                                        <option value="basculement">Basculement</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">Basculer</button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-primary w-50 h4 m-auto"> Aucun étudiant ne correspond à vos
                                        critères de recherche
                                    </div>
                                @endif
                            @endisset
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

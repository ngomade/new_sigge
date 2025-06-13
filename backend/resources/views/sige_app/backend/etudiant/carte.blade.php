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
                        <h4 class="card-title col-3">Liste des étudiants</h4>
                        <div class=" col-9">
                            <form action="/search_user/carte" method="post">
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
                                                {{
                \Carbon\Carbon::parse($anneescolaire->debut_annee)->format('Y')
            }}-{{
            \Carbon\Carbon::parse($anneescolaire->fin_annee)->format('Y')
        }}
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select name="niveau" id="niveau" class="form-select">
                                            @foreach (\App\Models\Niveau::all() as $niveau)
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
                        <form action="/carte" method="post">
                            @csrf
                            @isset($users)
                                @if ($users->count() >=1)
                                    <input type="hidden" name="code_filiere" value="{{$users->first()->code_filiere}}">
                                    <input type="hidden" name="niveau" value="{{$users->first()->code_niveau}}">
                                    <table class="datatable table table-bordered table-hover" id="filterTable">
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
                                        @foreach ($users as $user)
                                            <tr style="text-transform: uppercase;">
                                                <td>{{ $loop->index +1 }}</td>
                                                <td>{{ $user->code_user }}</td>
                                                <td><input type="checkbox" name="selected_users[]" checked
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
                                    <div class="mt-4 text-center">
                                        <button type="submit" class="btn btn-outline-danger">Imprimer &nbsp;<i
                                                class='bx bxs-file-pdf'></i></button>
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

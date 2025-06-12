@extends("sige_app.backend.template.backend")
@section('js')
<script>
    document.getElementById('select-all').addEventListener('change', function(e) {

        const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
    });
</script>
@endsection
@section("content")
<div class="container">
    <div class="card">
        <div class="card-header row">
            <h4 class="card-title col-2">Changement de site</h4>
            <div class="col-8">
                <form action="/search_etudiant_site" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-3">
                            <select name="code_filiere" id="code_filiere" class="form-select">
                                <option value="GLTCO">GLTCO</option>
                                <option value="TTL">TTL</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <select name="code_ecole" id="code_ecole" class="form-select">
                                <option value="ESTLC">ESTLC</option>
                                <option value="ISLAPE">ISLAPE</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <select name="code_annee" id="code_annee" class="form-select">
                                @foreach (\App\Models\Anneescolaire::all() as $anneescolaire)
                                    <option value="{{$anneescolaire->code_annee}}"> {{$anneescolaire->debut_annee->year}}- {{$anneescolaire->fin_annee->year}}  </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <select name="code_niveau" id="code_niveau" class="form-select">
                                @foreach (\App\Models\Niveau::all() as $niveau)
                                    <option value="{{$niveau->code_niveau}}"> {{$niveau->label_niveau}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            @isset($users)
                <div class="col-2">
                    <form action="/find_candidats_site" method="post">
                        <input type="hidden" name="filiere" value="{{$filiere}}">
                        <input type="hidden" name="ecole" value="{{$ecole}}">
                        <input type="hidden" name="annee" value="{{$annee}}">
                        <input type="hidden" name="niveau" value="{{$niveau}}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-8">
                                <input type="text" placeholder="rechercher par nom, prenons ou tel" name="keyword", id="keyword" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            @endisset
        </div>
        <div class="card-body">
            <form action="/changement_site_save" method="post">
                @csrf
            @isset($users)
                @if ($users->count()> 0)
                    <table class="table table-bordered table-hover datatable" id="filterTable">
                        <thead>
                            <tr style="text-transform: uppercase;">
                                <th>N°</th>
                                <th>Matricule</th>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Filiere</th>
                                <th>Ecole</th>
                                <th>Né le</th>
                                <th>à</th>
                                <th>Sexe</th>
                                <th>Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>

                                <input type="hidden" name="ecole_user" value="{{$ecole}}">
                                @foreach ($users as $user)
                                    <tr style="text-transform: uppercase;">
                                        <td>{{ $loop->index +1 }}</td>
                                        <td>{{ $user->code_user }}</td>
                                        <td><input type="checkbox" name="selected_users[]" value="{{$user->code_user}}"></td>
                                        <td>{{ $user->nom_user }}</td>
                                        <td>{{ $user->prenom_user }}</td>
                                        <td>{{ $user->code_filiere }}</td>
                                        <td>{{ $user->ecole_user }}</td>
                                        <td>{{ $user->date_naissance_user->format("d/m/Y") }}</td>
                                        <td>{{ $user->lieu_naissance_user }}</td>
                                        <td>{{ \Str::substr($user->sexe_user , 0, 1) }}</td>
                                        <td>{{ $user->first_phone_user }}</td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary">Changer de site de formation</button>
                    </div>
                @else
                <div class="alert alert-primary w-50 h4 m-auto"> Aucun étudiant ne correspond à vos critères de recherche</div>
                @endif
            @endisset
        </form>
        </div>
    </div>
</div>
@endsection

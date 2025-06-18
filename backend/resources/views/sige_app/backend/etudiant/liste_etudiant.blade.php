@extends("sige_app.backend.template.backend")
@section("js")
    <script>
        function showDeleteCandidatModal(code) {
            document.getElementById("code_user").value = code
            $("#confirmDeleteCandModal").modal("show");
        }

        function showUpdatePhotoModal(code) {
            document.getElementById("code_user_photo").value = code
            $("#updatePhoto").modal("show");
        }

        var previewPicture = function (e) {
            var image = document.getElementById("image_carte");
            const [picture] = e.files
            if (picture) {
                image.src = URL.createObjectURL(picture)
            }
        }
    </script>
@endsection
@section("content")
    <div class="modal fade" id="confirmDeleteCandModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger" style="color: white">
                    <h5 class="modal-title">Confirmation de suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/delete_user" method="post">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>Voulez-vous vraiment supprimer cet(te) étudiant(e)?? Cette action est irreversible!!!!! </p>
                        <input type="hidden" value="" id="code_user" name="code_user">
                    </div>
                    <div class="modal-footer mt-0" style="display: flex; justify-content: center;">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-success col-2">Non</button>
                        <button type="submit" class="btn btn-danger col-2">Oui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updatePhoto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" style="color: white">Modification de Photo de Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/change_photo" method="post" style="justify-content: center;"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <p class="p-1 text-center">Veuillez importer votre demi photo 4x4 qui sera présente sur votre
                        carte</p>
                    <div class="row mt-2 mb-3 p-2">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="file" accept="image/png, image/jpeg, image/gif, image/bmp" required
                                           class="form-control" required name="photo_user" placeholder="Votre photo"
                                           onchange="previewPicture(this)">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-outline-primary">Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5 offset-6 m-auto mb-2">
                            <img src="#" alt="" id="image_carte" style="width: 40mm; height: 40mm;">
                        </div>
                    </div>
                    <input type="hidden" name="code_user" id="code_user_photo" value="">
                </form>
            </div>
        </div>
    </div>

    <div class="card" style="width: 100%; margin:auto;">
        <div class="card-header row">
            <h4 class="card-title col-2">Listing</h4>
            <div class="col-9">
                <form action="/search_etudiant" method="post">
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
                                @foreach (\App\Models\Anneescolaire::orderBy("debut_annee", "desc")->get() as $anneescolaire)
                                    <option value="{{$anneescolaire->code_annee}}">
                                        {{
                                                    \Carbon\Carbon::parse($anneescolaire->debut_annee)->format('Y')
                                                }}-{{
            \Carbon\Carbon::parse($anneescolaire->fin_annee)->format('Y')
        }}                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <select name="level" id="level" class="form-select">
                                @foreach (\App\Models\notes\Niveau::all() as $niveau)
                                    <option value="{{$niveau->code_niveau}}"> {{$niveau->label_niveau}} </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-2">
                            <select name="inscrit" id="inscrit" class="form-select">
                                <option value="1"> OUI </option>
                                <option value="0"> NON </option>
                            </select>
                        </div> --}}
                        <div class="col-2">
                            <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-1">
                @isset($ecole)
                    <div class="col-sm-2" style="text-align: center;">
                        <form action="/imprimer" method="POST">
                            @csrf
                            <input type="hidden" name="filier" value="{{$filiere}}">
                            <input type="hidden" name="nivea" value="{{$level}}">
                            <input type="hidden" name="code_sit" value="{{$ecole}}">
                            <input type="hidden" name="code_anne" value="{{$annee}}">
                            {{-- <input type="hidden" name="inscri" value="{{$inscrit}}"> --}}
                            <button type="submit" class="btn btn-outline-primary"><i class="ri-file-word-2-line"></i>
                            </button>
                            {{-- <a href="/imprimer/pdf-{{$fil}}" class=" btn btn-outline-danger rounded p-2" title="Imprimer en pdf"  target="blank"> <i class='bx bxs-file-pdf' ></i></a> &nbsp; &nbsp; <a href="/imprimer/excel-{{$fil}}" class=" btn btn-outline-primary rounded p-2" title="Imprimer en Excel" target="blank"></a> --}}
                        </form>
                    </div>
                @endisset
            </div>
        </div>
        <div class="card-body">
            @isset($etudiants)
                @if ($etudiants->count() > 0)
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover datatable" id="filterTable">
                            <thead>
                            <tr>
                                <th>Actions</th>
                                <th>N°</th>
                                <th>Matricule</th>
                                <th>Noms</th>
                                <th>Prénoms</th>
                                <th>Sexe</th>
                                <th>Né(e) le</th>
                                <th>à</th>
                                <th>Téléphone</th>
                                <th>Region</th>
                                <th>Département</th>
                                {{-- <th>Arrond</th> --}}
                                <th>Diplôme</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($etudiants as $u)
                                <tr>
                                        <?php
                                        $dip = \App\Models\UsersDiplome::where("code_user", $u->code_user)->first();
                                        $diplome = \App\Models\Diplome::where("code_dip", $dip->code_dip)->first();
                                        ?>
                                    <td style="text-align: center;">
                                        <a href="#" onclick="showDeleteCandidatModal('{{$u->code_user}}-{{$filiere}}')"
                                           class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                        <a href="/update_info/{{$u->code_user}}-{{$filiere}}"
                                           class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                                        @if ($u->photo_user == null)
                                            <a href="#" onclick="showUpdatePhotoModal('{{$u->code_user}}')"
                                               class="btn-outline-primary rounded p-1"><i class='bx bxs-image'></i> </a>
                                        @else
                                            <a href="#" onclick="showUpdatePhotoModal('{{$u->code_user}}')"
                                               class="btn-outline-success rounded p-1"><i class='bx bxs-image'></i> </a>
                                        @endif
                                    </td>
                                    <td> {{$loop->index+1}}  </td>
                                    <td> {{$u->code_user}}  </td>
                                    <td> {{$u->nom_user}} </td>
                                    <td>{{$u->prenom_user}} </td>
                                    <td> {{$u->sexe_user}} </td>
                                    <td> {{$u->date_naissance_user->format("d/m/Y")}}</td>
                                    <td>{{$u->lieu_naissance_user}} </td>
                                    <td> {{$u->first_phone_user}} </td>
                                    <td> {{$u->region_origine_user}} </td>
                                    <td> {{$u->depart_origine_user}} </td>
                                    <td> {{$u->arrond_origine_user}} </td>
                                    {{-- <td> {{$diplome->label_dip}} {{$diplome->specialtite_dip}} </td> --}}
                                    <td style="text-align: center;">
                                        <a href="#" onclick="showDeleteCandidatModal('{{$u->code_user}}-{{$filiere}}')"
                                           class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                        <a href="/updatne_info/{{$u->code_user}}-{{$filiere}}"
                                           class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <hr>
                        {{-- <div style="align-self: flex-end; text-align: right !important;  ">
                                {{ $etudiants->links("pagination::bootstrap-4") }}
                        </div> --}}
                    </div>
                @else
                    <div class="alert alert-primary h4 w-50 text-center m-auto">
                        <p>Aucun étudiant ne correspond à vos critères de recherche</p>
                    </div>
                @endif
            @endisset
        </div>
    </div>
@endsection

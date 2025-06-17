@extends("sige_app.backend.template.backend")
@section("js")
    <script>
        function showDeleteCandidatModal(code) {
            document.getElementById("code_user").value = code
            $("#confirmDeleteCandModal").modal("show");
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
    <div class="card" style="width: 100%; margin:auto;">

        <div class="card-header pb-0" style="text-align: right; border-bottom: 1px solid rgb(217, 217, 217);">
            <h2 style="float: left;">Liste des étudiants inscrits</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div style="float: left">
                    <form action="/valider_paiement" method="post" style="justify-content: center;">
                        {{ csrf_field() }}
                        <div class="row mt-2" style="justify-content: center; margin-bottom: 1%;">
                            <div class="col-sm-2">
                                <select name="niveau" id="niveau" class="form-select">
                                    @foreach (\App\Models\notes\Niveau::all() as $niveau)
                                        <option value="{{$niveau->code_niveau}}"> {{$niveau->label_niveau}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="filiere" id="filiere" class="form-select">
                                    @foreach (\App\Models\Filiere::all() as $filiere)
                                        <option value="{{$filiere->code_filiere}}"> {{$filiere->code_filiere}}
                                            -- {{$filiere->label_filiere}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-outline-primary"><i class="ri-search-line"></i>
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="filterInput" onkeyup="filterFunction()"
                                       placeholder="Filtre de recherche" class="form-control">
                            </div>
                            <div class="col-sm-2" style="text-align: center;">
                                <a href="/imprimer/pdf-{{$fil}}" class=" btn btn-outline-danger rounded p-2"
                                   title="Imprimer en pdf" target="blank"><i class='bx bxs-file-pdf'></i></a> &nbsp;
                                &nbsp; <a href="/imprimer/excel-{{$fil}}" class=" btn btn-outline-primary rounded p-2"
                                          title="Imprimer en Excel" target="blank"><i
                                            class="ri-file-word-2-line"></i></a>
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
            </div>
            @isset($etudiants)
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-hover datatable" id="filterTable">
                        <thead>
                        <tr>
                            <th></th>
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
                            <th>1 <sup>ière</sup> Tranche</th>
                            <th>2 <sup>nde</sup> Tranche</th>
                            <th>Frais Médicaux</th>
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
                                    <a href="#" onclick="showDeleteCandidatModal('{{$u->code_user}}-{{$fil}}')"
                                       class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                    <a href="/update_info/{{$u->code_user}}-{{$fil}}"
                                       class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
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
                                <td> {{$diplome->label_dip}} {{$diplome->specialtite_dip}} </td>
                                <td style="text-align: center;">
                                    <a href="#" onclick="showDeleteCandidatModal('{{$u->code_user}}-{{$fil}}')"
                                       class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                                    <a href="/update_info/{{$u->code_user}}-{{$fil}}"
                                       class="btn-outline-success rounded p-1"><i class='bx bx-pencil'></i> </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <div style="align-self: flex-end; text-align: right !important;  ">
                        {{ $etudiants->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            @else
                <div class="alert alert-info h4">
                    <p>Aucun étudiant trouvé dans la base</p>
                </div>
            @endisset
        </div>
    </div>
@endsection

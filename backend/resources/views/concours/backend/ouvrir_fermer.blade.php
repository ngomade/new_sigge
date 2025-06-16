@extends("concours.backend.template.backend_concours")
@section("content")
<?php
    $nb_e  = \App\Models\Candidat::where("cursus_code", "EBTTL")->count();
    $nb_g  = \App\Models\Candidat::where("cursus_code", "GLTCO")->count();
    $nb_h  = \App\Models\Candidat::where("ca_sexe", "MASCULIN")->count();
    $nb_f  = \App\Models\Candidat::where("ca_sexe", "FEMININ")->count();
?>
<div class="modal fade" id="addSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success" style="color: white">
                <h5 class="modal-title">Ajout d'une Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/add_session" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <p class="text-center" style="text-align: center; color: blue;">Renseignez les informations</p>
                    <div class="row m-3">
                        <label for="ca_annee_diplome" class="col-sm-6 col-form-label">Session<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select name="annee" id="annee" class="form-select" required>

                            </select>
                        </div>
                    </div>
                    <div class="row m-3 mb-0">
                        <label for="debut" class="col-sm-5 col-form-label">Date de début <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                                <input type="datetime-local" class="form-control" name="debut" required id="debut">
                        </div>
                    </div>
                    <div class="row m-3 mb-0">
                        <label for="cloture" class="col-sm-5 col-form-label">Date de Clôture <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                                <input type="datetime-local" class="form-control" name="cloture" required id="cloture" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success" style="color: white">
                <h5 class="modal-title">Modifier une Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/update_session" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_session_edit" id="id_session_edit" value="">
                <div class="modal-body">
                    <p class="text-center" style="text-align: center; color: blue;">Renseignez les informations</p>
                    <div class="row m-3">
                        <label for="ca_annee_diplome" class="col-sm-6 col-form-label">Session<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select name="annee_edit" id="annee_edit" class="form-select" required>

                            </select>
                        </div>
                    </div>
                    <div class="row m-3 mb-0">
                        <label for="debut" class="col-sm-5 col-form-label">Date de début <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                                <input type="datetime-local" class="form-control" name="debut" required id="debut">
                        </div>
                    </div>
                    <div class="row m-3 mb-0">
                        <label for="cloture" class="col-sm-5 col-form-label">Date de Clôture <span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                                <input type="datetime-local" class="form-control" name="cloture" required id="cloture" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-0">
                    <button type="submit" class="btn btn-success">Valider <i class="ri-checkbox-circle-fill"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger" style="color: white">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/delete_session" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer cette session</p>
                    <input type="hidden" value="" id="id_session" name="id_session">
                </div>
                <div class="modal-footer mt-0" style="display: flex; justify-content: center;">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success col-2">Non</button>

                    <button type="submit" class="btn btn-danger col-2">Oui</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <main  id="main" class="main">
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8">
                    <div style="display: flex; justify-content: right;" class="mb-3">
                    <button class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#addSessionModal"><i class="ri-add-circle-line h5"></i> Créer une Session</button>
                    </div>
                    <div class="card" style="justify-content: center;">
                      <h3 class="card-header bg-success p-2" style="color:white;">Liste des sessions</h3>
                      <div class="card-body p-1">
                        <table class="table table-bordered table-hover">
                            <thead >
                                <tr >
                                  <th scope="col" class="bg-secondary">N°</th>
                                  <th scope="col" class="bg-secondary">Année</th>
                                  <th scope="col" class="bg-secondary">Début</th>
                                  <th scope="col" class="bg-secondary">Cloture</th>
                                  <th scope="col" class="bg-secondary">Auteur</th>
                                  <th scope="col" class="bg-secondary">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($sessions as $session)
                                  <tr>
                                      <td> {{$loop->index+1}} </td>
                                      <td> {{$session->annee}} </td>
                                      <td> {{$session->debut}} </td>
                                      <td> {{$session->cloture}} </td>
                                      <td> {{\App\Models\Admin::where("ad_code", $session->ad_code)->first()->ad_login}} </td>
                                      <td style=" display: flex; justify-content: center;" class="p-1">
                                        <button  onclick="showEditSessionModal({{$session->id}})"  class="h3  p-0"><i class="ri-pencil-fill bg-primary rounded"></i></button> &nbsp;
                                        <button  class="h3  p-0" onclick="showDeleteSessionModal({{$session->id}})" ><i class="ri-close-circle-line rounded bg-danger"></i></button> &nbsp;
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                        </table>
                      </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filtre</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
                                <li><a class="dropdown-item" href="#">Ce mois</a></li>
                                <li><a class="dropdown-item" href="#">Cette Année</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Statistique: Filière <span>| Aujourd'hui</span></h5>

                            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#trafficChart")).setOption({
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [{
                                            name: 'Repartition par Filière',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [{
                                                    value: {{ $nb_e }},
                                                    name: 'EBTTL'
                                                },
                                                {
                                                    value: {{ $nb_g }},
                                                    name: 'GLTCO'
                                                }
                                            ]
                                        }]
                                    });
                                });
                            </script>

                        </div>
                    </div>

                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filtre</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
                                <li><a class="dropdown-item" href="#">Ce mois</a></li>
                                <li><a class="dropdown-item" href="#">Cette Année</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Statistiques: Sexe<span>| Aujourd'hui</span></h5>

                            <div id="sexeChart" style="min-height: 400px;" class="echart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#sexeChart")).setOption({
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [{
                                            name: 'Répartition par Sexe',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: [{
                                                    value: {{ $nb_h }},
                                                    name: 'Homme'
                                                },
                                                {
                                                    value: {{ $nb_f }},
                                                    name: 'Femme'
                                                }
                                            ]
                                        }]
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

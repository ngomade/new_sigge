@extends("concours.backend.template.backend_concours")
@section("content")
<?php
    $nb_e  = \App\Models\Candidat::where("cursus_code", "EBTTL")->count();
    $nb_g  = \App\Models\Candidat::where("cursus_code", "GLTCO")->count();
    $nb_h  = \App\Models\Candidat::where("ca_sexe", "MASCULIN")->count();
    $nb_f  = \App\Models\Candidat::where("ca_sexe", "FEMININ")->count();
?>
    <main  id="main" class="main">
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8">
                    @if ($candidats->count() >0)
                    <div class="col-12">
                        <div class="card overflow-auto">

                          <div class="card-body">
                            <h5 class="card-title">{{$candidats->count()}} Résultats</h5>

                            <table class="table table-borderless table-hover datatable">
                              <thead>
                                <tr>
                                  <th scope="col">Code</th>
                                  <th scope="col">Nom</th>
                                  <th scope="col">Prénom</th>
                                  <th scope="col">N° CNI</th>
                                  <th scope="col">Filière</th>
                                  <th scope="col">Centre</th>
                                  <th scope="col">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($candidats as $ca)
                                  <tr>
                                      <td class="badge bg-success"> {{$ca->ca_code}} </td>
                                      <td> {{$ca->ca_nom}} </td>
                                      <td> {{$ca->ca_prenom}} </td>
                                      <td> {{$ca->ca_num_cni}} </td>
                                      <td> {{$ca->cursus_code}} </td>
                                      <td> {{$ca->ca_centre_examen}} </td>
                                      <td style="text-align: center;">
                                          <a href="/show_candidat/{{$ca->ca_code}}" class="btn btn-secondary"><i class="ri-eye-fill"></i></a> &nbsp;
                                      </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>

                          </div>

                        </div>
                      </div>
                    @else
                      <div class="alert alert-warning h3 p-3 mt-5 mb-5" >  <i class="ri-information-fill" style="font-size: 1.4em;"></i> &nbsp; &nbsp;Aucun Resultat trouvé </div>
                    @endif
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

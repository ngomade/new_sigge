@extends('concours.backend.template.backend_concours')
@section('content')
    <main id="main" class="main">
        <?php
        $nb_ca = \App\Models\Candidat::count();
        $nb_h = \App\Models\Candidat::where('ca_sexe', 'MASCULIN')->count();
        $nb_f = \App\Models\Candidat::where('ca_sexe', 'FEMININ')->count();
        $nb_e = \App\Models\Candidat::where('cursus_code', 'EBTTL')->count();
        $nb_g = \App\Models\Candidat::where('cursus_code', 'GLTCO')->count();
        ?>
        <div class="pagetitle">
            <h1>Tableau de Bord</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Acceuil</a></li>
                    <li class="breadcrumb-item active">Tableau de Bord</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
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

                                <div class="card-body">
                                    <h5 class="card-title">Candidats <span>| Aujourd'hui</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6> {{ $nb_ca }} Candidats</h6>
                                            <!--<span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>-->

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

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

                                <div class="card-body">
                                    <h5 class="card-title">Homme <span>| Aujourd'hui</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-heart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $nb_h }} Hommes</h6>
                                            @if (($nb_h / $nb_ca) * 100 > 50)
                                                <span class="text-success small pt-1 fw-bold">
                                                    {{ \Str::substr(($nb_h / $nb_ca) * 100, 0, 4) }} % </span> <span
                                                    class="text-muted small pt-2 ps-1">En hausse</span>
                                            @else
                                                <span class="text-danger small pt-1 fw-bold">
                                                    {{ \Str::substr(($nb_h / $nb_ca) * 100, 0, 4) }} % </span> <span
                                                    class="text-muted small pt-2 ps-1">En baisse</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-12">

                            <div class="card info-card customers-card">

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

                                <div class="card-body">
                                    <h5 class="card-title">Femme <span>| Aujourd'hui</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-hearts"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $nb_f }} Femmes</h6>
                                            @if (($nb_f / $nb_ca) * 100 > 50)
                                                <span class="text-success small pt-1 fw-bold">
                                                    {{ \Str::substr(($nb_f / $nb_ca) * 100, 0, 4) }} %</span> <span
                                                    class="text-muted small pt-2 ps-1">En hausse</span>
                                            @else
                                                <span class="text-danger small pt-1 fw-bold">
                                                    {{ \Str::substr(($nb_f / $nb_ca) * 100, 0, 4) }} %</span> <span
                                                    class="text-muted small pt-2 ps-1">En baisse</span>
                                            @endif

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
                                        <li><a class="dropdown-item" href="#">Ce mois</a></li>
                                        <li><a class="dropdown-item" href="#">Cette Année</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Récentes Inscription <span>| Aujourd'hui</span></h5>

                                    <table class="table table-borderless datatable">
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
                                            @foreach ($r_candidat as $ca)
                                                <tr>
                                                    <td class="badge bg-success"> {{ $ca->ca_code }} </td>
                                                    <td> {{ $ca->ca_nom }} </td>
                                                    <td> {{ $ca->ca_prenom }} </td>
                                                    <td> {{ $ca->ca_num_cni }} </td>
                                                    <td> {{ $ca->cursus_code }} </td>
                                                    <td> {{ $ca->ca_centre_examen }} </td>
                                                    <td style="text-align: center;">
                                                        <a href="/show_candidat/{{ $ca->ca_code }}"
                                                            class="btn btn-secondary"><i class="ri-eye-fill"></i></a>
                                                        &nbsp;
                                                        <!--<a href="" class="btn btn-danger"><i class="ri-close-line"></i></a>-->
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
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

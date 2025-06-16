@extends('concours.backend.template.backend_concours')
@section('content')
<div class="modal fade" id="confirmDeleteCandModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger" style="color: white">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/delete_cand" method="post">
                {{ csrf_field() }}
                <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer ce candidat?? Cette action est irreversible!!!!! </p>
                    <input type="hidden" value="" id="cand_code" name="cand_code">
                </div>
                <div class="modal-footer mt-0" style="display: flex; justify-content: center;">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success col-2">Non</button>
                    <button type="submit" class="btn btn-danger col-2">Oui</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <main id="main" class="main">
        <?php
        $cand = [['candidat' => $candidats]];
        ?>
        <div class="pagetitle">
            <h1>Tableau de Bord</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/liste_candidat">Gestion des candidats</a></li>
                    <li class="breadcrumb-item active">Tableau de Bord</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="card">
                <div class="card-body mt-2">
                    <div class="search-bar">
                        <form class="search-form d-flex align-items-center" method="POST" action="/search_candidat_imp">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="row">
                                        <label for="filiere" class="col-sm-3 col-form-label">Filière</label>
                                        <div class="col-sm-9">
                                            <select name="filiere" id="filiere" class="form-select">
                                                <option value="">Toutes les Filières</option>
                                                @foreach (\App\Models\Cursus::all() as $c)
                                                    <option value="{{ $c->cursus_code }}">{{ $c->cursus_label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <label for="ca_centre_examen" class="col-sm-5 col-form-label">Centre
                                            D'examen</label>
                                        <div class="col-sm-7">
                                            <select name="ca_centre_examen" id="ca_centre_examen" class="form-select">
                                                <option value="">Tout</option>
                                                <option value="AMBAM">Ambam</option>
                                                <option value="Bafoussan">Bafoussam</option>
                                                <option value="Bamenda">Bamenda</option>
                                                <option value="Bertoua">Bertoua</option>
                                                <option value="Buéa">Buéa</option>
                                                <option value="Ebolowa">Ebolowa</option>
                                                <option value="Douala">Douala</option>
                                                <option value="Garoua">Garoua</option>
                                                <option value="Maroua">Maroua</option>
                                                <option value="Ngaoundéré">Ngaoundéré</option>
                                                <option value="Yaoundé">Yaoundé</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="row" style="display: flex; justify-content: center;">
                                        <label for="ca_centre_depot" class="col-sm-6 col-form-label">Centre
                                            dépôt</label>
                                        <div class="col-sm-6">
                                            <select name="ca_centre_depot" id="ca_centre_depot" class="form-select">
                                                <option value="">Tout</option>
                                                <option value="AMBAM">Ambam</option>
                                                <option value="DRES OUEST">DRES OUEST</option>
                                                <option value="DRES NORD-OUEST">DRES NORD-OUEST</option>
                                                <option value="DRES EST">DRES EST</option>
                                                <option value="DRES SUD OUEST">DRES SUD OUEST</option>
                                                <option value="DRES SUD">DRES SUD</option>
                                                <option value="DRES LITTORAL">DRES LITTORAL</option>
                                                <option value="DRES NORD">DRES NORD</option>
                                                <option value="DRES EXTREME-NORD">DRES EXTREME-NORD</option>
                                                <option value="DRES ADAMAOUA">DRES ADAMAOUA</option>
                                                <option value="DRES CENTRE">DRES CENTRE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-outline-primary"><i
                                            class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <h5 class="card-header bg-success" style="color: white;">Liste des candidats </h5>
                        <div class="card-body">
                            <table class="table table-borderless table-hover datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Prénom</th>
                                        <th scope="col">Date Naissance</th>
                                        <th scope="col">Sexe</th>
                                        <th scope="col">Téléphone</th>
                                        <th scope="col">Nationalité</th>
                                        <th scope="col">Année D</th>
                                        <th scope="col">Mention</th>
                                        <th scope="col">Langue</th>
                                        <th scope="col">N° CNI</th>
                                        <th scope="col">Filière</th>
                                        <th scope="col">Dépôt</th>
                                        <th scope="col">Examen</th>
                                        <th scope="col">Date Inscription</th>
                                        <th scope="col">Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($candidats as $ca)
                                        <tr>
                                            <td class="badge bg-primary"> {{ $ca->ca_code }} </td>
                                            <td> {{ $ca->ca_nom }} </td>
                                            <td> {{ $ca->ca_prenom }} </td>
                                            <td> {{ \Str::substr($ca->ca_date_naiss, 0, 10) }} </td>
                                            <td> {{ $ca->ca_sexe }} </td>
                                            <td> {{ $ca->ca_telephone }} </td>
                                            <td> {{ $ca->ca_nationalite }} </td>
                                            <td> {{ $ca->ca_annee_diplome }} </td>
                                            <td> {{ $ca->ca_mention_diplome }} </td>
                                            <td> {{ $ca->ca_premirere_lang }} </td>
                                            <td> {{ $ca->ca_num_cni }} </td>
                                            <td> {{ $ca->cursus_code }} </td>
                                            <td> {{ $ca->ca_centre_depot }} </td>
                                            <td> {{ $ca->ca_centre_examen }} </td>
                                            <td> {{ \Str::substr($ca->created_at, 0, 10) }} </td>
                                            <td style="text-align: center;">
                                                <a style=" " href="/show_candidat/{{ $ca->ca_code }}"
                                                    class="btn btn-outline-secondary pt-0 pb-0"><i
                                                        class="ri-eye-fill"></i></a> &nbsp;
                                                <button class="btn btn-danger pt-0 pb-0 mt-1" onclick="showDeleteCandidatModal('{{$ca->ca_code}}')"><i class="ri-close-line"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>

            </div>

            <div class="row" style="display: flex; justify-content: center;">
                <div class="col-lg-2">
                    <div class="card">
                        <div class="card-header bg-secondary" style="color:white; text-align: center;">Impression <i
                                class="ri-printer-fill"></i></div>
                        <div class="card-body p-2">
                            <div class="row" style="display: flex; justify-content: center;">
                                <div class="col-sm-4" style="display: flex; justify-content: center;">
                                    <div class="rounded">
                                        <a href="/impression_liste/pdf" target="_blank" rel="noopener noreferrer"><img
                                                src="{{ asset('frontend/img/download_pdf.png') }}" alt="Télécharger"
                                                class="rounded" style="width: 50px;" id="download"
                                                title="Télécharger la liste en fichier PDF"></a>
                                    </div>
                                </div>

                                <div class="col-sm-4" style="display: flex; justify-content: center;">
                                    <div class=" rounded">
                                        <a href="/impression_liste/excel" target="_blank" rel="noopener noreferrer"><img
                                                src="{{ asset('frontend/img/download_excel.png') }}" alt="Télécharger"
                                                class="rounded" style="width: 50px;" id="download"
                                                title="Télécharger la liste en fichier Excel"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection

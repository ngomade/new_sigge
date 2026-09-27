@extends("sige_app.frontend.template.frontend")

@section("style")
    <style>
        .staff-section-title {
            position: relative;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            text-align: center;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 1.25rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            margin: 2.5rem 0 1.5rem 0;
        }

        .personnel-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease-in-out;
            background-color: #fff;
            font-size: 0.85rem;
        }

        .personnel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .personnel-img-wrapper {
            width: 110px;
            height: 110px;
            margin: 15px auto 10px auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .personnel-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .personnel-info .row {
            margin-bottom: 4px;
        }

        .personnel-info .label {
            font-weight: 600;
            color: #6c757d;
        }

        .personnel-info .value {
            color: #212529;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .staff-section-title {
                font-size: 1.1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 text-center border-bottom">
                <h3 class="mb-0 text-success fw-bold">Staff Administratif de l'ESTLC</h3>
            </div>
            
            <div class="card-body bg-light">

                <!-- LA DIRECTION -->
                <div class="staff-section-title">La Direction</div>
                <div class="row g-4 justify-content-center">
                    
                    <!-- Directeur -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/directeur.jpg') }}" alt="Directeur">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">TAMBA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Jean Gaston</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur Titulaire</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Directeur</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:directeur@estlc.unv-ebolowa.cm">directeur@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- Directeur Adjoint -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/da.jpg') }}" alt="Directeur Adjoint">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">KOUMI NGOH</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Simon</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Maitre de Conférences</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Directeur Adjoint</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:da@estlc.unv-ebolowa.cm">da@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- CREP -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/crep.jpg') }}" alt="CREP">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MOUZONG PEMI</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Marcelin</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Maitre de Conférences</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Centre (CREP)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:crep@estlc.unv-ebolowa.cm">crep@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- CDA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/lankoul.jpg') }}" alt="CDA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">LANGOUL</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">FRANCIS</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Centre (CDA)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:cda@estlc.unv-ebolowa.cm">cda@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- CISI -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/cisi.jpg') }}" alt="CISI">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">KEUDEM ZONING</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Steve</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Cellule Informatique</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:cisi@estlc.unv-ebolowa.cm">cisi@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- SOCAS -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/socas.jpg') }}" alt="SOCAS">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">DANADAM</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Flavien</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Conseiller Principal</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef service (SOCAS)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:socas@estlc.unv-ebolowa.cm">socas@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- SCRP -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('share/img/estlc_sans_fond.png') }}" alt="SCRP">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MFOM</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">GUY DEROSIER</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Collèges</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef service (SCRP)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:scrp@estlc.unv-ebolowa.cm">scrp@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DIVISION DES AFFAIRES ACADÉMIQUES -->
                <div class="staff-section-title">Division Des Affaires Académiques, de la Recherche et de la Coopération</div>
                <div class="row g-4 justify-content-center">
                    
                    <!-- DAARC -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/daarc.jpg') }}" alt="DAARC">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">ONANA ESSAMA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Bedel Giscard</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Division (DAARC)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:daarc@estlc.unv-ebolowa.cm">daarc@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- MBIAM -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/mbiam.jpg') }}" alt="MBIAM">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MBIAM</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Salomon Parfait</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur d'Enseignement Technique</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Service des Enseignements</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:see@estlc.unv-ebolowa.cm">see@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- NANA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/nana.jpg') }}" alt="NANA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">NGAPOUT NANA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">FADIMATOU</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">CPOSUP</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service des Diplômes</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:sdc@estlc.unv-ebolowa.cm">sdc@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237) 696918207</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- AZONG -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/azong.jpg') }}" alt="AZONG">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">AZONG TCHITILE</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Emmanuel Wilfried</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Assistant</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service du Personnel Enseignant</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:spe@estlc.unv-ebolowa.cm">spe@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- MVOGO -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/mvogo.png') }}" alt="MVOGO">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MVOGO AHANDA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Joseph Jean Baptiste</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef service de la Recherche</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:src@estlc.unv-ebolowa.cm">src@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- ABENA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/abena.jpg') }}" alt="ABENA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">ABENA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Michel Arnaud</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Qualité et Normes</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:abenamcjoss2@gmail.com">abenamcjoss2@gmail.com</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237) 655537927</div></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DIVISION DE LA SCOLARITÉ -->
                <div class="staff-section-title">Division de la Scolarité et du Suivi des Etudiants</div>
                <div class="row g-4 justify-content-center">
                    
                    <!-- DSSE -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/dsse.jpg') }}" alt="DSSE">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">EDOU ESSEKO</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Martin Brice</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Assistant</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Division (DSSE)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:dsse@estlc.unv-ebolowa.cm">dsse@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- DJOMO -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/djomo.jpg') }}" alt="DJOMO">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">DJOMO ONDO</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Edmond Aimé</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Cadre Contractuel</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Scolarité et Statistiques</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:sss@estlc.unv-ebolowa.cm">sss@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- ASSOUMOU -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/assoumou.jpg') }}" alt="ASSOUMOU">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">ASSOUMOU EMVO</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Jackson</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Conseiller Principal</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service des Stages</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:ssip@estlc.unv-ebolowa.cm">ssip@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DIVISION DES AFFAIRES ADMINISTRATIVES ET FINANCIÈRES -->
                <div class="staff-section-title">Division des Affaires Administratives et Financières</div>
                <div class="row g-4 justify-content-center">
                    
                    <!-- NTYAM ASSE -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('share/img/estlc_sans_fond.png') }}" alt="NTYAM">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">NTYAM ASSE</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Georges</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Division (DAAF)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:daaf@estlc.unv-ebolowa.cm">daaf@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- EBOLO -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/ebolo.jpg') }}" alt="EBOLO">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">EBOLO</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Pierre Arnold</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">-</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Services des Affaires Financiers</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:saf@estlc.unv-ebolowa.cm">saf@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- NANGA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/nanga.jpg') }}" alt="NANGA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">NANGA ETOA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Mireille Lorine</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Administration Générale</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:sagpne@estlc.unv-ebolowa.cm">sagpne@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237) 691289082</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- MANGA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/manga.jpg') }}" alt="MANGA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">ETEME MANGA</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Cédric Wilfried</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Maintenance et Matériel</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:smm@estlc.unv-ebolowa.cm">smm@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- PAKI -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/paki.jpg') }}" alt="PAKI">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">PAKI</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Hervé</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Animation Sportive</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:sasc@estlc.unv-ebolowa.cm">sasc@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- DIVISION DE LA FORMATION CONTINUE ET À DISTANCE -->
                <div class="staff-section-title">Division de la Formation Continue et à distance</div>
                <div class="row g-4 justify-content-center">
                    
                    <!-- MVONDO -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/dfcd.jpg') }}" alt="MVONDO">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MVONDO Didier</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Serge</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur des Lycées</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef De Division (DFCD)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:dfcd@estlc.unv-ebolowa.cm">dfcd@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- DJIEME -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/sfc.jpg') }}" alt="DJIEME">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">DJIEME EWOLE</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">OMER LEGRAND</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur Adjoint</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Formation Continue</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:sfc@estlc.unv-ebolowa.cm">sfc@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237) 699219769</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- NKONJOH -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/sfoad.jpg') }}" alt="NKONJOH">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">NKONJOH NGOMADE</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Armel</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Professeur d'Enseignement Technique</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef Service Formation à Distance</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:sfd@estlc.unv-ebolowa.cm">sfd@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- NOS DÉPARTEMENTS -->
                <div class="staff-section-title">Nos Départements</div>
                <div class="row g-4 justify-content-center">
                    
                    <!-- MBALLA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/nballa.png') }}" alt="MBALLA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MBALLA ELOUNDOU</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Aimé Christel</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Département (TEG)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:depteg@estlc.unv-ebolowa.cm">depteg@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- MBOUSSI -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/mboussi.jpg') }}" alt="MBOUSSI">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MBOUSSI</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Serge Bertrand</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Département (TESB)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:deptesb@estlc.unv-ebolowa.cm">deptesb@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- DIBOMA -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/dgtp.jpg') }}" alt="DIBOMA">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">DIBOMA Benjamin</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Salomon</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Département (Génie Transports)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:deptgt@estlc.unv-ebolowa.cm">deptgt@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- SAPNKEN -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/sapi.jpg') }}" alt="SAPNKEN">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">SAPNKEN</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">FLAVIAN EMMANUEL</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Département (Génie Logistique)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:deptgl@estlc.unv-ebolowa.cm">deptgl@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- KIBONG -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/dgmc.jpg') }}" alt="KIBONG">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">KIBONG</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Marius Tony</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Département (Génie Mécatronique)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:deptgm@estlc.unv-ebolowa.cm">deptgm@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                    <!-- MESSI -->
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card personnel-card h-100 p-3">
                            <div class="personnel-img-wrapper">
                                <img src="{{ asset('sige_app/frontend/img/team/messi.jpg') }}" alt="MESSI">
                            </div>
                            <div class="card-body personnel-info px-0">
                                <div class="row"><div class="col-4 label">Noms:</div><div class="col-8 value">MESSI NGUELE</div></div>
                                <div class="row"><div class="col-4 label">Prénoms:</div><div class="col-8 value">Thomas</div></div>
                                <div class="row"><div class="col-4 label">Grade:</div><div class="col-8 value">Chargé de Cours</div></div>
                                <div class="row"><div class="col-4 label">Fonction:</div><div class="col-8 value">Chef de Département (Génie Informatique)</div></div>
                                <div class="row"><div class="col-4 label">E-mail:</div><div class="col-8 value text-truncate"><a href="mailto:deptgi@estlc.unv-ebolowa.cm">deptgi@estlc.unv-ebolowa.cm</a></div></div>
                                <div class="row"><div class="col-4 label">Tel:</div><div class="col-8 value">(+237)</div></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@extends("sige_app.frontend.template.frontend")
@section("style")
    <style>
        h2{
            border-bottom: 1px solid green;
            text-align: center;
            background-color: rgb(81, 200, 129);
            border-radius: 20px 20px 0px 0px;
            color: white;
        }
        .personnel{
            height: 90%;
            border: 1px  solid rgb(227, 225, 225);
            box-shadow: 2px 3px 5px rgb(179, 179, 238);
            border-radius: 8px;
            font-size: 0.9em;
            padding: 1%;
            padding-top: 0 !important;
            margin: 1%;
            display: inline-block;
        }
        .personnel:hover{
            background-color: rgb(168, 168, 239);
        }
        .personnel img{
            width: 55%;
            margin : auto;
            height: 160px;
            border-radius: 50%;
            display: block;
        }
        .personnel .col{
            font-weight: bold;
            font-size: 0.9em;
            text-align: justify;
        }
        @media(max-width: 600px){
            .personnel img{
            width: 40%;
            margin-left: 30%;
            height: 130px;
        }
        h2{
            border-bottom: 1px solid green;
            text-align: center;
            background-color: rgb(81, 200, 129);
            border-radius: 20px 20px 0px 0px;
            color: white;
            font-size: 0.8em;
        }
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header p-2 pt-2" style="text-align: justify;">
                <div style="text-align: center;" class="h3">Staff Administratif de l'ESTLC</div>
            </div>
            <div class="card-body container-fluid">
                <div class="row">
                        <h2> La Direction</h2>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/directeur.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">TAMBA </div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Jean Gaston</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur Titulaire</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Directeur</div></div>
                        <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:directeur@estlc.unv-ebolowa.cm">directeur@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                    <div class="col-7"></div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/da.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col"> KOUMI NGOH</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Simon</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Maitre de Conférences </div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Directeur Adjoint</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:da@estlc.unv-ebolowa.cm">da@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                </div>
                <div class="row mb-4 mt-1">
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/crep.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">MOUZONG PEMI  </div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Marcelin</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Maitre de Conférences </div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Centre de Recherche, d’Expérimentation et de Production </div></div>
                    <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:crep@estlc.unv-ebolowa.cm">crep@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/lankoul.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col"> LANGOUL  </div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">FRANCIS</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'enseignement Général</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Centre de Documentation et des Archives</div></div>
                    <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:cda@estlc.unv-ebolowa.cm">cda@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/cisi.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">KEUDEM ZONING</div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Steve</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'enseignement Général</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Cellule Informatique et Des Systèmes D'information</div></div>
                    <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:cisi@estlc.unv-ebolowa.cm">cisi@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/socas.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">DANADAM</div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Flavien</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Conseiller Principal d'Orientation Scolaire </div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef service de L'Orientation- Conseil et de L'Action Sociale</div></div>
                    <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:socas@estlc.unv-ebolowa.cm">socas@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("share/img/estlc_sans_fond.png")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">MFOM</div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">GUY DEROSIER</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Collèges d'Enseignements Secondaire Général</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef service du Courier et des Relations Publiques</div></div>
                    <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:scrp@estlc.unv-ebolowa.cm">scrp@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
            </div>
            <div class="row">
                <h2> Division Des Affaires Académiques, de la Recherche et de la Coopération</h2>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/daarc.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">ONANA ESSAMA </div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Bedel Giscard</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargé de Cours</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Division Des Affaires Académiques, de la Recherche et de la Coopération</div></div>
                    <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:daarc@estlc.unv-ebolowa.cm">daarc@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>

                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/mbiam.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">MBIAM</div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col"> Salomon Parfait</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col"> Professeur des lycées d'Enseignement Technique et Professionnel </div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Service des enseignements et de l'évaluation</div></div>
                    <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:see@estlc.unv-ebolowa.cm"> see@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>

                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/nana.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col"> NGAPOUT NANA </div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">FADIMATOU</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col"> CPOSUP ( Conseiller Principal d'Orientation Scolaire Universitaire et Professionnel)</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service des Diplômes et de la Certification</div></div>
                    <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:sdc@estlc.unv-ebolowa.cm">sdc@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237) 696918207</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/azong.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col"> AZONG TCHITILE </div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Emmanuel Wilfried</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Assistant</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service du Personnel Enseignant</div></div>
                    <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:spe@estlc.unv-ebolowa.cm">spe@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/mvogo.png")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">MVOGO AHANDA</div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Joseph Jean Baptiste</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargé de Cours</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef service de la Recherche et de la Coopération  </div></div>
                    <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:src@estlc.unv-ebolowa.cm">src@estlc.unv-ebolowa.cm</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                </div>
                <div class="personnel col-sm-2">
                    <img src="{{asset("sige_app/frontend/img/team/abena.jpg")}}" alt=" Image du personnel">
                    <div class="row"><div class="col-4">Noms:</div><div class="col">ABENA</div></div>
                    <div class="row"><div class="col-4">Prénoms:</div><div class="col">Michel Arnaud</div></div>
                    <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'Enseignement Général</div></div>
                    <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de la Qualité et des Normes</div></div>
                    <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:abenamcjoss2@gmail.com ">abenamcjoss2@gmail.com</a></div> </div>
                    <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)  655537927</div></div>
                </div>
            </div>
                <div class="row">
                    <h2>Division de la Scolarité et du Suivi des Etudiants</h2>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/dsse.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">EDOU ESSEKO</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Martin Brice</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Assistant</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Division de la Scolarité et Du Suivi des Etudiants</div></div>
                        <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:dsse@estlc.unv-ebolowa.cm">dsse@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/djomo.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">DJOMO ONDO </div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Edmond Aimé</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Cadre Contractuel</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de la Scolarité et des Statiques </div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:sss@estlc.unv-ebolowa.cm">sss@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/assoumou.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">ASSOUMOU EMVO</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Jackson</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col"> Conseiller Principal d'Orientation </div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service des Stages et de L'Insertion Professionnelle</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:ssip@estlc.unv-ebolowa.cm">ssip@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                </div>
                <div class="row">
                    <h2>Division des Affaires Administratives et Financières</h2>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("share/img/estlc_sans_fond.png")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">NTYAM ASSE</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Georges</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'Enseignement Général</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Division des Affaires Administratives et Financières</div></div>
                        <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:daaf@estlc.unv-ebolowa.cm">daaf@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/ebolo.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col"> EBOLO</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Pierre Arnold</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col"></div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Services des Affaires Financiers</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:saf@estlc.unv-ebolowa.cm">saf@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/nanga.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">NANGA ETOA</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Mireille Lorine </div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'Enseignement Technique et Professionnel</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de l'Administration Général et du Personnel non Enseignant</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto::sagpne@estlc.unv-ebolowa.cm">sagpne@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237) 691289082</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/manga.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">ETEME MANGA</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Cédric Wilfried</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'Enseignement Général</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de la Maintenance et du Matériel</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:smm@estlc.unv-ebolowa.cm">smm@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/paki.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">PAKI</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Hervé</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées d'Enseignement Général</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de l'Animation Sportive et Culturelle</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:sasc@estlc.unv-ebolowa.cm">sasc@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                </div>
                <div class="row">
                    <h2>Division de la Formation Continue et à distance</h2>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/dfcd.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">MVONDO Didier</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col"> Serge</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'Enseignement Secondaire Général</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef De Division de la Formation Continue et A distance</div></div>
                        <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:dfcd@estlc.unv-ebolowa.cm">dfcd@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/sfc.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">DJIEME EWOLE</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col"> OMER LEGRAND</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">PROFESSEUR ADJOINT DES ÉCOLES NORMALE D'INSTITUTEURS</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de la Formation Continue </div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:sfc@estlc.unv-ebolowa.cm">sfc@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237) 699219769</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/sfoad.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">NKONJOH NGOMADE</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Armel</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Professeur des Lycées D'Enseignement Technique et Professionnelle</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef Service de la Formation à Distance</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:sfd@estlc.unv-ebolowa.cm">sfd@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                </div>

                <div class="row">
                    <h2>Nos Départements</h2>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/nballa.png")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">MBALLA ELOUNDOU</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Aimé Christel</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département des Enseignements Généraux </div></div>
                        <div class="row"><div class="col-3">E-mail:</div><div class="col"><a href="mailto:depteg@estlc.unv-ebolowa.cm">depteg@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2" >
                        <img src="{{asset("sige_app/frontend/img/team/mboussi.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">MBOUSSI</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col"> Serge Bertrand</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département des Enseignements Scientifiques de Base </div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptesb@estlc.unv-ebolowa.cm">deptesb@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/dgtp.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">DIBOMA Benjamin</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Salomon</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département Génie des Transports </div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptgt@estlc.unv-ebolowa.cm">deptgt@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2" >
                        <img src="{{asset("sige_app/frontend/img/team/sapi.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">SAPNKEN</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">FLAVIAN EMMANUEL</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département Génie Logistique</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptgl@estlc.unv-ebolowa.cm">deptgl@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2" >
                        <img src="{{asset("sige_app/frontend/img/team/dgmc.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">KIBONG</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Marius Tony</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département Génie Mécatronique </div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptgm@estlc.unv-ebolowa.cm">deptgm@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2" >
                        <img src="{{asset("sige_app/frontend/img/team/messi.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">MESSI NGUELE</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Thomas</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département Génie Informatique</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptgi@estlc.unv-ebolowa.cm">deptgi@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/belinga.jpg")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">BELINGA BESSALA</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Jacob Patrick</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département E-Commerce</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptec@estlc.unv-ebolowa.cm">deptec@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>

                    <div class="personnel col-sm-2">
                        <img src="{{asset("sige_app/frontend/img/team/dro.png")}}" alt=" Image du personnel">
                        <div class="row"><div class="col-4">Noms:</div><div class="col">KENMOE SIYOU</div></div>
                        <div class="row"><div class="col-4">Prénoms:</div><div class="col">Romuald Noel</div></div>
                        <div class="row"><div class="col-4">Grade:</div> <div class="col">Chargés de Cours</div></div>
                        <div class="row"><div class="col-4">Fonction:</div><div class="col">Chef de Département de Recherche Opérationnelle</div></div>
                        <div class="row"><div class="col-4">E-mail:</div><div class="col"><a href="mailto:deptro@estlc.unv-ebolowa.cm">deptro@estlc.unv-ebolowa.cm</a></div> </div>
                        <div class="row"><div class="col-4">Tel:</div><div class="col">(+237)</div></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

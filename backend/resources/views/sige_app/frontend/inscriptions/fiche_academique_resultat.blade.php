@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
    <div class="container mt-3">
        <div class="card card-form">
            <div class="card-header p-1 pt-2" style="background-color: green; color:white;">
                <h3>Inscription Terminée</h3>
            </div>
          <div class="card-body" style="background-color: rgb(245,245,249);">
            <div class="alert alert-success">
                Félicitations!!!!!. Votre inscription académiquue s'est déroulée avec success. Nous vous invitons
                maintenant à cliquer sur le lien suivant afin de télécharger votre fiche d'inscription
                et vos quitus
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-2 bloc-telecharger m-auto">
                    <p style="text-align: center;">Fiche d'Inscription académique</p>
                    <a href="/academique_download/{{$res->code_ins}}" target="blank"> Télécharger <i class="ri-arrow-down-circle-fill"></i></a>
                </div>
            </div>
            <hr>
            <div class="alert alert-success">
                En cas de perte de fiche , vous pouvez la retélécharger sur cette plateforme en vous
                connectant simplement à votre espace.
            </div>
          </div>
        </div>
    </div>
@endsection

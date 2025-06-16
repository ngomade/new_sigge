@extends("concours.frontend.template.concours")
@section("content")
<div class="card card-result p-0" style="margin-top: 12%; margin-bottom: 10%" data-aos="zoom-in" data-aos-delay="80">
    <div class="row g-0 p-0">
      <div class="col-md-12 p-0">
        <div class="card-header bg-success row p-1 m-0" style="color: white">
            <h5 class="card-title col-sm-11 m-0 p-0">Enregistrement réussie. </h5>
            <h3 class="col-sm-1"><i class="ri-checkbox-circle-fill"></i></h3>
        </div>
        <div class="card-body p-3 mt-1 row">
            <div class="col-sm-9" style="border-right: 1px double rgb(223, 223, 223)">
                <p class="card-text mb-1 text-success" style="font-size: 1.11em;">Chèr(e) <b>{{$res->ca_prenom}} {{$res->ca_nom}}</b>, Votre processus d'inscription s'est déroulé avec success.</p>
                <p class="card-text mb-2" style="font-size: 1.11em;">En cliquant sur le bouton suivant, vous téléchargez votre fiche d'inscription.</p>
                <p class="card-text mb-2" style="font-size: 1.11em;">Consignes: </p>
                <ol class="text-primary text-justify" style="font-style: italic;">
                    <li>Bien vouloir imprimer la fiche d'inscription en couleur sous peine d'un rejet de candidature;</li>
                    <li>Faire timbrer la fiche et la signer avant tout dépôt;</li>
                    <li>Bien vouloir enregistrer ces informations et les conserver de manière confidentielle;</li>
                    <li>En cas de perte de cette fiche, bien vouloir vous connecter sur cette plateforme avec vos identifiants.</li>
                </ol>
                <hr>
                <div class="p-auto w-30 rounded">
                    <a  href="/impression/{{$res->ca_code}}" target="_blank" rel="noopener noreferrer"><img src="{{asset('frontend/img/download_pdf.png')}}" alt="Télécharger" class="rounded"  id="download" title="Télécharger votre fiche d'inscription"></a>
                </div>
            </div>
            <div class="col-sm-3">
                    <div class="card mt-2" >
                        <div class="card-header h6 bg-success" style="color: white;">Informations utiles</div>
                        <div class="card-body">
                            <p class="text-danger" style="font-size: 0.8em; text-align: center; font-family: Arial Narrow;">Code et mot de passe à usage unique et à ne divulguer à personne!!!</p>
                            <h6 class="mb-2 mt-3">Code: <span class="text-primary h4" style="font-family: Arial Narrow;" > {{$res->ca_code}}</span></h6>
                            <h6 class="mb-4 mt-3">Password: <span class="text-primary h4" style="font-family: Arial Narrow;" > {{$res->ca_pwd}}</span></h6>
                        </div>
                    </div>
                    <button class="btn btn-outline-success m-5 mt-3 mb-1" data-bs-toggle="modal" data-bs-target="#connexionModal">Connexion</button>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection

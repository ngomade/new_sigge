@extends("sige_app.frontend.template.frontend")
@section("content")
    <div class="card"  id="content-document">
      <div class="card-header  bg-success" style="color: white; font-size: 1.2em;">
        Resumé des parcours académiques au sein de l'ESTLC
        <a href="/download/{{"cursus_".getdate()['year']}}" class="btn btn-success" title="télécharger en pdf" style="float: right; background-color: white; color: black;">Télécharger en PDF </a>
      </div>
      <div class="card-body">
        <img src="{{asset('sige_app/frontend/img/cursus.png')}}" alt="Cursus academique de l'ESTLC">
      </div>
      <div id="card-footer" style=" display: flex; justify-content: right;">
        <a href="/download/{{"cursus_".getdate()['year']}}" class="btn btn-success" title="télécharger en pdf">Télécharger en PDF </a>
      </div>
    </div>
@endsection

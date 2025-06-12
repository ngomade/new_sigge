@extends("sige_app.backend.template.backend")
@section("content")
<div class="card" style="width: 100%; margin:auto;">

    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Nos Actualités</h2>
        <a href="/index_actualite" class="btn btn-primary" style="font-size: 1.08em;" >Ajouter &nbsp; <i class="ri-add-circle-fill"></i></a>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>N°</th>
                  <th>Code Actu</th>
                  <th>Titre Actu</th>
                  <th>Vues</th>
                  <th>Ressources</th>
                  <th>Auteur</th>
                  <th>Date de publication</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($actus as $actualite)
                <?php $personnel =  \App\Models\Personnel::where("code_pers", $actualite->code_pers)->first();?>
                <tr>
                    <td> {{$loop->index+1}}  </td>
                    <td> {{$actualite->actu_code}}  </td>
                    <td title="{{$actualite->actu_title}}"> {{\Str::substr($actualite->actu_title, 0, 50)}} ...  </td>
                    <td> {{$actualite->actu_nb_views}}  </td>
                    <td> {{\App\Models\RessourceActu::where("actu_code", $actualite->actu_code)->count()}}  </td>
                    <td> {{$personnel->nom_pers}}  {{$personnel->prenom_user}} </td>
                    <td> {{$actualite->created_at->format("d/m/Y H:i:s")}}  </td>
                    <td style="text-align: center;">
                        <a href="/delete_actu/{{$actualite->actu_code}}" class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
           <div style="align-self: flex-end; text-align: right !important;  ">
                {{ $actus->links("pagination::bootstrap-4") }}
           </div>
          </div>
    </div>
  </div>
@endsection

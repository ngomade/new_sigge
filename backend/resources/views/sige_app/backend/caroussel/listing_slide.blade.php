@extends("sige_app.backend.template.backend")
@section("content")
<div class="card" style="width: 100%; margin:auto;">

    <div class="card-header" style="text-align: right;">
        <h2 style="float: left;">Nos Slides</h2>
        <a href="/index_caroussel" class="btn btn-primary" style="font-size: 1.08em;" >Ajouter &nbsp; <i class="ri-add-circle-fill"></i></a>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>N°</th>
                  <th>Titre 1</th>
                  <th>Titre 2</th>
                  <th>Auteur</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($slides as $slide)
                <?php $personnel =  \App\Models\Personnel::where("code_pers", $slide->code_pers)->first();?>
                <tr>
                    <td> {{$loop->index+1}}  </td>
                    {{-- <td> {{$slide->id}}  </td> --}}
                    <td title="{{$slide->first_title}}"> {{\Str::substr($slide->first_title, 0, 40)}} ...  </td>
                    <td title="{{$slide->second_title}}"> {{\Str::substr($slide->second_title, 0, 40)}} ...  </td>
                    <td> {{$personnel->nom_pers}}  {{$personnel->prenom_pers}} </td>
                    <td style="text-align: center;">
                        <a href="/delete_slide/{{$slide->id}}" class="btn-outline-danger rounded p-1"><i class='bx bx-x-circle'></i> </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
           <div style="align-self: flex-end; text-align: right !important;  ">
                {{ $slides->links("pagination::bootstrap-4") }}
           </div>
          </div>
    </div>
  </div>
@endsection

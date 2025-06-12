@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 m-auto" >
            <div class="card m-3 ">
                <div class="card-header text-center">
                    <h4>{{$laboratoire->label_labo}} ({{$laboratoire->code_lab}})</h4>
                </div>
                <div class="card-body p-5" style="text-align: justify; line-height:30px;">
                    {!! $laboratoire->desc_labo !!}
                </div>
                <div>
                    <h4 class="m-2 p-2 mb-1 text-center bg-success text-white rounded">Nos différents projets de recherche </h4>
                    <div id="accordion">
                        @foreach ($laboratoire->projets as $projet_labo )
                        <div class="card m-3">
                            <div class="card-header" >
                              <a data-bs-toggle="collapse" href="#collapse{{$loop->index}}" title="Cliquez pour voir la description">
                                <h5 style="font-family: 'Arial Narrow';">Projet {{$loop->index+1}} {{$projet_labo->theme_projet }}</h5>
                              </a>
                            </div>
                            <div id="collapse{{$loop->index}}" class="collapse" data-bs-parent="#accordion">
                              <div class="card-body" style="text-align: justify;">
                                {!! $projet_labo->description_projet!!}
                              </div>
                            </div>
                          </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
 @endsection

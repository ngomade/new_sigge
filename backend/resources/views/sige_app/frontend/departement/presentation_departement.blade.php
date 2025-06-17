@extends("sige_app.frontend.template.frontend")
@section("style")
    <style>
        #main-content {
            width: 80%;
            margin-top: 2%;
            height: 100%;
        }

        h2 {
            border-bottom: 1px solid green;
            text-align: center;
            background-color: rgb(81, 200, 129);
            border-radius: 20px 20px 0px 0px;
            color: white;
        }

        .bloc-text {
            box-shadow: 2px 3px 2px rgb(175, 207, 224);
        }

        .bordered {
            box-shadow: 3px 3px rgb(175, 207, 224);
            border: 1px solid rgb(211, 209, 209);
            border-radius: 10px 10px 0px 0px;
        }

        @media screen and (min-width: 768px) {
            .grille {
                column-count: 2;
            }
        }
    </style>
@endsection
@section('content')
    <div class="card w-5 m-auto mt-4" id="main-content">
        <div class="card-header  bg-success" style="color: white; font-size: 1.2em;">
            Département de {{$bureau->label_bureau}} ({{$bureau->code_bureau}})
        </div>
        <div class="card-body"
             style="justify-content: center; border:1px solid rgb(194, 193, 193); box-shadow:1px 2px 2px rgb(175, 207, 224); height: 100%;">
            <div class="row m-auto" style="height: 100%;">
                <div class="col-sm-2">
                    <img
                        src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."departements".DIRECTORY_SEPARATOR.$bureau->code_bureau.DIRECTORY_SEPARATOR.$presentation->photo_chef)}}'
                        alt="Photo du chef de département" title="Photo du chef de département"
                        style="width:100%; height:85%;">
                    <div style="text-align: center; font-weight: bold;">
                        {{$presentation->nom_chef}}
                    </div>
                </div>
                <div class="col-sm-10 bordered" style="text-align: justify; height: 7cm; overflow: auto;">
                    {!! $presentation->message_chef !!}
                </div>
            </div>
            <h2 class="mt-2"> Cursus Ingenieur</h2>
            <div class="row m-auto p-1 bordered">
                <h4 style="border-bottom: 1px solid gray; text-align: center;">Filières du Cursus Ingenieur</h4>
                <div class="col-sm-8">
                    <div style="height: 13cm; overflow: scroll; auto;" class="bloc-text">
                        {!! $presentation->cursus_ing !!}
                    </div>
                </div>
                <div class="col-sm-4 m-auto justify-content-center" style="text-align: justify;">
                    <div id="carouselExample" class="carousel slide justify-content-center"
                         style="width:100%; margin: auto;">
                        <div class="carousel-inner">
                            @foreach (\App\Models\notes\Document::where("code_bureau",$bureau->code_bureau)->where("nom_fichier","LIKE", '%ingenieur%')->get() as $document)
                                @if ($loop->index == 0)
                                    <div class="carousel-item active">
                                        <img
                                            src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."departements".DIRECTORY_SEPARATOR.$bureau->code_bureau.DIRECTORY_SEPARATOR.$document->nom_fichier)}}'
                                            class="d-block w-100">
                                    </div>
                                @else
                                    <div class="carousel-item">
                                        <img
                                            src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."departements".DIRECTORY_SEPARATOR.$bureau->code_bureau.DIRECTORY_SEPARATOR.$document->nom_fichier)}}'
                                            class="d-block w-100">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div style="text-align: center;" class="mt-2">
                        <a href="/download_grille/{{$bureau->code_bureau}}/depliant_ingenieur"
                           class="btn btn-outline-success"> Télécharger</a>
                    </div>
                </div>
            </div>
            <div class="bordered">
                <h3 style="border-bottom: 1px solid gray;">Grilles des programmes du Cursus Ingenieur</h3>
                <div style="height: 12cm; overflow: scroll;" class="bloc-text grille">
                    {!! $presentation->grille_ing !!}
                </div>
            </div>
            {{-- <h2 class="mt-2"> Cursus Science de L'ingénieur</h2>
            <h3 style="border-bottom: 1px solid gray; font-size: 1.15em;">Filières du Cursus Ingenieur</h3>
            <div class="row m-auto">
                <div class="col-sm-8 m-auto">
                    <div style="height: 13cm; overflow: auto;" class="bloc-text">
                        {!! $presentation->science_ing !!}
                    </div>
                </div>
                <div class="col-sm-4 m-auto" style="text-align: justify;" style="width:100%; height:90%;">
                    <div id="carouselExample" class="carousel slide " style="width:100%; height:90%;">
                        <div class="carousel-inner">
                            @foreach (\App\Models\Document::where("code_bureau",$bureau->code_bureau)->where("nom_fichier","LIKE", '%ingenieur%')->get() as $document)
                            @if ($loop->index == 0)
                                <div class="carousel-item active">
                                    <img src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."departements".DIRECTORY_SEPARATOR.$bureau->code_bureau.DIRECTORY_SEPARATOR.$document->nom_fichier)}}' class="d-block w-100">
                                </div>
                            @else
                                <div class="carousel-item">
                                    <img src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."departements".DIRECTORY_SEPARATOR.$bureau->code_bureau.DIRECTORY_SEPARATOR.$document->nom_fichier)}}' class="d-block w-100" style="height:90%;">
                                </div>
                            @endif
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div style="text-align: center;" class="mt-2">
                        <a href="/download_grille/{{$bureau->code_bureau}}/depliant_science" class="btn btn-outline-success"> Télécharger</a>
                    </div>
                </div>
            </div>
            <div>
                <h3 style="border-bottom: 1px solid gray;">Grilles des programmes du Cursus  Science de l'Ingenieur</h3>
                <div style="height: 12cm; overflow: scroll;" class="bloc-text grille">
                    {!! $presentation->grille_science !!}
                </div>
            </div>--}}
        </div>
    </div>
@endsection

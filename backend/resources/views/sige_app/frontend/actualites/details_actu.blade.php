@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
    <div class="container mt-3">
        <div class="card card-form">
            <div class="card-header p-2 pt-2" style="text-align: justify;">
                <h3 style="font-family: Aptos Display; font-size: 2em;"> {{$actualite->actu_title}} </h3>
            </div>
            <?php
                $d = $actualite->created_at->format("Y-m-d-H-i");

            ?>
          <div class="card-body" style="background-color: rgb(245,245,249);">
                <div id="carouselExample" class="carousel slide " style="width:60%; margin: auto;">
                    <div class="carousel-inner">
                        @foreach (\App\Models\RessourceActu::where("actu_code", $actualite->actu_code)->get() as $r)
                        @if ($loop->index == 0)
                            <div class="carousel-item active">
                                <img src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$actualite->actu_code.DIRECTORY_SEPARATOR.$r->r_name)}}' class="d-block w-100 h-90">
                            </div>
                        @else
                            <div class="carousel-item">
                                <img src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$actualite->actu_code.DIRECTORY_SEPARATOR.$r->r_name)}}' class="d-block w-100 h-90">
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
                <div class="actu_content" style="padding: 3%; text-align: justify;">
                    {!! $actualite->actu_content !!}
                </div>
          </div>
          <div class="card-footer" style="text-align: right; font-style: italic; ">
            <div style="float: left;">Publié le {{$actualite->created_at->format("d/m/Y")}} à {{$actualite->created_at->format("H:i:s")}}</div>
            <div class="read-more" style="float: right;"><a href="/all_actu" class="btn btn-outline-info"> Toutes nos actualités</i></a></div>
          </div>

        </div>

        <section id="more-services" class="more-services">
            <div class="container">
                <div class="section-title" data-aos="fade-up">
                    <h2>Activités récentes</h2>
                    <p>Découvrez la vie de l'école à travers nos articles d'actualités </p>
                </div>
                <div class="row">
                    @foreach (\App\Models\Actualite::orderBy("created_at", "desc")->take(6)->get() as $actu)
                    <?php
                        $res = \App\Models\RessourceActu::where("actu_code", $actu->actu_code)->first();
                        $src = "";
                        if($res){
                            $d = $actu->created_at->format("Y-m-d-H-i");
                            $src =storage_path().DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$d.DIRECTORY_SEPARATOR.$res->r_name;
                        }
                    ?>
                    <div class="col-md-4 d-flex align-items-stretch mt-3">
                        <div class="card"
                            style='background-image: url("{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$actu->actu_code.DIRECTORY_SEPARATOR.$res->r_name)}}");'

                            data-aos="fade-up" data-aos-delay="100">
                            <div class="card-body">
                                <div class="card-title" style="text-align: justify; font-weight: lighter;">{{$actu->actu_title}}</div>
                                <!--<p class="card-text"> {$actu->actu_content} </p>-->
                            </div>
                            <div class="card-footer" style="background-color: white; font-size: 0.9em;">
                                <div style="float: left;">Publié le {{$actu->created_at->format("d/m/Y")}} à {{$actu->created_at->format("H:i:s")}} </div>
                                <div class="read-more" style="float: right;"><a href="/details_actu/{{$actu->actu_code}}" class="btn btn-outline-info"> Lire plus <i class="bi bi-arrow-right"></i></a></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="mt-3" style="text-align: right;">
                        <a href="/all_actu" class="btn btn-info" > <i class='bx bx-list-ul'></i> Toutes nos actualités </a>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

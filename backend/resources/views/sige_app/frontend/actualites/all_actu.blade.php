@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
    <div class="container mt-3">
        <div class="card">
            <div class="card-header p-2 pt-2" style="text-align: justify;">
                <div style="float: left;" class="h3">Nos Actualités</div>
                <div style="float: right;">
                    <div class="input-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Filtrer par ici"
                          aria-label="mot clés"
                          aria-describedby="basic-addon1"
                        />
                        <span class="input-group-text" id="basic-addon1"> <i class='bx bx-search'></i></span>
                      </div>
                </div>
            </div>
        </div>
        <section id="more-services" class="more-services">
            <div class="container">
                <div class="row">
                    @foreach ($actus as $actu)
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
                            style="background-image: url('{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."actualites".DIRECTORY_SEPARATOR.$actu->actu_code.DIRECTORY_SEPARATOR.$res->r_name)}}');"
                            data-aos="fade-up" data-aos-delay="100">
                            <div class="card-body">
                                <div class="card-title" style="text-align: justify; font-weight: lighter;">{{$actu->actu_title}}</div>
                                <!--<p class="card-text"> {$actu->actu_content } </p>-->
                            </div>
                            <div class="card-footer" style="background-color: white; font-size: 0.9em;">
                                <div style="float: left;">Publié le {{$actu->created_at->format("d/m/Y")}} à {{$actu->created_at->format("H:i:s")}} </div>
                                <div class="read-more" style="float: right;"><a href="details_actu/{{$actu->actu_code}}" class="btn btn-outline-info"> Lire plus <i class="bi bi-arrow-right"></i></a></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="mt-3" style="text-align: right;">
                        {{ $actus->links("pagination::bootstrap-4") }}
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@extends("sige_app.frontend.template.frontend")
@section("style")
    <style>
        .dept-section {
            background: var(--app-bg, #f7faf8);
            padding: 3rem 0 4rem;
        }

        .dept-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .dept-header .badge-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--app-primary-soft, #dff2e9);
            color: var(--app-primary, #0e8f74);
            font-size: 26px;
            margin-bottom: 1rem;
        }
        .dept-header h1 {
            color: var(--app-primary-dark, #11583f);
            font-weight: 700;
            font-size: 1.6rem;
            margin: 0;
        }
        .dept-header .code {
            color: var(--app-text-muted, #61756c);
            font-size: 0.95rem;
        }

        .dept-card {
            background: var(--app-surface, #fff);
            border: 1px solid var(--app-border, #dce9e2);
            border-radius: var(--app-radius-lg, 16px);
            box-shadow: 0 8px 24px var(--app-shadow-soft, rgba(17,61,53,.08));
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .dept-chief-photo {
            width: 100%;
            max-width: 220px;
            margin: 0 auto;
        }
        .dept-chief-photo img {
            width: 100%;
            aspect-ratio: 3 / 4;
            object-fit: cover;
            border-radius: var(--app-radius-md, 12px);
            border: 3px solid var(--app-primary-soft, #dff2e9);
            box-shadow: 0 8px 20px var(--app-shadow-soft, rgba(17,61,53,.08));
        }
        .dept-chief-photo .name {
            text-align: center;
            font-weight: 700;
            color: var(--app-primary-dark, #11583f);
            margin-top: 0.75rem;
        }

        .dept-chief-message {
            background: #fafcfb;
            border: 1px solid var(--app-border, #dce9e2);
            border-radius: var(--app-radius-md, 12px);
            padding: 1.25rem 1.5rem;
            text-align: justify;
            max-height: 260px;
            overflow-y: auto;
            color: var(--app-text, #18352b);
            line-height: 1.7;
        }

        .dept-section-tag {
            display: inline-block;
            background: var(--app-primary-soft, #dff2e9);
            color: var(--app-primary-dark, #11583f);
            font-weight: 700;
            letter-spacing: .03em;
            font-size: .85rem;
            padding: .4rem 1.1rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }

        .dept-subblock {
            background: #fafcfb;
            border: 1px solid var(--app-border, #dce9e2);
            border-radius: var(--app-radius-md, 12px);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .dept-subblock h4 {
            color: var(--app-primary-dark, #11583f);
            font-weight: 700;
            font-size: 1.05rem;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--app-border, #dce9e2);
            text-align: center;
        }
        .dept-subblock h3 {
            color: var(--app-primary-dark, #11583f);
            font-weight: 700;
            font-size: 1.1rem;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--app-border, #dce9e2);
        }

        .dept-scroll-box {
            max-height: 340px;
            overflow-y: auto;
            padding-right: 0.5rem;
            text-align: justify;
            line-height: 1.7;
            color: var(--app-text, #18352b);
        }
        .dept-scroll-box.grille {
            max-height: 300px;
        }
        @media screen and (min-width: 768px) {
            .dept-scroll-box.grille {
                column-count: 2;
                column-gap: 2rem;
            }
        }

        .dept-carousel {
            border-radius: var(--app-radius-md, 12px);
            overflow: hidden;
            border: 1px solid var(--app-border, #dce9e2);
            box-shadow: 0 6px 16px var(--app-shadow-soft, rgba(17,61,53,.08));
        }
        .dept-carousel img {
            width: 100%;
            display: block;
            object-fit: cover;
        }
        .dept-download {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
@endsection
@section('content')
    <div class="dept-section">
        <div class="container">

            <div class="dept-header">
                <div class="badge-icon">
                    <i class="bi bi-building-fill"></i>
                </div>
                <h1>Département de {{$bureau->label_bureau}}</h1>
                <div class="code">({{$bureau->code_bureau}})</div>
            </div>

            <div class="dept-card">
                <div class="row g-4 align-items-start">
                    <div class="col-md-3">
                        <div class="dept-chief-photo">
                            <img
                                src='{{asset("storage".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."departements".DIRECTORY_SEPARATOR.$bureau->code_bureau.DIRECTORY_SEPARATOR.$presentation->photo_chef)}}'
                                alt="Photo du chef de département" title="Photo du chef de département">
                            <div class="name">{{$presentation->nom_chef}}</div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="dept-chief-message">
                            {!! $presentation->message_chef !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="dept-card">
                <span class="dept-section-tag">CURSUS INGÉNIEUR</span>

                <div class="dept-subblock">
                    <h4>Filières du Cursus Ingénieur</h4>
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="dept-scroll-box">
                                {!! $presentation->cursus_ing !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="carouselExample" class="carousel slide dept-carousel">
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
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="dept-download">
                                <a href="/download_grille/{{$bureau->code_bureau}}/depliant_ingenieur" class="btn btn-outline rounded-pill px-4">
                                    <i class="bi bi-download me-1"></i> Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dept-subblock mb-0">
                    <h3>Grilles des programmes du Cursus Ingénieur</h3>
                    <div class="dept-scroll-box grille">
                        {!! $presentation->grille_ing !!}
                    </div>
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
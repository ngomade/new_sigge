@extends("sige_app.frontend.template.frontend")

@section('js')

@endsection

@section('style')
<style>
    .mairie-presentation {
        background: var(--app-bg, #f7faf8);
        padding: 3rem 0 4rem;
    }

    .mairie-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .mairie-header .badge-icon {
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
    .mairie-header h1 {
        color: var(--app-primary-dark, #11583f);
        font-weight: 700;
        font-size: 1.7rem;
        margin-bottom: 0.5rem;
    }
    .mairie-header p {
        color: var(--app-text-muted, #61756c);
        max-width: 560px;
        margin: 0 auto;
    }

    .info-card {
        background: var(--app-surface, #fff);
        border: 1px solid var(--app-border, #dce9e2);
        border-radius: var(--app-radius-lg, 16px);
        box-shadow: 0 8px 24px var(--app-shadow-soft, rgba(17,61,53,.08));
        padding: 1.75rem;
        height: 100%;
    }
    .info-card h2 {
        color: var(--app-primary-dark, #11583f);
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .info-card p {
        color: var(--app-text, #18352b);
        line-height: 1.8;
        text-align: justify;
        margin-bottom: 0.75rem;
    }
    .info-card hr {
        border-color: var(--app-border, #dce9e2);
        margin: 1.25rem 0;
    }
    .info-card ul {
        padding-left: 1.1rem;
        margin: 0;
        color: var(--app-text, #18352b);
        line-height: 1.8;
    }
    .info-card ul li { margin-bottom: 0.5rem; }

    .esplanade-card img {
        width: 100%;
        height: 260px;
        object-fit: cover;
        border-radius: var(--app-radius-md, 12px);
        border: 1px solid var(--app-border, #dce9e2);
    }

    /* Photothèque */
    .photo-section { padding-top: 3rem; }
    .photo-section .section-title { text-align: center; margin-bottom: 2rem; }
    .photo-section .section-title h2 {
        color: var(--app-primary-dark, #11583f);
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 0.4rem;
    }
    .photo-section .section-title p { color: var(--app-text-muted, #61756c); }

    #portfolio-flters {
        list-style: none;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        padding: 0;
        margin: 0 0 2rem;
    }
    #portfolio-flters li {
        cursor: pointer;
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        background: var(--app-surface, #fff);
        border: 1px solid var(--app-border, #dce9e2);
        color: var(--app-text-muted, #61756c);
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 200ms ease;
    }
    #portfolio-flters li:hover {
        color: var(--app-primary, #0e8f74);
        border-color: var(--app-primary, #0e8f74);
    }
    #portfolio-flters li.filter-active {
        background: var(--app-primary, #0e8f74);
        border-color: var(--app-primary, #0e8f74);
        color: #fff;
    }

    .portfolio-wrap {
        position: relative;
        border-radius: var(--app-radius-md, 12px);
        overflow: hidden;
        box-shadow: 0 8px 20px var(--app-shadow-soft, rgba(17,61,53,.08));
        border: 1px solid var(--app-border, #dce9e2);
        margin-bottom: 1.5rem;
    }
    .portfolio-wrap img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        display: block;
        transition: transform 300ms ease;
    }
    .portfolio-wrap:hover img { transform: scale(1.05); }
    .portfolio-info {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(17,61,53,0) 40%, rgba(17,61,53,.85) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1rem;
        opacity: 0;
        transition: opacity 250ms ease;
    }
    .portfolio-wrap:hover .portfolio-info { opacity: 1; }
    .portfolio-info h4 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0; }
    .portfolio-info p { color: rgba(255,255,255,.85); font-size: 0.8rem; margin: 0 0 0.5rem; }
    .portfolio-links { display: flex; gap: 0.5rem; }
    .portfolio-links a {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255,255,255,.15);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 200ms ease;
    }
    .portfolio-links a:hover { background: var(--app-primary, #0e8f74); }
</style>
@endsection

@section('content')
    <div class="mairie-presentation">
        <div class="container">

            <div class="mairie-header">
                <div class="badge-icon">
                    <i class="bi bi-bank2"></i>
                </div>
                <h1>Mairie d'Ambam : Présentation</h1>
                <p>Historique, couverture géographique et cadre de vie de la commune d'Ambam.</p>
            </div>

            <div class="row g-4 mb-3">
                <div class="col-lg-6">
                    <div class="info-card">
                        <h2><i class="bi bi-info-circle-fill"></i> Fiche d'identité</h2>
                        <p><strong>Date de création :</strong> 1952</p>
                        <p><strong>Couverture géographique :</strong> La Commune d'Ambam partage l'espace territorial de l'Arrondissement du même nom, créé comme subdivision en 1921. Elle est composée de 86 villages et sa superficie est de 2 798 Km².</p>
                        <hr>
                        <p><strong>Bref historique :</strong></p>
                        <ul>
                            <li><strong>2004</strong> : Devient Commune d'Ambam avec la loi N° 2004/018 du 22 juillet 2004.</li>
                            <li><strong>1974</strong> : Devient Commune Rurale d'Ambam à la faveur de la loi N° 74/23 du 05 décembre 1974.</li>
                            <li><strong>1952</strong> : Création de la Commune Mixte Rurale d'Ambam par arrêté N° 523 du 21 août 1952.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="info-card esplanade-card">
                        <h2><i class="bi bi-image-fill"></i> Esplanade de la Mairie</h2>
                        <img src="{{asset('sige_app/frontend/img/mairie/photo_12.jpg')}}" alt="Esplanade de la Mairie d'Ambam">
                    </div>
                </div>
            </div>

            <section id="portfolio" class="photo-section">
                <div class="section-title">
                    <h2>Photothèque</h2>
                    <p>Quelques clichés de nos locaux.</p>
                </div>

                <ul id="portfolio-flters">
                    <li data-filter="*" class="filter-active">Tout</li>
                    <li data-filter=".filter-act">Esplanade</li>
                    <li data-filter=".filter-ass">Nos Bureaux</li>
                    <li data-filter=".filter-mem">Divers</li>
                </ul>

                <div class="row portfolio-container">

                    <div class="col-lg-4 col-md-6 portfolio-item filter-act">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_12.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Notre Esplanade</h4>
                                <p>Esplanade</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_12.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Notre Esplanade"><i class="bx bx-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-act">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_11.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Notre Esplanade</h4>
                                <p>Esplanade</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_11.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Notre Esplanade"><i class="bx bx-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-act">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_13.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Notre Esplanade</h4>
                                <p>Esplanade</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_13.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Notre Esplanade"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_2.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Nos Bureaux</h4>
                                <p>Bureau</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_2.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_6.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Nos Bureaux</h4>
                                <p>Bureau</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_6.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_7.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Nos Bureaux</h4>
                                <p>Bureau</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_7.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_8.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Nos Bureaux</h4>
                                <p>Bureau</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_8.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_9.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Nos Bureaux</h4>
                                <p>Bureau</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_9.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_1.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Divers</h4>
                                <p>Divers</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_1.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_3.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Divers</h4>
                                <p>Divers</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_3.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
                        <div class="portfolio-wrap">
                            <img src="{{asset("sige_app/frontend/img/mairie/photo_10.jpg")}}" alt="">
                            <div class="portfolio-info">
                                <h4>Divers</h4>
                                <p>Divers</p>
                                <div class="portfolio-links">
                                    <a href="{{asset("sige_app/frontend/img/mairie/photo_10.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                                    <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </div>
@endsection
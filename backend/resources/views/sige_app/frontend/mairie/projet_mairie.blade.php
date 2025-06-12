@extends("sige_app.frontend.template.frontend")
@section('js')

@endsection
@section('content')
    <div class="container mt-3" >
       <div class="row" style="justify-content: center;">
            <div class="card">
                <div class="card-header" style="text-align: justify;">
                    <div style="text-align: center;" class="h3">Mairie d'Ambam: Présentation</div>
                </div>
            </div>
            <div class="card col-md-5 m-3 p-3" style="text-align: justify; line-height: 30px; box-shadow: 2px 2px 2px rgb(86,179, 255);" >
                <p><strong>Date de cr&eacute;ation : 1952</strong></p>
                <p>Couverture g&eacute;ographique :</p>
                <p>La Commune d&rsquo;Ambam partage I&rsquo;espace territorial de I &lsquo;Arrondissement du m&ecirc;me nom qui a &eacute;t&eacute; cr&eacute;&eacute; comme subdivision en 1921. Elle est compos&eacute;e de 86 villages et sa superficie est de 2 798 Km2.</p>
                <hr>
                <p><strong>Bref historique :</strong></p>
                <ul>
                <li><strong>2004</strong> : Devient Commune D&rsquo;Ambam avec la loi N&deg; 2004/018 du 22 juillet 2004.</li>
                <li><strong>1974</strong> : Devient Commune Rurale d'Ambam a la faveur de la loi N&deg; 74/23 du 05 d&eacute;cembre 1974.</li>
                <li><strong>1952</strong> : Cr&eacute;ation de la Commune Mixte Rurale d'Ambam par arr&ecirc;t&eacute; N&deg; 523 du 21 aout 1952.</li>
                </ul>
            </div>
            <div class="card col-md-5 m-3 p-3">
                <h1 style="text-align: center;" class="mb-5 mt-5">Esplanade de la Mairie</h1>
                <img src="{{asset('sige_app/frontend/img/mairie/photo_12.jpg')}}" alt="" width="100%;">
            </div>
       </div>

       <section id="portfolio" class="portfolio">
        <div class="container">

          <div class="section-title" data-aos="fade-up">
            <h2>Photothèque</h2>
            <p>Quelques clichets de nos locaux.</p>
          </div>

          <div class="row" data-aos="fade-up" data-aos-delay="200">
            <div class="col-lg-12 d-flex justify-content-center">
              <ul id="portfolio-flters">
                <li data-filter="*" class="filter-active">Tout</li>
                <li data-filter=".filter-act">Esplanade</li>
                <li data-filter=".filter-ass">Nos Bureaux</li>
                <li data-filter=".filter-mem">Divers</li>
              </ul>
            </div>
          </div>

          <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="400">

            <div class="col-lg-4 col-md-6 portfolio-item filter-act">
              <div class="portfolio-wrap">
                <img src="{{asset("sige_app/frontend/img/mairie/photo_12.jpg")}}" class="img-fluid" alt="">
                <div class="portfolio-info">
                  <h4>Notre Esplanade</h4>
                  <p>Esplanade</p>
                  <div class="portfolio-links">
                    <a href="{{ asset("sige_app/frontend/img/mairie/photo_12.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Notre Esplanade"><i class="bx bx-plus"></i></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-act">
              <div class="portfolio-wrap">
                <img src="{{ asset("sige_app/frontend/img/mairie/photo_11.jpg")}}" class="img-fluid" alt="">
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
                <img src="{{ asset("sige_app/frontend/img/mairie/photo_13.jpg")}}" class="img-fluid" alt="">
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
                <img src="{{asset("sige_app/frontend/img/mairie/photo_2.jpg")}}" class="img-fluid" alt="">
                <div class="portfolio-info">
                    <h4>Nos Bureaux</h4>
                    <p>Bureau</p>
                  <div class="portfolio-links">
                    <a href="{{ asset("sige_app/frontend/img/mairie/photo_2.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                    <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                  </div>
                </div>
              </div>
            </div>

              <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                <div class="portfolio-wrap">
                  <img src="{{ asset("sige_app/frontend/img/mairie/photo_6.jpg")}}" class="img-fluid" alt="">
                  <div class="portfolio-info">
                      <h4>Nos Bureaux</h4>
                      <p>Bureau</p>
                    <div class="portfolio-links">
                      <a href="{{ asset("sige_app/frontend/img/mairie/photo_6.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                      <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                <div class="portfolio-wrap">
                  <img src="{{ asset("sige_app/frontend/img/mairie/photo_7.jpg")}}" class="img-fluid" alt="">
                  <div class="portfolio-info">
                      <h4>Nos Bureaux</h4>
                      <p>Bureau</p>
                    <div class="portfolio-links">
                      <a href="{{ asset("sige_app/frontend/img/mairie/photo_7.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                      <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                <div class="portfolio-wrap">
                  <img src="{{ asset("sige_app/frontend/img/mairie/photo_8.jpg")}}" class="img-fluid" alt="">
                  <div class="portfolio-info">
                      <h4>Nos Bureaux</h4>
                      <p>Bureau</p>
                    <div class="portfolio-links">
                      <a href="{{ asset("sige_app/frontend/img/mairie/photo_8.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                      <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 col-md-6 portfolio-item filter-ass">
                <div class="portfolio-wrap">
                  <img src="{{ asset("sige_app/frontend/img/mairie/photo_9.jpg")}}" class="img-fluid" alt="">
                  <div class="portfolio-info">
                      <h4>Nos Bureaux</h4>
                      <p>Bureau</p>
                    <div class="portfolio-links">
                      <a href="{{ asset("sige_app/frontend/img/mairie/photo_9.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Nos Bureaux"><i class="bx bx-plus"></i></a>
                      <a href="#" title="Savoir plus"><i class="bx bx-link"></i></a>
                    </div>
                  </div>
                </div>
              </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
              <div class="portfolio-wrap">
                <img src="{{ asset("sige_app/frontend/img/mairie/photo_1.jpg")}}" class="img-fluid" alt="">
                <div class="portfolio-info">
                    <h4>Divers</h4>
                    <p>Divers</p>
                  <div class="portfolio-links">
                    <a href="{{ asset("sige_app/frontend/img/mairie/photo_1.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                    <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
                <div class="portfolio-wrap">
                  <img src="{{ asset("sige_app/frontend/img/mairie/photo_3.jpg")}}" class="img-fluid" alt="">
                  <div class="portfolio-info">
                      <h4>Divers</h4>
                      <p>Divers</p>
                    <div class="portfolio-links">
                      <a href="{{ asset("sige_app/frontend/img/mairie/photo_3.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                      <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                    </div>
                  </div>
                </div>
              </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
            <div class="portfolio-wrap">
              <img src="{{ asset("sige_app/frontend/img/mairie/photo_10.jpg")}}" class="img-fluid" alt="">
              <div class="portfolio-info">
                  <h4>Divers</h4>
                  <p>Divers</p>
                <div class="portfolio-links">
                  <a href="{{ asset("sige_app/frontend/img/mairie/photo_10.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                  <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-mem">
            <div class="portfolio-wrap">
              <img src="{{ asset("sige_app/frontend/img/mairie/photo_10.jpg")}}" class="img-fluid" alt="">
              <div class="portfolio-info">
                  <h4>Divers</h4>
                  <p>Divers</p>
                <div class="portfolio-links">
                  <a href="{{ asset("sige_app/frontend/img/mairie/photo_10.jpg")}}" data-gallery="portfolioGallery" class="portfolio-lightbox" title="Divers"><i class="bx bx-plus"></i></a>
                  <a href="#" title="More Details"><i class="bx bx-link"></i></a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </div>
@endsection

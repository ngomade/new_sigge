<div id="main">
    <!-- Section Localisation -->
    <section id="localisation" class="app-section py-4">
        <div class="container">
            <div class="section-title text-center mb-4" data-aos="fade-up">
                <h2>Localisation Ambam</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                    <div id="map" class="card map-card border-0 shadow-sm overflow-hidden rounded-4">
                        <div class="card-body p-0">
                            <iframe title="Localisation ESTLC Ambam" src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d680696.1002018142!2d11.006932526069411!3d3.1073400537897453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x108bcf7a309a7977%3A0x7f54bad35e693c51!2zWWFvdW5kw6k!3m2!1d3.8480325!2d11.5020752!4m5!1s0x1087d5c57e4aa887%3A0x616425f0212f30d5!2sAmbam!3m2!1d2.3815417!2d11.2665498!5e0!3m2!1sfr!2scm!4v1692180500127!5m2!1sfr!2scm" style="border:0;width:100%;height:320px;display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section id="contact" class="app-section app-section-alt py-4">
        <div class="container">
            <div class="section-title text-center mb-4" data-aos="fade-up">
                <h2>Contactez-Nous</h2>
            </div>
            <div class="row justify-content-center g-4 align-items-stretch">
                <!-- À propos -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-about card border-0 shadow-sm h-100 p-4 rounded-4 bg-white text-center d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="fw-bold mb-3">ESTLC</h3>
                            <p class="text-muted small mb-4">ESTLC est une école de cycle ingénieur située à Ambam, dans le Sud-Cameroun. Dotée d'une équipe jeune et dynamique, elle a pour mission de booster l'émergence des métiers du transport, de la logistique et du commerce dans le pays et à l'international.</p>
                        </div>
                        <div class="social-links d-flex justify-content-center gap-3 pt-3 border-top">
                            <a href="#" class="social-icon" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="social-icon" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-icon" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="social-icon" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Infos de contact -->
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-info card border-0 shadow-sm h-100 p-4 rounded-4 bg-white d-flex flex-column justify-content-around">
                        <div class="contact-info-item d-flex align-items-center mb-2">
                            <i class="bi bi-geo-alt-fill fs-5 text-green me-3 flex-shrink-0"></i>
                            <p class="mb-0 small">Ambam<br>Ebolowa, Sud-Cameroun</p>
                        </div>
                        <div class="contact-info-item d-flex align-items-center mb-2">
                            <i class="bi bi-envelope-fill fs-5 text-green me-3 flex-shrink-0"></i>
                            <p class="mb-0 small text-break">estlc@estlc.unv-ebolowa.cm</p>
                        </div>
                        <div class="contact-info-item d-flex align-items-center mb-2">
                            <i class="bi bi-telephone-fill fs-5 text-green me-3 flex-shrink-0"></i>
                            <p class="mb-0 small">(+237) 222 482 412</p>
                        </div>
                        <div class="contact-info-item d-flex align-items-center">
                            <i class="bi bi-mailbox2 fs-5 text-green me-3 flex-shrink-0"></i>
                            <p class="mb-0 small">B.P 22 Ambam</p>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="col-lg-5 col-md-12" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 p-4 rounded-4 bg-white">
                        <div class="card-body p-0">
                            <form action="/save_message" method="post" class="contact-form">
                                {{ csrf_field() }}
                                <div class="mb-3">
                                    <input type="text" name="mess_sender" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="Votre nom" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="mess_send_email" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="Votre email" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="mess_objet" class="form-control bg-light border-0 py-2 px-3 rounded-3" placeholder="Sujet de la suggestion" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="mess_content" class="form-control bg-light border-0 py-2 px-3 rounded-3" rows="3" placeholder="Contenu du message" required></textarea>
                                </div>
                                <div class="my-2 text-center">
                                    @if(session('success'))
                                        <div class="form-status form-status-success alert alert-success py-1 small rounded-3">{{session('success')}}</div>
                                    @elseif(session('errors'))
                                        <div class="form-status form-status-error alert alert-danger py-1 small rounded-3">{{session('errors')}}</div>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">Envoyer <i class="bi bi-send-fill ms-1"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<footer id="footer" class="app-footer py-3 border-top">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-lg-6 text-lg-start text-center">
                <div class="copyright small text-white">&copy; Copyright <strong style="color: #0e8f74;">ESTLC</strong>. Tous droits réservés</div>
            </div>
            <div class="col-lg-6">
                <nav class="footer-links d-flex justify-content-center justify-content-lg-end gap-3 pt-2 pt-lg-0 flex-wrap small">
                    <a href="#">Accueil</a>
                    <a href="#">A Propos</a>
                    <a href="#">Politique de Confidentialité</a>
                    <a href="#">Guide d'inscription</a>
                </nav>
            </div>
        </div>
    </div>
</footer>

<a href="#" onclick="scrollToTop(event)" class="back-to-top d-flex align-items-center justify-content-center shadow" aria-label="Retour en haut de la page"><i class="bi bi-arrow-up-short"></i></a>
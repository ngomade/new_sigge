<div id="main">
    <section id="localisation" class="localisation">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Localisation D'ambam</h2>
            </div>

            <div class="row">

                <div class="col-lg-12 col-md-12" data-aos="fade-up" data-aos-delay="100"
                    style="display: flex; justify-content: center;">
                    <div id="map" class="card">
                        <div class="card-body">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d680696.1002018142!2d11.006932526069411!3d3.1073400537897453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x108bcf7a309a7977%3A0x7f54bad35e693c51!2zWWFvdW5kw6k!3m2!1d3.8480325!2d11.5020752!4m5!1s0x1087d5c57e4aa887%3A0x616425f0212f30d5!2sAmbam!3m2!1d2.3815417!2d11.2665498!5e0!3m2!1sfr!2scm!4v1692180500127!5m2!1sfr!2scm"
                                style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="contact" class="contact">
        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Contactez-Nous</h2>
            </div>

            <div class="row" style="display: flex; justify-content: center;">

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100" style="display: flex; justify-content: center;">
                    <div class="contact-about">
                        <h3>ESTLC</h3>
                        <p>ESTLC est une école de cycle ingénieur situé à AMBAM dans le Sud-Cameroun, Doté d'une équipe jeune et dynamique, elle a pour mission de booster
                            l'émergence des metiers du transport, de la Logistique et du Commerce dans le pays et aussi à l'internationale.
                        </p>
                        <div class="social-links" style="display: flex; justify-content: center;">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mt-4 mt-md-0" data-aos="fade-up" data-aos-delay="200" style="display: flex; justify-content: center;" >
                    <div class="info" >
                        <div>
                            <i class="ri-map-pin-line"></i>
                            <p>AMBAM<br>Ebolowa, Sud-Cameroun</p>
                        </div>

                        <div>
                            <i class="ri-mail-send-line"></i>
                            <p>estlc@estlc.unv-ebolowa.cm</p>
                        </div>

                        <div>
                            <i class="ri-phone-line"></i>
                            <p> (+237) 222 482 412 </p>
                        </div>

                        <div>
                            <i class="ri-inbox-archive-fill"></i>
                            <p> B.P 22 AMBAM </p>
                        </div>

                    </div>
                </div>

                <div class="col-lg-5 col-md-12" data-aos="fade-up" data-aos-delay="300">
                    <form action="/save_message" method="post" role="form" class="php-email-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" name="mess_sender" class="form-control" id="mess_sender"
                                placeholder="Votre nom" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="mess_send_email" id="mess_send_email"
                                placeholder="Votre Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="mess_objet" id="mess_objet"
                                placeholder="Sujet de la guggestion" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="mess_content" rows="5" placeholder="Contenu du message" required></textarea>
                        </div>
                        <div class="my-3">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Votre Message à été envoyé. Merci!!!</div>
                        </div>
                        <div class="text-center"><button type="submit">Envoyer <i
                                    class="ri-send-plane-line"></i></button></div>
                    </form>
                </div>

            </div>

        </div>
    </section>
</div>
<footer id="footer">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-lg-6 text-lg-left text-center">
                <div class="copyright">
                    &copy; Copyright <strong>ESTLC</strong>. Tous droits reservés
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="footer-links text-lg-right text-center pt-2 pt-lg-0">
                    <a href="#" class="scrollto">Acceuil</a>
                    <a href="#" class="scrollto">A Propos</a>
                    <a href="#">Politique de Confidentialité</a>
                    <a href="#">Guide d'inscription</a>
                </nav>
            </div>
        </div>
    </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

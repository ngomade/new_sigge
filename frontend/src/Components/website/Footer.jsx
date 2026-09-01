import React, { useEffect, useState } from "react";
import "../../Styles/Footer.css";

export default function Footer() {
  const [showBackToTop, setShowBackToTop] = useState(false);

  const [formData, setFormData] = useState({
    mess_sender: "",
    mess_send_email: "",
    mess_objet: "",
    mess_content: "",
  });

  // idle | loading | success | error
  const [status, setStatus] = useState("idle");

  useEffect(() => {
    const onScroll = () => setShowBackToTop(window.scrollY > 300);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus("loading");

    try {
      // TODO: brancher sur l'API réelle, ex: await messageService.send(formData)
      await new Promise((resolve) => setTimeout(resolve, 800));
      setStatus("success");
      setFormData({ mess_sender: "", mess_send_email: "", mess_objet: "", mess_content: "" });
    } catch (err) {
      setStatus("error");
    }
  };

  const scrollToTop = (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return (
    <>
      <div id="main">
        {/* ============================================================
            LOCALISATION
            ============================================================ */}
        <section id="localisation" className="app-section py-5">
          <div className="container">
            <div className="section-title text-center mb-4" data-aos="fade-up">
              <h2>Localisation d'Ambam</h2>
            </div>

            <div className="row">
              <div
                className="col-12 d-flex justify-content-center"
                data-aos="fade-up"
                data-aos-delay="100"
              >
                <div id="map" className="card map-card">
                  <div className="card-body p-0">
                    <iframe
                      title="Localisation ESTLC Ambam"
                      src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d680696.1002018142!2d11.006932526069411!3d3.1073400537897453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x108bcf7a309a7977%3A0x7f54bad35e693c51!2zWWFvdW5kw6k!3m2!1d3.8480325!2d11.5020752!4m5!1s0x1087d5c57e4aa887%3A0x616425f0212f30d5!2sAmbam!3m2!1d2.3815417!2d11.2665498!5e0!3m2!1sfr!2scm!4v1692180500127!5m2!1sfr!2scm"
                      style={{ border: 0, width: "100%", height: "400px" }}
                      allowFullScreen=""
                      loading="lazy"
                      referrerPolicy="no-referrer-when-downgrade"
                    ></iframe>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* ============================================================
            CONTACT
            ============================================================ */}
        <section id="contact" className="app-section app-section-alt py-5">
          <div className="container">
            <div className="section-title text-center mb-5" data-aos="fade-up">
              <h2>Contactez-Nous</h2>
            </div>

            <div className="row justify-content-center g-4">
              <div className="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div className="contact-about text-center">
                  <h3>ESTLC</h3>
                  <p>
                    ESTLC est une école de cycle ingénieur située à Ambam, dans le Sud-Cameroun.
                    Dotée d'une équipe jeune et dynamique, elle a pour mission de booster
                    l'émergence des métiers du transport, de la logistique et du commerce dans le
                    pays et à l'international.
                  </p>
                  <div className="social-links d-flex justify-content-center gap-3">
                    <a href="#" className="social-icon" aria-label="Twitter">
                      <i className="bi bi-twitter"></i>
                    </a>
                    <a href="#" className="social-icon" aria-label="Facebook">
                      <i className="bi bi-facebook"></i>
                    </a>
                    <a href="#" className="social-icon" aria-label="Instagram">
                      <i className="bi bi-instagram"></i>
                    </a>
                    <a href="#" className="social-icon" aria-label="LinkedIn">
                      <i className="bi bi-linkedin"></i>
                    </a>
                  </div>
                </div>
              </div> <br />

              <div className="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div className="contact-info">
                  <div className="contact-info-item">
                    <i className="bi bi-geo-alt-fill"></i>
                    <p>
                      Ambam
                      <br />
                      Ebolowa, Sud-Cameroun
                    </p>
                  </div>

                  <div className="contact-info-item">
                    <i className="bi bi-envelope-fill"></i>
                    <p>estlc@estlc.unv-ebolowa.cm</p>
                  </div>

                  <div className="contact-info-item">
                    <i className="bi bi-telephone-fill"></i>
                    <p>(+237) 222 482 412</p>
                  </div>

                  <div className="contact-info-item">
                    <i className="bi bi-mailbox2"></i>
                    <p>B.P 22 Ambam</p>
                  </div>
                </div>
              </div>

              <div className="col-lg-5 col-md-12" data-aos="fade-up" data-aos-delay="300">
                <form className="contact-form" onSubmit={handleSubmit}>
                  <div className="mb-3">
                    <input
                      type="text"
                      name="mess_sender"
                      className="form-control"
                      placeholder="Votre nom"
                      value={formData.mess_sender}
                      onChange={handleChange}
                      required
                    />
                  </div>
                  <div className="mb-3">
                    <input
                      type="email"
                      name="mess_send_email"
                      className="form-control"
                      placeholder="Votre email"
                      value={formData.mess_send_email}
                      onChange={handleChange}
                      required
                    />
                  </div>
                  <div className="mb-3">
                    <input
                      type="text"
                      name="mess_objet"
                      className="form-control"
                      placeholder="Sujet de la suggestion"
                      value={formData.mess_objet}
                      onChange={handleChange}
                      required
                    />
                  </div>
                  <div className="mb-3">
                    <textarea
                      name="mess_content"
                      className="form-control"
                      rows="5"
                      placeholder="Contenu du message"
                      value={formData.mess_content}
                      onChange={handleChange}
                      required
                    ></textarea>
                  </div>

                  <div className="my-3 text-center">
                    {status === "loading" && <div className="form-status form-status-loading">Envoi en cours...</div>}
                    {status === "error" && (
                      <div className="form-status form-status-error">
                        Une erreur est survenue, veuillez réessayer.
                      </div>
                    )}
                    {status === "success" && (
                      <div className="form-status form-status-success">
                        Votre message a été envoyé. Merci !
                      </div>
                    )}
                  </div>

                  <div className="text-center">
                    <button type="submit" className="btn btn-primary rounded-pill px-4" disabled={status === "loading"}>
                      Envoyer <i className="bi bi-send-fill ms-1"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
      </div>

      {/* ============================================================
          FOOTER
          ============================================================ */}
      <footer id="footer" className="app-footer">
        <div className="container">
          <div className="row d-flex align-items-center py-4">
            <div className="col-lg-6 text-lg-start text-center">
              <div className="copyright">
                &copy; Copyright <strong>ESTLC</strong>. Tous droits réservés
              </div>
            </div>
            <div className="col-lg-6">
              <nav className="footer-links d-flex justify-content-center justify-content-lg-end gap-3 pt-2 pt-lg-0 flex-wrap">
                <a href="#">Accueil</a>
                <a href="#">A Propos</a>
                <a href="#">Politique de Confidentialité</a>
                <a href="#">Guide d'inscription</a>
              </nav>
            </div>
          </div>
        </div>
      </footer>

      {/* ============================================================
          BACK TO TOP
          ============================================================ */}
      <a
        href="#"
        onClick={scrollToTop}
        className={`back-to-top d-flex align-items-center justify-content-center ${showBackToTop ? "back-to-top-visible" : ""}`}
        aria-label="Retour en haut de la page"
      >
        <i className="bi bi-arrow-up-short"></i>
      </a>
    </>
  );
}
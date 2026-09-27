@extends("sige_app.frontend.template.frontend")

@section('style')
<style>
    .maire-section {
        background: var(--app-bg, #f7faf8);
        padding: 3rem 0;
    }

    .maire-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }
    .maire-header .title-block h1 {
        color: var(--app-primary-dark, #11583f);
        font-weight: 700;
        font-size: 1.7rem;
        margin: 0 0 0.4rem;
    }
    .maire-header .title-block p {
        color: var(--app-text-muted, #61756c);
        margin: 0;
    }
    .maire-header img {
        height: 90px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
    }

    .maire-card {
        background: var(--app-surface, #fff);
        border: 1px solid var(--app-border, #dce9e2);
        border-radius: var(--app-radius-lg, 16px);
        box-shadow: 0 8px 24px var(--app-shadow-soft, rgba(17,61,53,.08));
        padding: 2rem;
    }

    .maire-card .section-tag {
        display: inline-block;
        background: var(--app-primary-soft, #dff2e9);
        color: var(--app-primary-dark, #11583f);
        font-weight: 700;
        letter-spacing: .04em;
        font-size: .85rem;
        padding: .35rem 1rem;
        border-radius: 999px;
        margin-bottom: 1.5rem;
    }

    .maire-photo {
        width: 100%;
        max-width: 340px;
        margin: 0 auto;
        display: block;
    }
    .maire-photo img {
        width: 100%;
        height: auto;
        border-radius: var(--app-radius-md, 12px);
        box-shadow: 0 8px 20px var(--app-shadow-soft, rgba(17,61,53,.08));
        border: 1px solid var(--app-border, #dce9e2);
    }
    .maire-photo .caption {
        text-align: center;
        font-weight: 700;
        color: var(--app-primary-dark, #11583f);
        margin-top: 1rem;
        font-size: 1.05rem;
    }

    .maire-text {
        color: var(--app-text, #18352b);
        text-align: justify;
        line-height: 1.9;
    }
    .maire-text p { margin-bottom: 1rem; }
    .maire-text ol {
        padding-left: 1.2rem;
        margin: 0;
    }
    .maire-text ol li { margin-bottom: 1rem; }

    @media (max-width: 767px) {
        .maire-header { justify-content: center; text-align: center; }
        .maire-card { padding: 1.25rem; }
    }
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.AOS) {
        AOS.init({ duration: 800, once: true, easing: 'ease-out-quart' });
    }
});
</script>
@endsection

@section('content')
    <section class="maire-section">
        <div class="container">
            <div class="maire-header" data-aos="fade-up">
                <div class="title-block">
                    <h1>Bienvenue à la Mairie d'Ambam</h1>
                    <p>Découvrez le mot d'accueil de Monsieur le Maire et la vision portée pour la commune.</p>
                </div>
                <img src="{{asset("sige_app/frontend/img/mairie/logo_mairie.jpg")}}" alt="Logo de la Mairie d'Ambam">
            </div>

            <div class="maire-card" data-aos="fade-up" data-aos-delay="100">
                <span class="section-tag">MOT DU MAIRE</span>

                <div class="row g-4">
                    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="150">
                        <div class="maire-photo">
                            <img src="{{asset("sige_app/frontend/img/mairie/maire.jpg")}}" alt="Le Maire d'Ambam" title="Le Maire d'Ambam">
                            <div class="caption">Ambam : un nouvel envol</div>
                        </div>
                    </div>

                    <div class="col-lg-8" data-aos="fade-left" data-aos-delay="200">
                        <div class="maire-text">
                            <p>La commune d'Ambam est depuis le vingt-six février 2020 tournée vers de nouveaux objectifs, avec notamment l'entrée en matière d'un nouvel exécutif municipal conduit par Monsieur <b>ZOMO OVONO Samson</b>.</p>
                            <p>Après une année 2020 fortement marquée par la pandémie du COVID 19, l'année nouvelle 2021 se présente avec un nouveau visage et un espoir d'un meilleur lendemain. C'est l'année attendue pour la finalisation du processus de décentralisation.</p>
                            <p>Faire d'Ambam une commune digne de ce nom, une véritable vitrine dans la sous-région, est un des objectifs fixés par la nouvelle équipe dirigeante. Plusieurs chantiers sont nécessaires pour atteindre ce noble et bel objectif :</p>

                            <div class="row g-4">
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="250">
                                    <ol>
                                        <li>Le premier challenge est de faire d'Ambam une ville propre et belle : l'hygiène, la salubrité et l'assainissement, sont des activités à mettre en œuvre par tous et chacun. La commune apportant sa contribution dans l'enlèvement des ordures et l'entretien des espaces verts.</li>
                                        <li>La réalisation des projets socio-économiques, pour donner un visage digne d'une capitale départementale, et ville carrefour de trois frontières : Cameroun – Gabon – Guinée Équatoriale.</li>
                                    </ol>
                                </div>
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                                    <ol start="3">
                                        <li>Cultiver et maintenir un climat de paix, de sécurité et de prospérité pour l'ensemble des populations de cette municipalité, dans un vivre ensemble propre aux coutumes africaines.</li>
                                        <li>La bonne gouvernance et le respect des normes administratives est un autre chantier tout aussi important. Il est bon que la municipalité ne reste pas en marge des prescriptions gouvernementales.</li>
                                        <li>L'appui et l'accompagnement des structures déconcentrées de l'État, pour une meilleure appropriation des compétences transférées et une implémentation harmonieuse et efficiente. L'autre objectif est la participation effective des populations et la construction d'une commune nouvelle.</li>
                                    </ol>
                                </div>
                            </div>

                            <p class="mt-3">Les chantiers sont nombreux et aussi importants les uns que les autres. Dans le cadre de l'accélération de la décentralisation, les populations ont un rôle d'acteur : il est plus qu'urgent que la commune d'Ambam prenne un nouvel envol pour le bien de tous et de chacun. Chaque citoyen devrait jouer sa partition et apporter ainsi sa contribution dans la construction d'une commune qui nous ressemble et que nous portons tous fièrement dans nos cœurs.</p>

                            <p>En définitive, le nouvel envol est un nouveau départ, une nouvelle approche ; une manière nouvelle de faire, impliquant la participation de l'ensemble des populations et des structures déconcentrées de l'État en plus de la commune, acteur central du processus de décentralisation, pour un développement total et harmonieux de notre belle cité d'Ambam.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
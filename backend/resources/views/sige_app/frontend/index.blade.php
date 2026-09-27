@extends("sige_app.frontend.template.frontend")

@section('style')
<style>
/* Styles propres à la page d'accueil uniquement — le thème commun (variables, .app-section, footer, contact, etc.) vient de public/css/app.css */

.app-hero {
    position: relative;
    padding: 40px 0 60px;
    overflow: hidden;
    background: linear-gradient(180deg, var(--app-primary-soft) 0%, var(--app-bg) 55%);
}
.hero-animated-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; }
.hero-glow { position: absolute; width: 420px; height: 420px; border-radius: 50%; filter: blur(80px); }
.hero-glow-left { top: -120px; left: -140px; background: radial-gradient(circle, rgba(14,143,116,.25) 0%, transparent 70%); }
.hero-glow-right { bottom: -160px; right: -140px; background: radial-gradient(circle, rgba(14,143,116,.15) 0%, transparent 70%); }

.floating-icon { position: absolute; color: var(--app-primary); opacity: .18; animation: float-icon 18s ease-in-out infinite; }
.floating-icon:nth-child(odd) { color: var(--app-primary-dark); }
@keyframes float-icon { 0%,100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-24px) rotate(8deg); } }
@media (prefers-reduced-motion: reduce) { .floating-icon { animation: none; } }

.school-logo-icon {
    color: var(--app-primary);
    background: var(--app-surface);
    border: 1px solid var(--app-border);
    border-radius: var(--app-radius-md);
    padding: 10px;
    box-shadow: 0 6px 16px var(--app-shadow-soft);
}

.announcement-card, .icon-box, .actu-card {
    background: var(--app-surface);
    border: 1px solid var(--app-border);
    border-radius: var(--app-radius-lg);
    box-shadow: 0 8px 24px var(--app-shadow-soft);
}
.announcement-card { padding: 20px; }
.announcement-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 16px; }
.announcement-list li { display: flex; align-items: flex-start; gap: 12px; }
.announcement-list li i { font-size: 20px; color: var(--app-primary); margin-top: 2px; flex-shrink: 0; }
.announcement-list strong { color: var(--app-primary-dark); }
.announcement-list p { margin: 4px 0 0; color: var(--app-text-muted); font-size: .92rem; line-height: 1.5; }

.carousel-slide-visual {
    height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--app-primary) 0%, var(--app-primary-dark) 100%);
}
.carousel-slide-visual i { font-size: 96px; color: rgba(255,255,255,.9); }
.carousel-caption h5 { color: #fff; }

.partner-icon {
    font-size: 44px;
    color: var(--app-primary);
    opacity: .75;
    transition: opacity var(--app-transition), transform var(--app-transition);
}
.partner-icon:hover { opacity: 1; transform: translateY(-4px); color: var(--app-primary-dark); }

.icon-box { padding: 32px 24px; text-align: center; height: 100%; transition: transform var(--app-transition); }
.icon-box:hover { transform: translateY(-6px); }
.icon-box .icon {
    width: 64px; height: 64px; margin: 0 auto 16px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; background: var(--app-primary-soft); color: var(--app-primary); font-size: 28px;
}
.icon-box .title a { color: var(--app-primary-dark); font-weight: 700; }
.icon-box .description { color: var(--app-text-muted); margin: 0; }

.actu-card { overflow: hidden; height: 100%; display: flex; flex-direction: column; }
.actu-card-icon { height: 100px; display: flex; align-items: center; justify-content: center; background: var(--app-primary-soft); color: var(--app-primary); font-size: 36px; }
.actu-card-body { padding: 16px; flex: 1; }
.actu-card-title { color: var(--app-text); font-weight: 600; margin: 0; }
.actu-card-footer { padding: 12px 16px; border-top: 1px solid var(--app-border); display: flex; align-items: center; justify-content: space-between; gap: 8px; }

.faq-item { padding: 18px 0; border-bottom: 1px solid var(--app-border); }
.faq-item h4 { font-size: 1.05rem; color: var(--app-text); }
.faq-item p { color: var(--app-text-muted); margin: 0; }
.faq-icon { font-size: 20px; color: var(--app-primary); margin-top: 3px; }

.app-hero#hero { height: auto; margin-top: 0; width: 100%; }
.app-hero#hero h2 { color: var(--app-primary-dark); line-height: 1.3; text-align: left; margin: 0; }
.app-hero .row.g-4 { align-items: stretch; }
.app-hero .row.g-4 > [class*="col-"] { display: flex; flex-direction: column; }
.app-hero .announcement-card { height: 322px; box-sizing: border-box; }
.app-hero .carousel { width: 100%; height: 322px; margin-top: 0; }
.app-hero .order-1.order-lg-2 { margin-top: 6.5%; }
.app-hero .carousel-inner, .app-hero .carousel-item, .app-hero .carousel-slide-visual { height: 100%; }
.app-hero .carousel-slide-visual img { min-height: 100%; }

#header .logo img { display: block; visibility: visible; width: auto; height: 46px; max-width: 170px; object-fit: contain; }
#header .logo h1 { margin: 0; line-height: 1; }
#header .container > .logo:last-child { display: none; }

@media (max-width: 991px) {
    .app-hero .announcement-card, .app-hero .carousel { height: auto; min-height: 322px; }
    .app-hero .order-1.order-lg-2 { margin-top: 0; }
    .app-hero .carousel { margin-top: 0; }
}
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('homeCarousel');
    if (carousel && window.bootstrap) {
        const carouselInstance = bootstrap.Carousel.getOrCreateInstance(carousel, { interval: 5000, pause: 'hover', wrap: true, touch: true });
        carouselInstance.cycle();
    }
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            backToTop.classList.toggle('back-to-top-visible', window.scrollY > 300);
        }, { passive: true });
    }
    const password = document.getElementById('npwd');
    const confirmation = document.getElementById('npwdc');
    const message = document.getElementById('message');
    if (confirmation) {
        confirmation.addEventListener('input', function () {
            message.textContent = password.value !== confirmation.value ? 'Les mots de passe ne correspondent pas.' : '';
        });
    }
});
function scrollToTop(event) { event.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); }
function validatePasswords() {
    const valid = document.getElementById('npwd').value === document.getElementById('npwdc').value;
    document.getElementById('message').textContent = valid ? '' : 'Les mots de passe ne correspondent pas.';
    return valid;
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('homeCarousel');
    if (!carousel || !window.bootstrap) return;
    const slides = [
        { icon: 'bi-mortarboard-fill', title: "Concours d'entrée ESTLC", subtitle: "Rejoignez une formation d'ingénieur reconnue en transport et logistique" },
        { icon: 'bi-truck', title: 'Gestion Logistique, Transport et Commerce', subtitle: 'Un parcours tourné vers les métiers de la chaîne logistique' },
        { icon: 'bi-signpost-split-fill', title: 'Technologie de Transport et de Logistique', subtitle: 'Des compétences techniques au service de la mobilité' }
    ];
    const instance = bootstrap.Carousel.getInstance(carousel);
    if (instance) instance.dispose();
    carousel.querySelector('.carousel-indicators').innerHTML = slides.map(function (slide, index) {
        return '<button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="' + index + '" class="' + (index === 0 ? 'active' : '') + '" aria-label="Slide ' + (index + 1) + '"></button>';
    }).join('');
    carousel.querySelector('.carousel-inner').innerHTML = slides.map(function (slide, index) {
        return '<div class="carousel-item ' + (index === 0 ? 'active' : '') + '"><div class="carousel-slide-visual"><i class="bi ' + slide.icon + '"></i></div><div class="carousel-caption"><h5>' + slide.title + '</h5><p>' + slide.subtitle + '</p></div></div>';
    }).join('');
    bootstrap.Carousel.getOrCreateInstance(carousel, { interval: 5000, pause: 'hover', wrap: true, touch: true }).cycle();
});
</script>
@endsection

@section('content')
@if(Session::exists("new_password"))
<div class="modal fade" id="NewPasswordModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header" style="background-color:var(--app-success,#198754);color:white"><h5 class="modal-title">Changement de mot de passe</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><form action="/changer_pwd_first" method="post" onsubmit="return validatePasswords()">{{ csrf_field() }}<input type="hidden" name="code_user" value="{{Session::get('user')->code_user}}"><div class="modal-body p-3"><div class="row"><div class="col-sm-11 m-auto mb-4"><input type="text" name="apwd" id="apwd" class="form-control" placeholder="votre ancien mot de passe"></div></div><div class="row"><div class="col-sm-11 m-auto mb-4"><input type="password" name="npwd" id="npwd" class="form-control" required placeholder="votre nouveau mot de passe"></div></div><div class="row"><div class="col-sm-11 m-auto"><input type="password" name="npwdc" id="npwdc" class="form-control" required placeholder="confirmez votre nouveau mot de passe"><span id="message" class="d-block mt-2 small" style="color:var(--app-danger,#dc3545)"></span></div></div></div><div class="modal-footer mt-0"><button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-success">Valider</button></div></form></div></div></div>
<script>document.addEventListener('DOMContentLoaded',function(){new bootstrap.Modal(document.getElementById('NewPasswordModal')).show();});</script>
@endif
@php
    $homeSlides = [
        (object) ['id' => 1, 'icon' => 'bi-mortarboard-fill', 'title' => "Concours d'entrée ESTLC", 'subtitle' => "Rejoignez une formation d'ingénieur reconnue en transport et logistique"],
        (object) ['id' => 2, 'icon' => 'bi-truck', 'title' => 'Gestion Logistique, Transport et Commerce', 'subtitle' => 'Un parcours tourné vers les métiers de la chaîne logistique'],
        (object) ['id' => 3, 'icon' => 'bi-signpost-split-fill', 'title' => 'Technologie de Transport et de Logistique', 'subtitle' => 'Des compétences techniques au service de la mobilité'],
    ];
@endphp
<section id="hero" class="app-hero position-relative">
    <div class="hero-animated-bg" aria-hidden="true">
        <div class="hero-glow hero-glow-left"></div>
        <div class="hero-glow hero-glow-right"></div>
        @foreach ([['bi-mortarboard',46,'12%','8%','14s','0s'],['bi-book',34,'70%','6%','18s','2s'],['bi-truck',40,'20%','88%','16s','1s'],['bi-signpost-split',30,'78%','90%','20s','3s'],['bi-lightbulb',28,'45%','50%','22s','4s']] as $shape)
            <i class="bi {{$shape[0]}} floating-icon" style="font-size:{{$shape[1]}}px;top:{{$shape[2]}};left:{{$shape[3]}};animation-duration:{{$shape[4]}};animation-delay:{{$shape[5]}}"></i>
        @endforeach
    </div>
    <div class="container position-relative" style="z-index:1">
        <div class="row align-items-center mb-4">
            <div class="col-auto">
                <img src="{{asset('share/img/logo_estlc_ok.png')}}" alt="Logo ESTLC" class="school-logo-icon" style="height:100px">
            </div>
            <div class="col">
                <h2 class="mb-0" style="font-size:25px">Ecole Supérieure de Transport, de Logistique et de Commerce — ESTLC</h2>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="mb-3 text-center text-lg-start">
                    <a href="/download/Appel_Candidature_Recrutement_ESTLC" class="btn btn-primary rounded-pill px-4 py-2" target="_blank"><i class="bi bi-download me-2"></i>Télécharger l'appel à candidature</a>
                </div>
                <div class="announcement-card">
                    <ul class="announcement-list">
                        <li><i class="bi bi-megaphone-fill"></i><div><strong>Avis aux étudiants – Master Recherche à l'UFD-TSI</strong><p>Le Coordonnateur de l'UFD-TSI informe les étudiants nouvellement sélectionnés en Master Recherche pour l'année académique 2024-2025 qu'une réunion importante se tiendra le lundi 03 mars 2025 à 14h précises, au campus de Nkoumekeke, salle C1. Présence obligatoire.</p></div></li>
                        <li><i class="bi bi-calendar-event-fill"></i><div><strong>Rentrée académique – Master Recherche à l'UFD-TSI</strong><p>Lundi 03 mars 2025. Pour les modalités d'inscription académique et administrative, veuillez vous rapprocher du secrétariat de l'UFD-TSI.</p></div></li>
                        <li><i class="bi bi-mortarboard-fill"></i><div><strong>Recrutement de 150 Enseignants dans les Universités d'État !</strong><p>La troisième phase de recrutement de 150 enseignants est lancée pour l'exercice 2025 dans les Universités d'État de Bertoua, Ebolowa et Garoua. Les postes sont ouverts aux Camerounais titulaires du Doctorat ou du PhD !</p></div></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <div id="homeCarousel" class="carousel slide rounded-4 overflow-hidden shadow" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach (\App\Models\Slide::orderBy('id','desc')->take(10)->get() as $slide)
                            <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="{{$loop->index}}" class="{{$loop->first?'active':''}}" aria-label="Slide {{$loop->iteration}}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @forelse (\App\Models\Slide::orderBy('id','desc')->take(10)->get() as $slide)
                            <div class="carousel-item {{$loop->first?'active':''}}">
                                <div class="carousel-slide-visual">
                                    <img src="{{asset('storage/app/public/slides/'.$slide->photo)}}" class="d-block w-100 h-100" style="object-fit:cover" alt="{{$slide->first_title}}">
                                </div>
                                <div class="carousel-caption"><h5>{{$slide->first_title}}</h5><p>{{$slide->second_title}}</p></div>
                            </div>
                        @empty
                            <div class="carousel-item active"><div class="carousel-slide-visual"><i class="bi bi-mortarboard-fill"></i></div></div>
                        @endforelse
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span><span class="visually-hidden">Précédent</span></button>
                    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span><span class="visually-hidden">Suivant</span></button>
                </div>
            </div>
        </div>
    </div>
</section>

<main id="main">
    <section id="clients" class="app-section py-5">
        <div class="container">
            <div class="row justify-content-center g-4 align-items-center">
                @foreach ([['share/img/logo_islape.jpg','Institut Supérieur La Perle'],['share/img/logo_ueb.png','Université d’Ebolowa'],['share/img/logo_islape.jpg','Institut Supérieur La Perle'],['share/img/logo_ueb.png','Université d’Ebolowa'],['share/img/logo_islape.jpg','Institut Supérieur La Perle'],['share/img/logo_ueb.png','Université d’Ebolowa']] as $partner)
                    <div class="col-lg-2 col-md-4 col-6 text-center">
                        <img src="{{asset($partner[0])}}" alt="{{$partner[1]}}" title="{{$partner[1]}}" class="img-fluid partner-icon" style="max-height:130px;width:auto;object-fit:contain">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="services" class="app-section py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Nos Parcours</h2>
                <p>Actuellement, nous possédons différents parcours permettant aux apprenants de se spécialiser dans leurs formations</p>
            </div>
            <div class="row justify-content-center g-4">
                @foreach ([['bi-truck','GLTCO','Gestion Logistique Transport et Commerce'],['bi-signpost-split-fill','TTL','Technologie de Transport et de Logistique']] as $path)
                    <div class="col-md-6 col-lg-3">
                        <div class="icon-box">
                            <div class="icon"><i class="bi {{$path[0]}}"></i></div>
                            <h4 class="title"><a href="#">{{$path[1]}}</a></h4>
                            <p class="description">{{$path[2]}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="more-services" class="app-section py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Activités récentes</h2>
                <p>Découvrez la vie de l'école à travers nos articles d'actualités</p>
            </div>
            <div class="row g-4">
                @foreach (\App\Models\Actualite::orderBy('created_at','desc')->take(9)->get() as $actu)
                    <div class="col-md-4">
                        <div class="actu-card">
                            <div class="actu-card-icon"><i class="bi bi-newspaper"></i></div>
                            <div class="actu-card-body"><p class="actu-card-title">{{$actu->actu_title}}</p></div>
                            <div class="actu-card-footer">
                                <span class="small text-muted">Publié le {{$actu->created_at->format('d/m/Y')}}</span>
                                <a href="/details_actu/{{$actu->actu_code}}" class="btn btn-sm btn-outline">Lire plus <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 text-end">
                <a href="/all_actu" class="btn btn-primary"><i class="bi bi-list-ul me-1"></i>Toutes nos actualités</a>
            </div>
        </div>
    </section>

    <section id="faq" class="app-section app-section-alt py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Questions Utiles pour étudiants et candidats</h2>
            </div>
            @foreach ([['Où est située la localité d’Ambam ?','Ambam est une ville et une communauté située dans la région du Sud-Cameroun, à la frontière de la Guinée Equatoriale et du Gabon. Cette ville est située à environ 245 km de Yaoundé.'],['Qui peut postuler au concours d’entrée à l’ESTLC ?','Les candidats doivent être titulaires d’un Baccalauréat ou d’un GCE A/L pour le premier cycle, et d’une Licence pour le second cycle.'],['Quels sont les départements disponibles à l’ESTLC ?','En plus des enseignements généraux et scientifiques de base, l’ESTLC dispose des départements Transport, Logistique, Recherche Opérationnelle, Génie Informatique, E-Commerce et Mécatronique.'],['Quels diplômes obtient-on au terme de sa formation à l’ESTLC ?','Au terme des 5 années de formation, l’étudiant obtient un diplôme d’ingénieur, pouvant déboucher sur un Master Recherche puis un Doctorat PhD.'],['Comment modifier ma fiche d’inscription au concours ?','Conservez l’identifiant et le mot de passe transmis après le remplissage de votre fiche : ils vous permettront d’y revenir pour la modifier.']] as $faq)
                <div class="row faq-item g-3">
                    <div class="col-lg-5 d-flex align-items-start gap-2"><i class="bi bi-question-circle-fill faq-icon"></i><h4 class="mb-0">{{$faq[0]}}</h4></div>
                    <div class="col-lg-7"><p>{{$faq[1]}}</p></div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
@extends("sige_app.frontend.template.frontend")
@section("style")
    <style>
        .org-mairie-section {
            --level-1: var(--app-surface, #fff);
            --level-2: var(--app-surface, #fff);
            --line-color: var(--app-border, #dce9e2);

            background: var(--app-bg, #f7faf8);
            padding: 3rem 0 4rem;
        }

        .org-mairie-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .org-mairie-header .badge-icon {
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
        .org-mairie-header h1 {
            color: var(--app-primary-dark, #11583f);
            font-weight: 700;
            font-size: 1.7rem;
            margin-bottom: 0.5rem;
        }
        .org-mairie-header p {
            color: var(--app-text-muted, #61756c);
            max-width: 560px;
            margin: 0 auto;
        }

        .org-mairie-card {
            background: var(--app-surface, #fff);
            border: 1px solid var(--app-border, #dce9e2);
            border-radius: var(--app-radius-lg, 16px);
            box-shadow: 0 8px 24px var(--app-shadow-soft, rgba(17,61,53,.08));
            padding: 2.5rem 1.5rem;
        }

        .org-mairie-section ol {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .org-mairie-section .rectangle {
            position: relative;
            border-radius: var(--app-radius-md, 12px);
            border: 1px solid var(--app-border, #dce9e2);
            box-shadow: 0 6px 16px var(--app-shadow-soft, rgba(17,61,53,.08));
            padding: 1.25rem 1rem;
            text-align: center;
        }
        .org-mairie-section .rectangle img {
            border: 3px solid var(--app-primary-soft, #dff2e9);
            margin-bottom: 0.75rem;
        }
        .org-mairie-section .rectangle .name {
            color: var(--app-primary-dark, #11583f);
            font-weight: 700;
            font-size: 1rem;
            margin: 0 0 0.25rem;
        }
        .org-mairie-section .rectangle .role {
            color: var(--app-text-muted, #61756c);
            font-size: 0.9rem;
            margin: 0;
        }

        /* LEVEL-1
        –––––––––––––––––––––––––––––––––––––––––––––––––– */
        .level-1 {
            width: 260px;
            margin: 0 auto 40px;
            background: var(--level-1);
        }
        .level-1::before {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: var(--line-color);
        }

        /* LEVEL-2
        –––––––––––––––––––––––––––––––––––––––––––––––––– */
        .level-2-wrapper {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            max-width: 700px;
            margin: 0 auto;
            gap: 0 20px;
        }
        .level-2-wrapper::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 27%;
            width: 48.7%;
            height: 2px;
            background: var(--line-color);
        }
        .level-2-wrapper li {
            position: relative;
        }
        .level-2-wrapper > li::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: var(--line-color);
        }
        .level-2 {
            width: 100%;
            margin: 0 auto;
            background: var(--level-2);
        }

        /* MOBILE
        –––––––––––––––––––––––––––––––––––––––––––––––––– */
        @media screen and (max-width: 700px) {
            .org-mairie-card { padding: 2rem 1rem; }

            .level-1 { width: 100%; }

            .level-2-wrapper {
                display: block;
                width: 90%;
                margin: 0 auto;
                position: relative;
            }
            .level-2-wrapper::before {
                left: -20px;
                top: 0;
                width: 2px;
                height: calc(100% + 20px);
            }
            .level-2-wrapper > li::before {
                display: none;
            }
            .level-2-wrapper > li:not(:first-child) {
                margin-top: 30px;
            }
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
<div class="org-mairie-section">
    <div class="container">

        <div class="org-mairie-header" data-aos="fade-up">
            <div class="badge-icon">
                <i class="bi bi-diagram-3-fill"></i>
            </div>
            <h1>Mairie d'Ambam : Organigramme</h1>
        </div>

        <div class="org-mairie-card" data-aos="fade-up" data-aos-delay="100">
            <div class="level-1 rectangle" data-aos="zoom-in" data-aos-delay="150">
                <img src="{{asset("sige_app/frontend/img/mairie/maire_portrait.png")}}" alt="Le Maire d'Ambam" style="width: 95%;" class="rounded">
                <p class="name">ZOMO OVONO SAMSON</p>
                <p class="role">Maire de la Commune d'Ambam</p>
            </div>

            <ol class="level-2-wrapper">
                <li data-aos="fade-up" data-aos-delay="200">
                    <div class="level-2 rectangle">
                        <img src="{{asset("sige_app/frontend/img/mairie/premier_adjoint.png")}}" alt="1er Adjoint au Maire" style="width: 50%;" class="rounded">
                        <p class="name">AVOMO EKOTO MATHILDE EPSE ELLA</p>
                        <p class="role">1<sup>ier</sup> Adjoint au Maire</p>
                    </div>
                </li>
                <li data-aos="fade-up" data-aos-delay="250">
                    <div class="level-2 rectangle">
                        <img src="{{asset("sige_app/frontend/img/mairie/second_adjoint.png")}}" alt="2e Adjoint au Maire" style="width: 63%;" class="rounded">
                        <p class="name">NVOA JENNER PURCELL</p>
                        <p class="role">2<sup>ieme</sup> Adjoint au Maire</p>
                    </div>
                </li>
            </ol>
        </div>

    </div>
</div>
@endsection
@extends("sige_app.frontend.template.frontend")

@section('style')
<style>
    .organigramme-section {
        background: var(--app-bg, #f7faf8);
        padding: 3rem 0 4rem;
    }

    .organigramme-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .organigramme-header .badge-icon {
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
    .organigramme-header h1 {
        color: var(--app-primary-dark, #11583f);
        font-weight: 700;
        font-size: 1.7rem;
        margin-bottom: 0.5rem;
    }
    .organigramme-header p {
        color: var(--app-text-muted, #61756c);
        max-width: 560px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .organigramme-card {
        background: var(--app-surface, #fff);
        border: 1px solid var(--app-border, #dce9e2);
        border-radius: var(--app-radius-lg, 16px);
        box-shadow: 0 8px 24px var(--app-shadow-soft, rgba(17,61,53,.08));
        padding: 1.5rem;
    }

    .organigramme-frame {
        position: relative;
        border: 1px solid var(--app-border, #dce9e2);
        border-radius: var(--app-radius-md, 12px);
        overflow: hidden;
        background: #fafcfb;
        text-align: center;
    }
    .organigramme-frame img {
        width: 100%;
        height: auto;
        display: block;
        cursor: zoom-in;
        transition: transform 220ms ease;
    }
    .organigramme-frame:hover img {
        transform: scale(1.01);
    }

    .organigramme-actions {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
</style>
@endsection

@section('content')
    <div class="organigramme-section">
        <div class="container">
            <div class="organigramme-header">
                <div class="badge-icon">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <h1>Organigramme de l'ESTLC</h1>
                <p>Vue d'ensemble de la structure organisationnelle de l'école : direction, divisions et départements.</p>
            </div>

            <div class="organigramme-card">
                <div class="organigramme-frame">
                    <a href="{{asset('sige_app/frontend/img/organigrammenew.png')}}" class="glightbox" data-title="Organigramme de l'ESTLC">
                        <img src="{{asset('sige_app/frontend/img/organigrammenew.png')}}" alt="Organigramme de l'ESTLC">
                    </a>
                </div>

                <div class="organigramme-actions">
                    <a href="{{asset('sige_app/frontend/img/organigrammenew.png')}}" class="glightbox btn btn-primary rounded-pill px-4" data-title="Organigramme de l'ESTLC">
                        <i class="bi bi-zoom-in me-1"></i> Agrandir
                    </a>
                    <a href="{{asset('sige_app/frontend/img/organigrammenew.png')}}" download class="btn btn-outline rounded-pill px-4">
                        <i class="bi bi-download me-1"></i> Télécharger
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.GLightbox) {
            GLightbox({ selector: '.glightbox' });
        }
    });
</script>
@endsection
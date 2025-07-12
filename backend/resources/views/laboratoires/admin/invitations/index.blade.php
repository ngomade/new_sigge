@extends('laboratoires.public.layout')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class='bx bx-link-alt'></i> Gestion des invitations
                </h2>
                <a href="{{ route('laboratoires.admin.membres', $laboratoire->code_lab) }}" class="btn btn-outline-secondary">
                    <i class='bx bx-arrow-back'></i> Retour aux membres
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class='bx bx-plus-circle'></i> Créer une nouvelle invitation
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('laboratoires.admin.invitations.store', $laboratoire->code_lab) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="id_rl" class="form-label">Rôle dans le laboratoire</label>
                                <select name="id_rl" id="id_rl" class="form-select">
                                    <option value="">Sélectionner un rôle (optionnel)</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id_rl }}">{{ $role->lib_rl }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Si aucun rôle n'est sélectionné, un rôle par défaut sera attribué</small>
                            </div>
                            <div class="col-md-4">
                                <label for="date_fin_affectation" class="form-label">Date de fin d'affectation</label>
                                <input type="date" name="date_fin_affectation" id="date_fin_affectation"
                                       class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                <small class="form-text text-muted">Date à laquelle l'affectation prendra fin</small>
                            </div>
                            <div class="col-md-3">
                                <label for="duree_validite_jours" class="form-label">Durée de validité du lien (jours)</label>
                                <input type="number" name="duree_validite_jours" id="duree_validite_jours"
                                       class="form-control" required min="1" max="30" value="7">
                                <small class="form-text text-muted">Nombre de jours pendant lesquels le lien sera valide</small>
                            </div>
                            <div class="col-md-3">
                                <label for="nombre_utilisations_max" class="form-label">Nombre maximum d'utilisations</label>
                                <input type="number" name="nombre_utilisations_max" id="nombre_utilisations_max"
                                       class="form-control" required min="1" max="100" value="1">
                                <small class="form-text text-muted">Nombre maximum de personnes qui peuvent utiliser ce lien</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-plus'></i> Créer le lien d'invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class='bx bx-check-circle'></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('invitation_url'))
                <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                    <h6><i class='bx bx-info-circle'></i> Lien d'invitation généré :</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ session('invitation_url') }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copierLien()">
                            <i class='bx bx-copy'></i> Copier
                        </button>
                    </div>
                    <small class="form-text">Partagez ce lien avec la personne que vous souhaitez inviter</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Liste des invitations -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class='bx bx-list-ul'></i> Invitations créées
                    </h5>
                </div>
                <div class="card-body">
                    @if($invitations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%;">Lien d'invitation</th>
                                        <th style="width: 12%;">Rôle</th>
                                        <th style="width: 12%;">Fin affectation</th>
                                        <th style="width: 12%;">Expire le</th>
                                        <th style="width: 10%;">Utilisations</th>
                                        <th style="width: 10%;">Statut</th>
                                        <th style="width: 8%;">Créé par</th>
                                        <th style="width: 11%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <!-- URL courte avec boutons d'action -->
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                               value="{{ $invitation->url_invitation }}" readonly
                                                               style="font-size: 0.75rem;">
                                                        <button class="btn btn-outline-primary btn-sm" type="button"
                                                                data-url="{{ $invitation->url_invitation }}"
                                                                data-token="{{ $invitation->token }}"
                                                                onclick="afficherQRCode(this)"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Afficher QR Code">
                                                            <i class='bx bx-qr'></i>
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm" type="button"
                                                                onclick="partagerDirectement('{{ $invitation->url_invitation }}')"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Partager l'invitation">
                                                            <i class='bx bx-share-alt'></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary btn-sm" type="button"
                                                                onclick="copierLienSpecifique('{{ $invitation->url_invitation }}')"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Copier le lien">
                                                            <i class='bx bx-copy'></i>
                                                        </button>
                                                    </div>
                                                    <!-- Lien complet (optionnel, affiché au survol) -->
                                                    <div class="collapse" id="urlComplete{{ $invitation->id }}">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light" style="font-size: 0.7rem;">Complet</span>
                                                            <input type="text" class="form-control form-control-sm"
                                                                   value="{{ $invitation->url_invitation_complete }}" readonly
                                                                   style="font-size: 0.7rem;">
                                                        </div>
                                                    </div>
                                                    <!-- Bouton pour afficher/masquer l'URL complète -->
                                                    <button class="btn btn-link btn-sm p-0 text-muted" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#urlComplete{{ $invitation->id }}"
                                                            style="font-size: 0.7rem; text-decoration: none;">
                                                        <i class='bx bx-chevron-down'></i> Voir URL complète
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                @if($invitation->roleLabo)
                                                    <span class="badge bg-info" style="font-size: 0.7rem;">{{ $invitation->roleLabo->lib_rl }}</span>
                                                @else
                                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Défaut</span>
                                                @endif
                                            </td>
                                            <td style="font-size: 0.8rem;">{{ \Carbon\Carbon::parse($invitation->date_fin_affectation)->format('d/m/Y') }}</td>
                                            <td style="font-size: 0.8rem;">{{ \Carbon\Carbon::parse($invitation->date_expiration)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info" style="font-size: 0.7rem;">
                                                    {{ $invitation->nombre_utilisations_actuelles }}/{{ $invitation->nombre_utilisations_max }}
                                                </span>
                                                @if($invitation->est_limite_atteinte)
                                                    <br><small class="text-muted" style="font-size: 0.65rem;">Limite atteinte</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->statut === 'actif')
                                                    @if($invitation->est_expire)
                                                        <span class="badge bg-warning" style="font-size: 0.7rem;">Expiré</span>
                                                    @elseif($invitation->est_limite_atteinte)
                                                        <span class="badge bg-warning" style="font-size: 0.7rem;">Limite</span>
                                                    @else
                                                        <span class="badge bg-success" style="font-size: 0.7rem;">Actif</span>
                                                    @endif
                                                @elseif($invitation->statut === 'utilise')
                                                    <span class="badge bg-primary" style="font-size: 0.7rem;">Utilisé</span>
                                                @else
                                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">Expiré</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->createdBy)
                                                    <small style="font-size: 0.75rem;">{{ $invitation->createdBy->id_pers_lab }}</small>
                                                @else
                                                    <small class="text-muted" style="font-size: 0.75rem;">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('laboratoires.admin.invitations.destroy', [$laboratoire->code_lab, $invitation]) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette invitation ?')">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class='bx bx-link-alt' style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucune invitation créée pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">
                    <i class='bx bx-qr'></i> Code QR d'invitation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContent"></div>
                <div class="mt-3">
                    <small class="text-muted">Scannez ce code QR pour accéder au lien d'invitation</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="partagerQRCode()">
                    <i class='bx bx-share-alt'></i> Partager
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Conteneur pour les notifications toast -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

<!-- Styles CSS personnalisés -->
<style>
    .table-sm td, .table-sm th {
        padding: 0.5rem 0.25rem;
        vertical-align: middle;
    }

    .table-sm .input-group-sm .form-control {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .table-sm .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .table-sm .badge {
        font-size: 0.7rem;
    }

    /* Optimisation pour les écrans moyens */
    @media (max-width: 1200px) {
        .table-sm td, .table-sm th {
            padding: 0.25rem 0.15rem;
        }
    }

    /* Optimisation pour les petits écrans */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }
    }
</style>

<!-- QR Code JS -->
<script src="{{ asset('js/qrcode-simple.js') }}"></script>

<script>
let currentQRCodeUrl = '';

function afficherQRCode(button) {
    const url = button.getAttribute('data-url');
    const token = button.getAttribute('data-token');
    currentQRCodeUrl = url;

    console.log('Afficher QR Code:', { url, token });

    // Debug de l'URL
    debugQRCode(url);

    // Ouvrir le modal
    const modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
    modal.show();

    // Générer le QR code avec notre script simple
    const qrContainer = document.getElementById('qrCodeContent');
    qrContainer.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Génération du QR code...</span></div>';

    try {
        // Utiliser la vraie URL d'invitation
        const qrImage = generateSimpleQRCode(url, 300);
        qrContainer.innerHTML = '';
        qrContainer.appendChild(qrImage);

        // Ajouter des informations de debug
        const debugMsg = document.createElement('div');
        debugMsg.className = 'alert alert-info mt-2';
        debugMsg.innerHTML = `
            <strong>URL d'invitation:</strong> ${url}<br>
            <strong>Longueur:</strong> ${url.length} caractères<br>
            <strong>URL valide:</strong> ${isValidUrl(url) ? 'Oui' : 'Non'}<br>
        `;
        qrContainer.appendChild(debugMsg);

    } catch (error) {
        console.error('Erreur lors de la génération du QR code:', error);
        qrContainer.innerHTML = '<div class="alert alert-danger">Erreur lors de la génération du QR code</div>';
    }
}

function partagerQRCode() {
    if (!currentQRCodeUrl) return;

    try {
        shareQRCode(currentQRCodeUrl, 'Invitation au laboratoire');
    } catch (error) {
        console.error('Erreur lors du partage:', error);
        alert('Erreur lors du partage');
    }
}

function partagerDirectement(url) {
    try {
        shareQRCode(url, 'Invitation au laboratoire');
    } catch (error) {
        console.error('Erreur lors du partage direct:', error);
        alert('Erreur lors du partage');
    }
}

// Initialiser les tooltips Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function copierLien() {
    const lien = document.querySelector('.alert-info input').value;
    navigator.clipboard.writeText(lien).then(() => {
        // Afficher une notification de succès
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '1050';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class='bx bx-check-circle text-success'></i>
                    <strong class="me-auto">Succès</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    Lien copié dans le presse-papiers !
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}

function copierLienSpecifique(lien) {
    navigator.clipboard.writeText(lien).then(() => {
        // Afficher une notification de succès
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '1050';
        toast.innerHTML = `
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class='bx bx-check-circle text-success'></i>
                    <strong class="me-auto">Succès</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    Lien copié dans le presse-papiers !
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}
</script>
@endsection

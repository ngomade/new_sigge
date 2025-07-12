/**
 * Générateur de QR Code simple et fiable
 * Utilise l'API QR Server pour générer des QR codes
 */

function generateSimpleQRCode(text, size = 200) {
    // Nettoyer le texte
    const cleanText = encodeURIComponent(text.trim());

    // Utiliser l'API QR Server avec des paramètres optimisés
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${cleanText}&format=png&margin=2&ecc=H`;

    // Créer l'image
    const img = document.createElement('img');
    img.src = qrUrl;
    img.alt = 'QR Code d\'invitation';
    img.className = 'img-fluid border rounded';
    img.style.maxWidth = '100%';
    img.style.height = 'auto';

    // Ajouter un gestionnaire d'erreur
    img.onerror = function() {
        console.error('Erreur lors du chargement du QR code');
        this.style.display = 'none';
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.innerHTML = '<i class="bx bx-error"></i> Erreur lors de la génération du QR code';
        this.parentNode.appendChild(errorDiv);
    };

    return img;
}

function shareQRCode(text, title = 'QR Code d\'invitation') {
    console.log('=== DÉBUT PARTAGE ===');
    console.log('Texte à partager:', text);
    console.log('Titre:', title);
    console.log('User Agent:', navigator.userAgent);
    console.log('navigator.share disponible:', !!navigator.share);
    console.log('Est mobile:', /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));

    // Nettoyer le texte
    const cleanText = encodeURIComponent(text.trim());

    // URL pour le QR code
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${cleanText}&format=png&margin=2&ecc=H`;

    // Pour l'instant, toujours afficher les options de partage personnalisées
    // On pourra réactiver l'API native plus tard si nécessaire
    console.log('Affichage des options de partage personnalisées');
    showShareOptions(text, qrUrl, title);

    /*
    // Code pour l'API native (désactivé pour l'instant)
    if (navigator.share && /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        console.log('Utilisation de l\'API Web Share native');
        navigator.share({
            title: title,
            text: `Lien d'invitation: ${text}`,
            url: text
        }).then(() => {
            console.log('Partage réussi via API native');
        }).catch((error) => {
            console.log('Erreur de partage via API native:', error);
            // Fallback vers les options de partage
            showShareOptions(text, qrUrl, title);
        });
    } else {
        console.log('Affichage des options de partage personnalisées');
        // Toujours afficher les options de partage sur desktop ou si l'API n'est pas disponible
        showShareOptions(text, qrUrl, title);
    }
    */
}

function showShareOptions(text, qrUrl, title) {
    // Supprimer le modal existant s'il y en a un
    const existingModal = document.getElementById('shareModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Créer un modal de partage avec différentes options
    const shareModal = document.createElement('div');
    shareModal.className = 'modal fade';
    shareModal.id = 'shareModal';
    shareModal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class='bx bx-share-alt'></i> Partager l'invitation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class='bx bx-info-circle'></i> Choisissez comment partager votre invitation
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <button class="btn btn-outline-primary w-100" onclick="shareViaWhatsApp('${text}')">
                                <i class='bx bxl-whatsapp'></i> WhatsApp
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-info w-100" onclick="shareViaTelegram('${text}')">
                                <i class='bx bxl-telegram'></i> Telegram
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-success w-100" onclick="shareViaEmail('${text}', '${title}')">
                                <i class='bx bx-envelope'></i> Email
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-secondary w-100" onclick="copyToClipboard('${text}')">
                                <i class='bx bx-copy'></i> Copier le lien
                            </button>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-outline-dark w-100" onclick="downloadQRCode('${text}')">
                                <i class='bx bx-download'></i> Télécharger QR Code
                            </button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class='bx bx-link'></i> Lien à partager : <code>${text}</code>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(shareModal);
    const modal = new bootstrap.Modal(shareModal);
    modal.show();

    // Nettoyer le modal après fermeture
    shareModal.addEventListener('hidden.bs.modal', function() {
        if (document.body.contains(shareModal)) {
            document.body.removeChild(shareModal);
        }
    });
}

function shareViaWhatsApp(text) {
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(`Lien d'invitation: ${text}`)}`;
    window.open(whatsappUrl, '_blank');
}

function shareViaTelegram(text) {
    const telegramUrl = `https://t.me/share/url?url=${encodeURIComponent(text)}&text=${encodeURIComponent('Lien d\'invitation')}`;
    window.open(telegramUrl, '_blank');
}

function shareViaEmail(text, title) {
    const emailUrl = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(`Lien d'invitation: ${text}`)}`;
    window.open(emailUrl);
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Afficher une notification de succès
        showNotification('Lien copié dans le presse-papiers !', 'success');
    }).catch(() => {
        // Fallback pour les navigateurs plus anciens
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Lien copié dans le presse-papiers !', 'success');
    });
}

function showNotification(message, type = 'info') {
    // Créer une notification toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    // Ajouter au conteneur de toasts
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Nettoyer après fermeture
    toast.addEventListener('hidden.bs.toast', function() {
        toastContainer.removeChild(toast);
    });
}

function downloadQRCode(text, filename = 'qr-code.png') {
    // Nettoyer le texte
    const cleanText = encodeURIComponent(text.trim());

    // URL pour télécharger le QR code
    const downloadUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${cleanText}&format=png&margin=2&ecc=H`;

    // Créer un lien temporaire pour le téléchargement
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = filename;
    link.target = '_blank';

    // Déclencher le téléchargement
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Fonction pour tester la génération de QR code
function testQRCode() {
    const testUrl = 'https://www.google.com';
    const testContainer = document.getElementById('testQRContainer');

    if (testContainer) {
        const qrImage = generateSimpleQRCode(testUrl, 200);
        testContainer.innerHTML = '';
        testContainer.appendChild(qrImage);

        const testInfo = document.createElement('div');
        testInfo.className = 'alert alert-info mt-2';
        testInfo.innerHTML = `<strong>Test QR Code:</strong> ${testUrl}`;
        testContainer.appendChild(testInfo);
    }
}

// Fonction pour valider une URL
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

// Fonction pour afficher des informations de debug
function debugQRCode(text) {
    console.log('=== DEBUG QR CODE ===');
    console.log('Texte original:', text);
    console.log('Longueur:', text.length);
    console.log('URL valide:', isValidUrl(text));
    console.log('Texte encodé:', encodeURIComponent(text));
    console.log('====================');
}

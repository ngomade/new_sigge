<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\laboratoires\LaboratoireInvitation;

echo "=== TEST DES URLs D'INVITATION ===\n\n";

// Créer une invitation de test
$invitation = new LaboratoireInvitation();
$invitation->token = 'test_token_123456789';

echo "Token: " . $invitation->token . "\n";
echo "URL courte (QR): " . $invitation->url_invitation . "\n";
echo "URL complète: " . $invitation->url_invitation_complete . "\n";

echo "\n=== CONFIGURATION ===\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "Base URL: " . rtrim(config('app.url'), '/') . "\n";

echo "\n=== TEST DE VALIDATION ===\n";
$shortUrl = $invitation->url_invitation;
$completeUrl = $invitation->url_invitation_complete;

echo "URL courte valide: " . (filter_var($shortUrl, FILTER_VALIDATE_URL) ? 'OUI' : 'NON') . "\n";
echo "URL complète valide: " . (filter_var($completeUrl, FILTER_VALIDATE_URL) ? 'OUI' : 'NON') . "\n";

echo "\n=== LONGUEUR DES URLs ===\n";
echo "URL courte: " . strlen($shortUrl) . " caractères\n";
echo "URL complète: " . strlen($completeUrl) . " caractères\n";

echo "\n=== RECOMMANDATIONS ===\n";
if (strlen($shortUrl) > 100) {
    echo "⚠️  L'URL courte est assez longue (" . strlen($shortUrl) . " caractères)\n";
} else {
    echo "✅ L'URL courte est de taille raisonnable (" . strlen($shortUrl) . " caractères)\n";
}

if (strlen($completeUrl) > 200) {
    echo "⚠️  L'URL complète est très longue (" . strlen($completeUrl) . " caractères)\n";
} else {
    echo "✅ L'URL complète est de taille raisonnable (" . strlen($completeUrl) . " caractères)\n";
}

echo "\n=== FIN DU TEST ===\n";

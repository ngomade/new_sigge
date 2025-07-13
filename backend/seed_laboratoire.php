<?php

/**
 * Script pour créer les données de test du module Laboratoire
 *
 * Ce script crée des données complètes et cohérentes pour présenter
 * toutes les fonctionnalités du module laboratoire.
 */

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🌐 Création des données pour le module Laboratoire...\n\n";

try {
    // Exécuter le seeder
    Artisan::call('db:seed', ['--class' => 'LaboratoireModuleSeeder']);

    echo "✅ Module Laboratoire créé avec succès !\n\n";
    echo "🔑 Comptes de test créés :\n";
    echo "   - Admin Labo 1: admin@labo1.ufd-tsi.com / password\n";
    echo "   - Admin Labo 2: admin@labo2.ufd-tsi.com / password\n";
    echo "   - Admin Labo 3: admin@labo3.ufd-tsi.com / password\n";
    echo "   - Chef Projet: marie.dupont@ufd-tsi.com / password\n";
    echo "   - Chercheur: pierre.martin@ufd-tsi.com / password\n";
    echo "   - Développeur: sophie.bernard@ufd-tsi.com / password\n";
    echo "   - Expert Cybersécurité: lucas.petit@ufd-tsi.com / password\n";
    echo "   - Technicien: emma.robert@ufd-tsi.com / password\n\n";

    echo "📊 Données créées :\n";
    echo "   - 3 Laboratoires (IA, Cybersécurité, Développement)\n";
    echo "   - 7 Rôles de laboratoire\n";
    echo "   - 8 Membres (personnel et utilisateurs)\n";
    echo "   - 4 Utilisateurs externes actifs\n";
    echo "   - 5 Projets (en cours, en attente, terminés)\n";
    echo "   - 10 Participants aux projets\n";
    echo "   - 7 Équipements avec différents états\n";
    echo "   - 3 Réservations d'équipements\n";
    echo "   - 2 Entretiens d'équipements\n";
    echo "   - 3 Publications scientifiques\n";
    echo "   - 3 Candidatures en attente\n\n";

    echo "🎯 Fonctionnalités à tester :\n";
    echo "   - Gestion des membres et rôles\n";
    echo "   - Gestion des projets et participants\n";
    echo "   - Gestion des équipements et réservations\n";
    echo "   - Gestion des entretiens et maintenance\n";
    echo "   - Gestion des publications\n";
    echo "   - Gestion des candidatures\n";
    echo "   - Tableaux de bord et statistiques\n";
    echo "   - Notifications et alertes\n\n";

    echo "🚀 Vous pouvez maintenant tester le module avec des données réalistes !\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de la création des données : " . $e->getMessage() . "\n";
    echo "Stack trace : " . $e->getTraceAsString() . "\n";
}

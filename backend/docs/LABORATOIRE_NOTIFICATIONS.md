# Système de Notifications et Alertes - Module Laboratoire

## Vue d'ensemble

Le système de notifications et alertes du module laboratoire permet de surveiller automatiquement les échéances de projets et les maintenances d'équipements, en envoyant des notifications appropriées aux membres concernés.

## Fonctionnalités

### 1. Alertes sur les échéances de projets
- **Surveillance automatique** : Vérification quotidienne des projets arrivant à échéance
- **Niveaux d'urgence** :
  - **Urgent** : ≤ 7 jours avant la fin
  - **Important** : ≤ 30 jours avant la fin
  - **Info** : > 30 jours avant la fin
- **Notifications** : Email et notifications en base de données
- **Destinataires** : Tous les membres actifs du laboratoire

### 2. Rappels pour la maintenance des équipements
- **Surveillance automatique** : Vérification des maintenances prévues
- **Niveaux d'urgence** :
  - **Urgent** : ≤ 3 jours avant la maintenance
  - **Important** : ≤ 30 jours avant la maintenance
  - **Info** : > 30 jours avant la maintenance
- **Notifications** : Email et notifications en base de données
- **Destinataires** : Responsables et administrateurs du laboratoire

## Architecture

### Modèles
- `LabNotif` : Table des notifications de laboratoire
- `ProjetLabo` : Projets de recherche
- `Equipements` : Équipements du laboratoire
- `LaboratoirePersLab` : Membres du laboratoire

### Services
- `LaboratoireAlertService` : Service principal de gestion des alertes
  - `checkProjetEcheances()` : Vérification des échéances de projets
  - `checkMaintenanceEquipements()` : Vérification des maintenances
  - `getAlertStats()` : Statistiques des alertes
  - `runAllChecks()` : Exécution de toutes les vérifications

### Notifications
- `ProjetEcheanceNotification` : Notification pour les échéances de projets
- `MaintenanceEquipementNotification` : Notification pour les maintenances

### Commandes Artisan
- `laboratoire:check-alerts` : Commande pour vérifier manuellement les alertes

## Configuration

### Tâches planifiées
Les alertes sont vérifiées automatiquement :
- **Quotidien** : À 8h00 du matin
- **En semaine** : Toutes les heures entre 9h00 et 18h00

### Configuration dans `app/Console/Kernel.php`
```php
// Vérifier les alertes des laboratoires tous les jours à 8h00
$schedule->command('laboratoire:check-alerts')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->runInBackground();

// Vérifier les alertes des laboratoires toutes les heures en semaine
$schedule->command('laboratoire:check-alerts')
    ->weekdays()
    ->hourly()
    ->between('09:00', '18:00')
    ->withoutOverlapping()
    ->runInBackground();
```

## Interface utilisateur

### Dashboard
- **Cartes de notifications** : Accès rapide aux notifications et alertes
- **Compteurs** : Affichage du nombre de notifications non lues
- **Liens directs** : Navigation vers les pages de gestion

### Page Notifications (`/laboratoires/{code_lab}/admin/notifications`)
- **Filtres** : Par type, statut, recherche textuelle
- **Actions** : Marquer comme lu, supprimer, marquer tout comme lu
- **Statistiques** : Vue d'ensemble des notifications
- **Actualisation automatique** : Toutes les 30 secondes

### Page Alertes (`/laboratoires/{code_lab}/admin/alertes`)
- **Alertes urgentes** : Projets ≤ 7 jours, maintenances ≤ 3 jours
- **Alertes importantes** : Projets ≤ 30 jours, maintenances ≤ 30 jours
- **Actions** : Vérification manuelle des alertes
- **Actualisation automatique** : Toutes les 5 minutes

### Composant de navigation
- **Badge de notifications** : Affichage du nombre de notifications non lues
- **Menu déroulant** : Aperçu des dernières notifications
- **Actions rapides** : Marquer tout comme lu, accès aux pages

## Routes

### Notifications
- `GET /laboratoires/{code_lab}/admin/notifications` : Liste des notifications
- `POST /laboratoires/{code_lab}/admin/notifications/{id}/mark-read` : Marquer comme lue
- `POST /laboratoires/{code_lab}/admin/notifications/mark-all-read` : Tout marquer comme lu
- `DELETE /laboratoires/{code_lab}/admin/notifications/{id}` : Supprimer

### Alertes
- `GET /laboratoires/{code_lab}/admin/alertes` : Alertes actives
- `POST /laboratoires/{code_lab}/admin/alertes/check` : Vérification manuelle

### API AJAX
- `GET /laboratoires/{code_lab}/admin/notifications/unread` : Notifications non lues
- `GET /laboratoires/{code_lab}/admin/notifications/unread-count` : Nombre de notifications non lues

## Utilisation

### Vérification manuelle des alertes
```bash
# Vérifier tous les laboratoires
php artisan laboratoire:check-alerts

# Vérifier un laboratoire spécifique
php artisan laboratoire:check-alerts --laboratoire=LAB001
```

### Surveillance des logs
```bash
# Surveiller les erreurs de vérification d'alertes
tail -f storage/logs/laravel.log | grep "Erreur lors de la vérification des alertes"
```

### Configuration des emails
Assurez-vous que la configuration email est correcte dans `.env` :
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Personnalisation

### Modifier les seuils d'alerte
Dans `app/Services/LaboratoireAlertService.php` :
```php
// Projets
if ($joursRestants <= 7) {        // Urgent
    $type = 'urgent';
} elseif ($joursRestants <= 14) {  // Important
    $type = 'warning';
}

// Maintenances
if ($joursRestants <= 3) {        // Urgent
    $type = 'urgent';
} elseif ($joursRestants <= 7) {   // Important
    $type = 'warning';
}
```

### Ajouter de nouveaux types de notifications
1. Créer une nouvelle classe de notification
2. Ajouter la logique dans `LaboratoireAlertService`
3. Mettre à jour les vues pour afficher le nouveau type

### Modifier la fréquence des vérifications
Dans `app/Console/Kernel.php`, ajuster les tâches planifiées selon vos besoins.

## Maintenance

### Nettoyage des anciennes notifications
```sql
-- Supprimer les notifications lues de plus de 30 jours
DELETE FROM lab_notif 
WHERE lu = 1 
AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Surveillance des performances
- Surveiller la taille de la table `lab_notif`
- Vérifier les logs d'erreur
- Monitorer l'utilisation des emails

## Sécurité

- Les notifications sont limitées au laboratoire de l'utilisateur
- Vérification des permissions avant affichage
- Protection CSRF sur toutes les actions
- Validation des données d'entrée

## Support

Pour toute question ou problème :
1. Vérifier les logs dans `storage/logs/laravel.log`
2. Tester la commande manuellement : `php artisan laboratoire:check-alerts`
3. Vérifier la configuration email
4. Contacter l'équipe de développement 

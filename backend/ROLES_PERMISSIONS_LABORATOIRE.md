# 🎭 Rôles et Permissions - Module Laboratoire

Ce document définit l'organisation des rôles et permissions pour le module laboratoire, permettant une gestion granulaire des accès selon les responsabilités de chaque membre.

## 📋 Rôles de Base

### 1. **Administrateur** 👑
**Description** : Gestion complète du laboratoire
**Responsabilités** :
- Gestion globale du laboratoire
- Gestion de tous les membres et rôles
- Accès à toutes les fonctionnalités
- Configuration du laboratoire

**Permissions** : `*` (Toutes les permissions)

---

### 2. **Chef de Projet** 📊
**Description** : Gestion des projets et équipes
**Responsabilités** :
- Création et gestion des projets
- Gestion des participants aux projets
- Coordination des équipes
- Suivi des équipements pour les projets

**Permissions** :
- `projets.view` - Voir les projets
- `projets.create` - Créer des projets
- `projets.edit` - Modifier les projets
- `projets.delete` - Supprimer les projets
- `projets.participants` - Gérer les participants
- `projets.documents` - Gérer les documents
- `equipements.view` - Voir les équipements
- `equipements.reserve` - Réserver des équipements
- `equipements.cancel_reservation` - Annuler des réservations
- `publications.view` - Voir les publications
- `publications.create` - Créer des publications
- `publications.edit` - Modifier les publications
- `membres.view` - Voir les membres
- `dashboard.view` - Accéder au tableau de bord
- `dashboard.stats` - Voir les statistiques

---

### 3. **Chercheur Principal** 🔬
**Description** : Conduite de recherches avancées
**Responsabilités** :
- Direction de projets de recherche
- Publication de résultats
- Utilisation d'équipements spécialisés
- Supervision de chercheurs juniors

**Permissions** :
- `projets.view` - Voir les projets
- `projets.create` - Créer des projets
- `projets.edit` - Modifier les projets
- `publications.view` - Voir les publications
- `publications.create` - Créer des publications
- `publications.edit` - Modifier les publications
- `equipements.view` - Voir les équipements
- `equipements.reserve` - Réserver des équipements
- `dashboard.view` - Accéder au tableau de bord
- `dashboard.stats` - Voir les statistiques

---

### 4. **Chercheur** 🧪
**Description** : Participation aux recherches
**Responsabilités** :
- Participation aux projets de recherche
- Publication de résultats
- Utilisation d'équipements
- Collaboration avec l'équipe

**Permissions** :
- `projets.view` - Voir les projets
- `publications.view` - Voir les publications
- `publications.create` - Créer des publications
- `equipements.view` - Voir les équipements
- `equipements.reserve` - Réserver des équipements
- `dashboard.view` - Accéder au tableau de bord

---

### 5. **Technicien** 🔧
**Description** : Maintenance et support technique
**Responsabilités** :
- Maintenance des équipements
- Gestion des réservations
- Support technique
- Entretien préventif

**Permissions** :
- `equipements.view` - Voir les équipements
- `equipements.maintenance` - Effectuer la maintenance
- `equipements.edit` - Modifier les équipements
- `reservations.view` - Voir les réservations
- `reservations.manage` - Gérer les réservations
- `dashboard.view` - Accéder au tableau de bord
- `dashboard.stats` - Voir les statistiques

---

### 6. **Secrétaire** 📝
**Description** : Gestion administrative
**Responsabilités** :
- Gestion des membres
- Traitement des candidatures
- Gestion des documents
- Support administratif

**Permissions** :
- `membres.view` - Voir les membres
- `membres.create` - Créer des membres
- `membres.edit` - Modifier les membres
- `candidatures.view` - Voir les candidatures
- `candidatures.process` - Traiter les candidatures
- `documents.view` - Voir les documents
- `documents.manage` - Gérer les documents
- `dashboard.view` - Accéder au tableau de bord
- `dashboard.stats` - Voir les statistiques

---

### 7. **Stagiaire** 🎓
**Description** : Apprentissage et support
**Responsabilités** :
- Apprentissage des processus
- Support aux projets
- Observation et participation limitée

**Permissions** :
- `projets.view` - Voir les projets
- `equipements.view` - Voir les équipements
- `publications.view` - Voir les publications
- `dashboard.view` - Accéder au tableau de bord

---

### 8. **Utilisateur Externe** 🌍
**Description** : Collaborateur externe
**Responsabilités** :
- Collaboration ponctuelle
- Utilisation limitée des ressources
- Participation aux projets

**Permissions** :
- `projets.view` - Voir les projets
- `equipements.view` - Voir les équipements
- `equipements.reserve` - Réserver des équipements
- `publications.view` - Voir les publications

## 🔐 Permissions Détaillées

### Permissions Projets
- `projets.view` - Consultation des projets
- `projets.create` - Création de nouveaux projets
- `projets.edit` - Modification des projets existants
- `projets.delete` - Suppression de projets
- `projets.participants` - Gestion des participants
- `projets.documents` - Gestion des documents de projet

### Permissions Équipements
- `equipements.view` - Consultation des équipements
- `equipements.create` - Ajout d'équipements
- `equipements.edit` - Modification d'équipements
- `equipements.delete` - Suppression d'équipements
- `equipements.reserve` - Réservation d'équipements
- `equipements.cancel_reservation` - Annulation de réservations
- `equipements.maintenance` - Gestion de la maintenance

### Permissions Publications
- `publications.view` - Consultation des publications
- `publications.create` - Création de publications
- `publications.edit` - Modification de publications
- `publications.delete` - Suppression de publications

### Permissions Membres
- `membres.view` - Consultation des membres
- `membres.create` - Ajout de nouveaux membres
- `membres.edit` - Modification des informations membres
- `membres.delete` - Suppression de membres
- `membres.roles` - Gestion des rôles

### Permissions Candidatures
- `candidatures.view` - Consultation des candidatures
- `candidatures.process` - Traitement des candidatures
- `candidatures.approve` - Approbation de candidatures
- `candidatures.reject` - Rejet de candidatures

### Permissions Tableau de Bord
- `dashboard.view` - Accès au tableau de bord
- `dashboard.stats` - Consultation des statistiques
- `dashboard.reports` - Génération de rapports

## 🎯 Matrice des Permissions

| Rôle | Projets | Équipements | Publications | Membres | Candidatures | Dashboard |
|------|---------|-------------|--------------|---------|--------------|-----------|
| **Administrateur** | ✅ Tous | ✅ Tous | ✅ Tous | ✅ Tous | ✅ Tous | ✅ Tous |
| **Chef de Projet** | ✅ CRUD | ✅ Voir/Réserver | ✅ CRUD | ✅ Voir | ❌ | ✅ Stats |
| **Chercheur Principal** | ✅ CRUD | ✅ Voir/Réserver | ✅ CRUD | ❌ | ❌ | ✅ Stats |
| **Chercheur** | ✅ Voir | ✅ Voir/Réserver | ✅ Créer | ❌ | ❌ | ✅ Voir |
| **Technicien** | ❌ | ✅ Maintenance | ❌ | ❌ | ❌ | ✅ Stats |
| **Secrétaire** | ❌ | ❌ | ❌ | ✅ CRUD | ✅ Traiter | ✅ Stats |
| **Stagiaire** | ✅ Voir | ✅ Voir | ✅ Voir | ❌ | ❌ | ✅ Voir |
| **Externe** | ✅ Voir | ✅ Voir/Réserver | ✅ Voir | ❌ | ❌ | ❌ |

**Légende** :
- ✅ Tous : Toutes les permissions
- ✅ CRUD : Créer, Lire, Modifier, Supprimer
- ✅ Voir : Consultation uniquement
- ✅ Voir/Réserver : Consultation et réservation
- ✅ Créer : Création uniquement
- ✅ Traiter : Traitement des candidatures
- ✅ Stats : Accès aux statistiques
- ❌ : Aucun accès

## 🔧 Implémentation Technique

### 1. Mise à jour du Trait LaboratoirePermissions
```php
// Dans app/Traits/LaboratoirePermissions.php
protected function getRolePermissions(): array
{
    return [
        'administrateur' => ['*'],
        'chef de projet' => [
            'projets.view', 'projets.create', 'projets.edit', 'projets.delete',
            'projets.participants', 'projets.documents',
            'equipements.view', 'equipements.reserve', 'equipements.cancel_reservation',
            'publications.view', 'publications.create', 'publications.edit',
            'membres.view', 'dashboard.view', 'dashboard.stats',
        ],
        // ... autres rôles
    ];
}
```

### 2. Utilisation dans les Contrôleurs
```php
// Exemple d'utilisation
public function index()
{
    $this->authorizeAction('projets.view');
    // ... logique du contrôleur
}

public function store(Request $request)
{
    $this->authorizeAction('projets.create');
    // ... logique de création
}
```

### 3. Vérification dans les Vues
```php
@if(auth()->user()->hasPermission('projets.create'))
    <a href="{{ route('projets.create') }}" class="btn btn-primary">
        Nouveau Projet
    </a>
@endif
```

## 📊 Gestion des Rôles

### Création d'un Nouveau Rôle
1. Ajouter le rôle dans la table `role_labo`
2. Définir les permissions dans `getRolePermissions()`
3. Mettre à jour la matrice des permissions
4. Tester les accès

### Modification des Permissions
1. Modifier le tableau `getRolePermissions()`
2. Mettre à jour la documentation
3. Tester les changements
4. Informer les utilisateurs

## 🚀 Prochaines Étapes

1. **Implémenter les permissions** dans tous les contrôleurs
2. **Créer des middlewares** pour chaque type de permission
3. **Ajouter des vérifications** dans les vues
4. **Tester exhaustivement** chaque rôle
5. **Documenter les cas d'usage** spécifiques

---

**Note** : Cette organisation permet une gestion flexible et sécurisée des accès au module laboratoire, adaptée aux besoins de chaque type d'utilisateur. 

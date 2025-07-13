# 🧪 Module Laboratoire - Données de Démonstration

Ce document explique comment utiliser les données de démonstration créées pour présenter le module laboratoire.

## 🚀 Installation des données

### Option 1 : Script automatique (Recommandé)
```bash
php seed_laboratoire.php
```

### Option 2 : Commande Artisan
```bash
php artisan db:seed --class=LaboratoireModuleSeeder
```

## 🔑 Comptes de test créés

### Administrateurs de laboratoires
- **Admin Labo 1 (IA)** : `admin@labo1.ufd-tsi.com` / `password`
- **Admin Labo 2 (Cybersécurité)** : `admin@labo2.ufd-tsi.com` / `password`
- **Admin Labo 3 (Développement)** : `admin@labo3.ufd-tsi.com` / `password`

### Membres du laboratoire
- **Marie Dupont** (Chef de projet) : `marie.dupont@ufd-tsi.com` / `password`
- **Pierre Martin** (Chercheur principal) : `pierre.martin@ufd-tsi.com` / `password`
- **Sophie Bernard** (Développeur) : `sophie.bernard@ufd-tsi.com` / `password`
- **Lucas Petit** (Expert cybersécurité) : `lucas.petit@ufd-tsi.com` / `password`
- **Emma Robert** (Technicien) : `emma.robert@ufd-tsi.com` / `password`

## 📊 Données créées

### 🏢 Laboratoires
1. **LABO1** - Laboratoire d'Intelligence Artificielle et Systèmes Intelligents
2. **LABO2** - Laboratoire de Cybersécurité et Sécurité des Réseaux
3. **LABO3** - Laboratoire de Développement Logiciel et Architecture Système

### 👥 Rôles de laboratoire
- Administrateur (gestion complète)
- Chef de Projet (gestion projets et équipes)
- Chercheur Principal (recherches avancées)
- Chercheur (participation aux recherches)
- Technicien (maintenance et support)
- Secrétaire (gestion administrative)
- Stagiaire (apprentissage)

### 📋 Projets
1. **PROJ001** - Système de reconnaissance faciale intelligent (en cours)
2. **PROJ002** - Analyse prédictive des cyberattaques (en cours)
3. **PROJ003** - Plateforme de développement collaboratif (en cours)
4. **PROJ004** - Optimisation des algorithmes ML (en attente)
5. **PROJ005** - Système de gestion IoT (terminé)

### 🔧 Équipements
- Serveur GPU NVIDIA DGX A100 (LABO1)
- Station de travail HP Z8 G4 (LABO1)
- Caméra HD Logitech Brio (LABO1)
- Analyseur de trafic réseau (LABO2)
- Serveur de test isolé (LABO2)
- Serveur de développement (LABO3)
- Station MacBook Pro (LABO3)

### 🌍 Utilisateurs externes
- **Aminata Koné** - Data Scientist (Entreprise Tech Solutions)
- **Moussa Traoré** - Expert Cybersécurité (CyberSec Pro)
- **Fatou Ouattara** - Chercheuse (Université Félix Houphouët-Boigny)
- **Kouassi Bamba** - Développeur Full-Stack (Startup Innovation Lab)

### 📚 Publications
- Optimisation des algorithmes de reconnaissance faciale
- Nouvelle approche pour la détection prédictive des cyberattaques
- Architecture d'une plateforme de développement collaboratif

### 📝 Candidatures en attente
- **Aïcha Diabaté** - Étudiante Master (IA)
- **Kouamé Yao** - Développeur junior (Cybersécurité)
- **N'Guessan Kouassi** - Stagiaire développeur (Développement)

## 🎯 Scénarios de démonstration

### 1. Gestion des participants aux projets
- Connectez-vous avec `admin@labo1.ufd-tsi.com`
- Allez dans "Projets" → "PROJ001" → "Gérer les participants"
- Testez l'ajout/suppression de participants
- Vérifiez que les participants déjà ajoutés n'apparaissent plus dans la liste

### 2. Gestion des équipements
- Connectez-vous avec `admin@labo1.ufd-tsi.com`
- Allez dans "Équipements"
- Testez les réservations d'équipements
- Testez la gestion des entretiens

### 3. Gestion des candidatures
- Connectez-vous avec `admin@labo1.ufd-tsi.com`
- Allez dans "Candidatures"
- Testez l'approbation/rejet des candidatures

### 4. Tableau de bord et statistiques
- Connectez-vous avec n'importe quel admin
- Consultez le tableau de bord avec les statistiques
- Testez les rapports et exports

### 5. Gestion des membres
- Testez l'ajout de nouveaux membres
- Testez l'affectation de rôles
- Testez la modification des informations

## 🔧 Fonctionnalités à tester

### ✅ Fonctionnalités principales
- [ ] Gestion des laboratoires
- [ ] Gestion des membres et rôles
- [ ] Gestion des projets
- [ ] Gestion des participants aux projets
- [ ] Gestion des équipements
- [ ] Gestion des réservations
- [ ] Gestion des entretiens
- [ ] Gestion des publications
- [ ] Gestion des candidatures
- [ ] Gestion des utilisateurs externes

### 📊 Fonctionnalités avancées
- [ ] Tableaux de bord avec statistiques
- [ ] Rapports et exports (PDF/Excel)
- [ ] Notifications et alertes
- [ ] Système de permissions
- [ ] Recherche et filtres
- [ ] Actions groupées

## 🐛 Résolution de problèmes

### Si les données ne se créent pas
1. Vérifiez que la base de données est configurée
2. Vérifiez que les migrations sont à jour
3. Vérifiez les logs dans `storage/logs/laravel.log`

### Si les relations ne s'affichent pas
1. Vérifiez que les modèles ont les bonnes relations
2. Vérifiez que les accesseurs sont définis
3. Vérifiez les logs pour les erreurs de chargement

## 📞 Support

Pour toute question ou problème, consultez :
- Les logs Laravel dans `storage/logs/`
- La documentation du module
- Les commentaires dans le code

---

**Note** : Ces données sont créées uniquement à des fins de démonstration. En production, utilisez des données réelles et sécurisées. 

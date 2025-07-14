# Diagramme d'Activité - Module Laboratoire

## 1. Connexion et Authentification

```mermaid
graph TD
    A[Début] --> B[Accès à la page de connexion]
    B --> C{Saisie des identifiants}
    C --> D[Validation des données]
    D --> E{Identifiants corrects?}
    E -->|Non| F[Affichage erreur]
    F --> C
    E -->|Oui| G[Vérification des permissions]
    G --> H{Utilisateur autorisé?}
    H -->|Non| I[Redirection page d'accueil]
    H -->|Oui| J[Création de session]
    J --> K[Redirection tableau de bord]
    K --> L[Fin]
```

## 2. Gestion des Projets

```mermaid
graph TD
    A[Début] --> B[Accès gestion projets]
    B --> C{Type d'action?}
    
    C -->|Créer| D[Saisie informations projet]
    C -->|Modifier| E[Sélection projet existant]
    C -->|Consulter| F[Affichage liste projets]
    C -->|Supprimer| G[Sélection projet à supprimer]
    
    D --> H[Validation données]
    E --> I[Modification données]
    G --> J[Confirmation suppression]
    
    H --> K{Données valides?}
    I --> L{Modifications valides?}
    J --> M{Confirmation?}
    
    K -->|Non| N[Affichage erreurs]
    L -->|Non| O[Affichage erreurs]
    M -->|Non| P[Annulation]
    
    K -->|Oui| Q[Sauvegarde projet]
    L -->|Oui| R[Mise à jour projet]
    M -->|Oui| S[Suppression projet]
    
    N --> D
    O --> I
    P --> B
    Q --> T[Notification succès]
    R --> T
    S --> T
    T --> U[Fin]
    
    F --> V[Filtrage et recherche]
    V --> W[Affichage résultats]
    W --> X[Fin]
```

## 3. Gestion des Équipements

```mermaid
graph TD
    A[Début] --> B[Accès gestion équipements]
    B --> C{Action souhaitée?}
    
    C -->|Réserver| D[Sélection équipement]
    C -->|Maintenir| E[Sélection équipement]
    C -->|Consulter| F[Affichage liste équipements]
    C -->|Ajouter| G[Saisie informations équipement]
    
    D --> H{Vérification disponibilité}
    E --> I[Saisie détails maintenance]
    G --> J[Validation données équipement]
    
    H -->|Disponible| K[Saisie période réservation]
    H -->|Non disponible| L[Affichage message indisponibilité]
    
    I --> M[Planification maintenance]
    J --> N{Données valides?}
    K --> O[Validation période]
    
    N -->|Non| P[Affichage erreurs]
    N -->|Oui| Q[Enregistrement équipement]
    
    O -->|Valide| R[Création réservation]
    O -->|Invalide| S[Affichage erreur période]
    
    M --> T[Programmation entretien]
    Q --> U[Notification succès]
    R --> V[Notification réservation]
    T --> W[Notification maintenance]
    
    L --> X[Fin]
    P --> G
    S --> K
    U --> Y[Fin]
    V --> Y
    W --> Y
    
    F --> Z[Filtrage par statut]
    Z --> AA[Affichage résultats]
    AA --> BB[Fin]
```

## 4. Gestion des Membres

```mermaid
graph TD
    A[Début] --> B[Accès gestion membres]
    B --> C{Action souhaitée?}
    
    C -->|Ajouter membre| D[Saisie informations membre]
    C -->|Modifier| E[Sélection membre]
    C -->|Supprimer| F[Sélection membre]
    C -->|Affecter rôle| G[Sélection membre et rôle]
    
    D --> H[Validation données]
    E --> I[Modification données]
    F --> J[Confirmation suppression]
    G --> K[Vérification permissions]
    
    H --> L{Données valides?}
    I --> M{Modifications valides?}
    J --> N{Confirmation?}
    K --> O{Permissions suffisantes?}
    
    L -->|Non| P[Affichage erreurs]
    M -->|Non| Q[Affichage erreurs]
    N -->|Non| R[Annulation]
    O -->|Non| S[Refus permission]
    
    L -->|Oui| T[Création compte membre]
    M -->|Oui| U[Mise à jour membre]
    N -->|Oui| V[Suppression membre]
    O -->|Oui| W[Affectation rôle]
    
    P --> D
    Q --> I
    R --> B
    S --> X[Notification refus]
    
    T --> Y[Envoi invitation]
    U --> Z[Notification mise à jour]
    V --> AA[Notification suppression]
    W --> BB[Notification affectation]
    
    X --> CC[Fin]
    Y --> DD[Fin]
    Z --> DD
    AA --> DD
    BB --> DD
```

## 5. Traitement des Candidatures

```mermaid
graph TD
    A[Début] --> B[Réception candidature]
    B --> C[Enregistrement candidature]
    C --> D[Notification administrateur]
    D --> E[Consultation candidature]
    E --> F{Évaluation candidature}
    
    F -->|Approuver| G[Validation candidature]
    F -->|Rejeter| H[Refus candidature]
    F -->|Demander plus d'infos| I[Demande compléments]
    
    G --> J[Création compte externe]
    J --> K[Affectation rôle temporaire]
    K --> L[Envoi notification approbation]
    
    H --> M[Enregistrement motif refus]
    M --> N[Envoi notification refus]
    
    I --> O[Envoi demande compléments]
    O --> P[Attente réponse candidat]
    P --> Q{Réponse reçue?}
    Q -->|Oui| R[Évaluation compléments]
    Q -->|Non| S[Expiration délai]
    
    R --> T{Compléments satisfaisants?}
    T -->|Oui| G
    T -->|Non| H
    
    S --> H
    
    L --> U[Fin]
    N --> U
    U --> V[Fin]
```

## 6. Système de Notifications et Alertes

```mermaid
graph TD
    A[Début] --> B[Déclenchement vérification]
    B --> C[Vérification échéances projets]
    C --> D[Vérification maintenances équipements]
    
    D --> E{Alertes détectées?}
    E -->|Non| F[Fin vérification]
    E -->|Oui| G[Génération notifications]
    
    G --> H[Création notification base]
    H --> I[Envoi email notification]
    I --> J[Stockage notification]
    
    J --> K{Type d'alerte?}
    K -->|Urgente| L[Notification immédiate]
    K -->|Importante| M[Notification différée]
    K -->|Information| N[Notification planifiée]
    
    L --> O[Envoi tous les membres]
    M --> P[Envoi responsables]
    N --> Q[Envoi administrateurs]
    
    O --> R[Marquage notification envoyée]
    P --> R
    Q --> R
    
    R --> S[Fin]
    F --> S
```

## 7. Génération de Rapports

```mermaid
graph TD
    A[Début] --> B[Accès génération rapports]
    B --> C{Sélection type rapport}
    
    C -->|Projets| D[Filtrage projets]
    C -->|Équipements| E[Filtrage équipements]
    C -->|Membres| F[Filtrage membres]
    C -->|Statistiques| G[Calcul statistiques]
    
    D --> H[Collecte données projets]
    E --> I[Collecte données équipements]
    F --> J[Collecte données membres]
    G --> K[Agrégation données]
    
    H --> L[Formatage données]
    I --> L
    J --> L
    K --> L
    
    L --> M{Sélection format}
    M -->|PDF| N[Génération PDF]
    M -->|Excel| O[Génération Excel]
    M -->|Word| P[Génération Word]
    
    N --> Q[Téléchargement fichier]
    O --> Q
    P --> Q
    
    Q --> R[Fin]
```

## 8. Flux Principal d'Utilisation

```mermaid
graph TD
    A[Début] --> B[Connexion utilisateur]
    B --> C[Vérification authentification]
    C --> D[Vérification permissions]
    D --> E[Accès tableau de bord]
    
    E --> F{Action utilisateur}
    F -->|Gérer projets| G[Module projets]
    F -->|Gérer équipements| H[Module équipements]
    F -->|Gérer membres| I[Module membres]
    F -->|Consulter rapports| J[Module rapports]
    F -->|Gérer candidatures| K[Module candidatures]
    
    G --> L[Exécution action projet]
    H --> M[Exécution action équipement]
    I --> N[Exécution action membre]
    J --> O[Génération rapport]
    K --> P[Traitement candidature]
    
    L --> Q[Validation action]
    M --> Q
    N --> Q
    O --> Q
    P --> Q
    
    Q --> R{Action réussie?}
    R -->|Oui| S[Notification succès]
    R -->|Non| T[Notification erreur]
    
    S --> U[Retour tableau de bord]
    T --> U
    
    U --> V{Continuer?}
    V -->|Oui| F
    V -->|Non| W[Déconnexion]
    W --> X[Fin]
```

Ces diagrammes d'activité montrent les flux principaux de votre module laboratoire, incluant :
- L'authentification et la gestion des permissions
- La gestion des projets avec validation
- La gestion des équipements avec réservation et maintenance
- La gestion des membres et des rôles
- Le traitement des candidatures
- Le système de notifications et alertes
- La génération de rapports
- Le flux principal d'utilisation

Vous pouvez utiliser ces diagrammes dans votre rapport en les adaptant selon vos besoins spécifiques ou en les simplifiant pour mettre l'accent sur les aspects les plus importants de votre module. 

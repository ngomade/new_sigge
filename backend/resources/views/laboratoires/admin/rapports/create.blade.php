@extends('laboratoires.public.layout')

@section('title', 'Créer un Rapport - ' . $laboratoire->nom_labo)

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header moderne -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-semibold text-dark">Créer un Rapport</h1>
            <p class="text-muted mb-0">{{ $laboratoire->label_labo }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laboratoires.admin.rapports', $laboratoire->code_lab) }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <!-- Formulaire de création -->
            <div class="card border-0 bg-white shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Informations du Rapport</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('laboratoires.admin.rapports.store', $laboratoire->code_lab) }}" method="POST" id="rapportForm">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="titre" class="form-label fw-medium">Titre du rapport <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('titre') is-invalid @enderror"
                                       id="titre" name="titre" value="{{ old('titre') }}"
                                       placeholder="Ex: Rapport d'activité 2024" required>
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="type_rapport" class="form-label fw-medium">Format <span class="text-danger">*</span></label>
                                <select class="form-select @error('type_rapport') is-invalid @enderror"
                                        id="type_rapport" name="type_rapport" required>
                                    <option value="">Choisir un format</option>
                                    <option value="pdf" {{ old('type_rapport') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="word" {{ old('type_rapport') == 'word' ? 'selected' : '' }}>Word (.docx)</option>
                                </select>
                                @error('type_rapport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-medium">Description (optionnel)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="Brève description du rapport...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="contenu" class="form-label fw-medium">Contenu du rapport <span class="text-danger">*</span></label>
                            <div class="border rounded-3">
                                <div class="bg-light border-bottom p-3">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')">
                                            <i class="bi bi-type-bold"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')">
                                            <i class="bi bi-type-italic"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')">
                                            <i class="bi bi-type-underline"></i>
                                        </button>
                                        <div class="vr mx-2"></div>
                                        <button type="button" class="btn btn-outline-secondary" onclick="insertText('h2')">
                                            <i class="bi bi-type-h1"></i> Titre
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="insertText('h3')">
                                            <i class="bi bi-type-h2"></i> Sous-titre
                                        </button>
                                        <div class="vr mx-2"></div>
                                        <button type="button" class="btn btn-outline-secondary" onclick="insertText('list')">
                                            <i class="bi bi-list-ul"></i> Liste
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="insertText('table')">
                                            <i class="bi bi-table"></i> Tableau
                                        </button>
                                    </div>
                                </div>
                                <textarea class="form-control @error('contenu') is-invalid @enderror"
                                          id="contenu" name="contenu" rows="20"
                                          placeholder="Rédigez le contenu de votre rapport ici...&#10;&#10;Vous pouvez utiliser les boutons ci-dessus pour formater votre texte.&#10;&#10;Exemple de structure :&#10;&#10;1. Introduction&#10;2. Méthodologie&#10;3. Résultats&#10;4. Conclusion"
                                          required>{{ old('contenu') }}</textarea>
                                @error('contenu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('laboratoires.admin.rapports', $laboratoire->code_lab) }}" class="btn btn-light">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-file-earmark-plus me-1"></i>Créer le Rapport
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Aide et modèles -->
            <div class="card border-0 bg-white shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Modèles de Rapports</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <button type="button" class="list-group-item list-group-item-action border-0 py-3"
                                onclick="loadTemplate('activite')">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-activity text-white small"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Rapport d'Activité</div>
                                    <small class="text-muted">Rapport mensuel/trimestriel</small>
                                </div>
                            </div>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action border-0 py-3"
                                onclick="loadTemplate('projet')">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-folder text-white small"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Rapport de Projet</div>
                                    <small class="text-muted">Suivi et résultats</small>
                                </div>
                            </div>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action border-0 py-3"
                                onclick="loadTemplate('equipement')">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-tools text-white small"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Rapport Équipements</div>
                                    <small class="text-muted">État et maintenance</small>
                                </div>
                            </div>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action border-0 py-3"
                                onclick="loadTemplate('bilan')">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-graph-up text-white small"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Bilan Annuel</div>
                                    <small class="text-muted">Synthèse complète</small>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Conseils -->
            <div class="card border-0 bg-white shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">Conseils de Rédaction</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-medium text-primary">Structure recommandée :</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Introduction et contexte</li>
                            <li>• Méthodologie utilisée</li>
                            <li>• Résultats obtenus</li>
                            <li>• Discussion et analyse</li>
                            <li>• Conclusion et perspectives</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-medium text-success">Bonnes pratiques :</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Utilisez des titres clairs</li>
                            <li>• Structurez avec des listes</li>
                            <li>• Incluez des données chiffrées</li>
                            <li>• Ajoutez des tableaux si nécessaire</li>
                        </ul>
                    </div>
                    <div>
                        <h6 class="fw-medium text-info">Variables disponibles :</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• {{ $laboratoire->label_labo }} - Nom du laboratoire</li>
                            <li>• {{ $laboratoire->code_lab }} - Code du laboratoire</li>
                            <li>• Date de génération automatique</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fonctions pour l'éditeur de texte
function formatText(command) {
    const textarea = document.getElementById('contenu');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    let formattedText = '';
    switch(command) {
        case 'bold':
            formattedText = `**${selectedText}**`;
            break;
        case 'italic':
            formattedText = `*${selectedText}*`;
            break;
        case 'underline':
            formattedText = `__${selectedText}__`;
            break;
    }

    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + 2, start + 2 + selectedText.length);
}

function insertText(type) {
    const textarea = document.getElementById('contenu');
    const cursorPos = textarea.selectionStart;
    let insertText = '';

    switch(type) {
        case 'h2':
            insertText = '\n## Titre de section\n';
            break;
        case 'h3':
            insertText = '\n### Sous-titre\n';
            break;
        case 'list':
            insertText = '\n- Premier élément\n- Deuxième élément\n- Troisième élément\n';
            break;
        case 'table':
            insertText = '\n| Colonne 1 | Colonne 2 | Colonne 3 |\n|-----------|-----------|-----------|\n| Donnée 1  | Donnée 2  | Donnée 3  |\n';
            break;
    }

    textarea.value = textarea.value.substring(0, cursorPos) + insertText + textarea.value.substring(cursorPos);
    textarea.focus();
    textarea.setSelectionRange(cursorPos + insertText.length, cursorPos + insertText.length);
}

// Modèles de rapports
function loadTemplate(type) {
    const textarea = document.getElementById('contenu');
    const titreInput = document.getElementById('titre');

    let template = '';
    let titre = '';

    switch(type) {
        case 'activite':
            titre = 'Rapport d\'Activité - ' + new Date().toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
            template = `## Introduction

Ce rapport présente les activités du laboratoire ${@json($laboratoire->label_labo)} pour la période en cours.

## Activités Principales

### Projets en Cours
- Projet 1 : Description et avancement
- Projet 2 : Description et avancement

### Publications
- Publication 1 : Titre, auteurs, revue
- Publication 2 : Titre, auteurs, revue

### Équipements
- État des équipements
- Maintenances effectuées
- Nouvelles acquisitions

## Résultats et Perspectives

### Résultats Obtenus
- Résultat 1
- Résultat 2

### Perspectives
- Objectifs pour la prochaine période
- Projets futurs

## Conclusion

Synthèse des activités et perspectives d'évolution.`;
            break;

        case 'projet':
            titre = 'Rapport de Projet - [Nom du Projet]';
            template = `## Présentation du Projet

### Contexte et Objectifs
Description du contexte et des objectifs du projet.

### Méthodologie
Description de la méthodologie utilisée.

## Avancement

### Tâches Réalisées
- Tâche 1 : Statut et résultats
- Tâche 2 : Statut et résultats

### Tâches en Cours
- Tâche 3 : Progression actuelle
- Tâche 4 : Progression actuelle

### Difficultés Rencontrées
- Difficulté 1 : Description et solutions
- Difficulté 2 : Description et solutions

## Résultats

### Résultats Obtenus
- Résultat 1 : Description détaillée
- Résultat 2 : Description détaillée

### Analyse des Résultats
Analyse et interprétation des résultats obtenus.

## Planning et Perspectives

### Prochaines Étapes
- Étape 1 : Planning et délais
- Étape 2 : Planning et délais

### Risques et Opportunités
Identification des risques et opportunités.

## Conclusion

Synthèse du projet et recommandations.`;
            break;

        case 'equipement':
            titre = 'Rapport Équipements - ' + new Date().toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
            template = `## État des Équipements

### Équipements Disponibles
- Équipement 1 : État et disponibilité
- Équipement 2 : État et disponibilité

### Équipements en Maintenance
- Équipement 3 : Type de maintenance, durée estimée
- Équipement 4 : Type de maintenance, durée estimée

### Équipements Hors Service
- Équipement 5 : Problème identifié, solution prévue
- Équipement 6 : Problème identifié, solution prévue

## Utilisation des Équipements

### Statistiques d'Utilisation
- Heures d'utilisation par équipement
- Taux d'occupation
- Périodes de pointe

### Réservations
- Nombre de réservations
- Utilisateurs principaux
- Types d'utilisation

## Maintenance

### Maintenances Préventives
- Maintenances effectuées
- Maintenances programmées
- Coûts de maintenance

### Maintenances Correctives
- Interventions réalisées
- Pièces remplacées
- Coûts des réparations

## Acquisitions et Renouvellements

### Nouvelles Acquisitions
- Équipements acquis
- Justification des achats
- Budget utilisé

### Renouvellements Prévus
- Équipements à renouveler
- Planning de renouvellement
- Budget estimé

## Recommandations

### Améliorations Suggérées
- Recommandation 1
- Recommandation 2

### Besoins Futurs
- Besoins identifiés
- Priorités

## Conclusion

Synthèse de l'état des équipements et recommandations.`;
            break;

        case 'bilan':
            titre = 'Bilan Annuel - ' + new Date().getFullYear();
            template = `## Bilan Annuel ${new Date().getFullYear()}

### Introduction
Présentation du bilan annuel du laboratoire ${@json($laboratoire->label_labo)}.

## Activités de Recherche

### Projets Réalisés
- Projet 1 : Résultats et impacts
- Projet 2 : Résultats et impacts

### Publications
- Articles publiés
- Communications en congrès
- Ouvrages et chapitres

### Collaborations
- Partenariats nationaux
- Partenariats internationaux
- Réseaux de recherche

## Ressources Humaines

### Équipe
- Effectifs permanents
- Doctorants et post-doctorants
- Stagiaires et visiteurs

### Formations
- Formations suivies
- Encadrement doctoral
- Enseignements

## Moyens et Équipements

### Équipements
- État du parc d'équipements
- Nouvelles acquisitions
- Maintenances effectuées

### Budget
- Budget de fonctionnement
- Budget de recherche
- Autofinancement

## Valorisation

### Transfert de Technologie
- Brevets déposés
- Licences accordées
- Créations d'entreprises

### Diffusion
- Conférences organisées
- Séminaires et workshops
- Actions de médiation

## Perspectives

### Objectifs pour l'Année à Venir
- Objectif 1
- Objectif 2

### Défis et Opportunités
- Défis identifiés
- Opportunités à saisir

## Conclusion

Synthèse du bilan et perspectives d'évolution.`;
            break;
    }

    titreInput.value = titre;
    textarea.value = template;
    textarea.focus();
}

// Gestion du formulaire
document.getElementById('rapportForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Génération en cours...';
});
</script>

<style>
.card {
    border-radius: 12px;
    transition: all 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.fw-semibold {
    font-weight: 600;
}

.text-muted {
    color: #6c757d !important;
}

#contenu {
    border: none;
    resize: vertical;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.6;
}

.btn-group .btn {
    border-radius: 6px !important;
}

.vr {
    width: 1px;
    background-color: #dee2e6;
}
</style>
@endsection

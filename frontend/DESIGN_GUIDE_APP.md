# Guide de refonte visuelle de l’application

## 1. Inspiration visuelle

Le logo de l’application s’inspire d’une palette naturelle, professionnelle et moderne, avec des tons verts, dorés et neutres. La refonte des vues Blade doit rester cohérente avec cette identité visuelle.

## 2. Palette de couleurs globale

```css
:root {
  --app-primary: #0E8F74;
  --app-primary-dark: #113D35;
  --app-primary-soft: #DDF6EF;

  --app-accent: #F5C84C;
  --app-accent-soft: #FFF0B8;

  --app-bg: #F5F7F1;
  --app-surface: #FFFFFF;
  --app-surface-alt: #EEF5F2;

  --app-text: #1B2424;
  --app-text-muted: #647272;
  --app-border: #D9E6E1;

  --app-success: #2FA77F;
  --app-warning: #E7B94D;
  --app-danger: #D95F5F;

  --app-shadow: rgba(17, 61, 53, 0.12);
}
```

## 3. Règles de design

### 3.1. Palette dominante

- Vert principal : identifie l’application et les éléments prioritaires
- Vert foncé : pour la navigation, sidebar et zones structurantes
- Or / jaune : pour les accents visuels et éléments de mise en avant
- Fond clair : pour la lisibilité et le confort visuel

### 3.2. Hiérarchie visuelle

- Les titres doivent être en vert foncé ou noir profond
- Les éléments d’action importants doivent utiliser le vert principal
- Les accents marketing ou de signalisation utilisent le jaune doré
- Le fond général doit rester très clair pour éviter une impression lourde

### 3.3. UI recommandée

- Sidebar / navigation : vert foncé
- Header : fond blanc avec bordure légère
- Cartes : fond blanc, ombre douce, bordure minimaliste
- Boutons primaires : vert principal
- Boutons secondaires : jaune doré
- Badges / status : utiliser des tons cohérents avec la palette

## 4. Styles CSS de base

```css
body {
  background: var(--app-bg);
  color: var(--app-text);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
  background: var(--app-surface);
  border: 1px solid var(--app-border);
  border-radius: 16px;
  box-shadow: 0 8px 24px var(--app-shadow);
}

.btn-primary {
  background: var(--app-primary);
  border-color: var(--app-primary);
  color: #fff;
}

.btn-primary:hover {
  background: var(--app-primary-dark);
  border-color: var(--app-primary-dark);
}

.btn-secondary {
  background: var(--app-accent);
  border-color: var(--app-accent);
  color: var(--app-text);
}

.btn-secondary:hover {
  background: #e8bc3d;
  border-color: #e8bc3d;
}

.text-primary-app {
  color: var(--app-primary) !important;
}

.bg-primary-app {
  background: var(--app-primary) !important;
}

.bg-soft-app {
  background: var(--app-primary-soft) !important;
}
```

## 5. Direction visuelle recommandée

### Navigation

- Fond : vert foncé (#113D35)
- Texte : blanc
- Éléments actifs : vert principal ou jaune doré discret
- Survol : vert plus lumineux

### Contenus

- Fond clair neutre
- Titres forts, lisibles
- Formulaires avec bordures douces
- Tableau avec lignes légères et accents sur les entêtes

### Éléments de mise en avant

- statistiques
- cartes KPI
- badges
- alertes

Utiliser un mélange équilibré entre :

- vert principal pour la structure
- jaune doré pour le dynamisme
- blanc / beige clair pour la sobriété

## 6. Recommandation finale

Pour la refonte des vues Blade, la meilleure direction est :

- fond clair et propre
- structure admin en vert profond
- accents dorés subtils
- boutons et actions en vert principal
- interfaces lisibles, premium et professionnelles

Cette direction est cohérente avec le logo et convient à une application de gestion / administration / laboratoire / concours.

## 7. Exemple de CSS complet

```css
:root {
  --app-primary: #0E8F74;
  --app-primary-dark: #113D35;
  --app-primary-soft: #DDF6EF;
  --app-accent: #F5C84C;
  --app-accent-soft: #FFF0B8;
  --app-bg: #F5F7F1;
  --app-surface: #FFFFFF;
  --app-surface-alt: #EEF5F2;
  --app-text: #1B2424;
  --app-text-muted: #647272;
  --app-border: #D9E6E1;
  --app-success: #2FA77F;
  --app-warning: #E7B94D;
  --app-danger: #D95F5F;
}

body {
  background: var(--app-bg);
  color: var(--app-text);
}

.sidebar {
  background: var(--app-primary-dark);
  color: #fff;
}

.sidebar a:hover {
  background: var(--app-primary);
}

.card {
  background: var(--app-surface);
  border: 1px solid var(--app-border);
  border-radius: 14px;
  box-shadow: 0 8px 18px rgba(17, 61, 53, 0.08);
}

.btn-primary {
  background: var(--app-primary);
  border-color: var(--app-primary);
}

.btn-primary:hover {
  background: var(--app-primary-dark);
  border-color: var(--app-primary-dark);
}

.btn-warning {
  background: var(--app-accent);
  border-color: var(--app-accent);
  color: var(--app-text);
}

.badge-success {
  background: var(--app-success);
}

.badge-warning {
  background: var(--app-warning);
  color: #1B2424;
}
```

## 8. Résumé ultra court

Palette recommandée : vert + or + fond clair + texte foncé.

C’est la meilleure combinaison pour rester fidèle au logo tout en donnant une interface moderne, crédible et professionnelle.

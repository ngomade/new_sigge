<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport {{ ucfirst($type) }} - {{ $laboratoire->label_labo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h2 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            min-width: 200px;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>Rapport {{ ucfirst($type) }}</h1>
        <p><strong>Laboratoire :</strong> {{ $laboratoire->label_labo }}</p>
        <p><strong>Code :</strong> {{ $laboratoire->code_lab }}</p>
        <p><strong>Date de génération :</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if($type === 'general')
        <!-- Rapport général -->
        <div class="info-section">
            <h2>Vue d'ensemble</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $data['membres'] }}</div>
                    <div class="stat-label">Membres actifs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['projets'] }}</div>
                    <div class="stat-label">Projets de recherche</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['equipements'] }}</div>
                    <div class="stat-label">Équipements</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['publications'] }}</div>
                    <div class="stat-label">Publications</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['externes'] }}</div>
                    <div class="stat-label">Utilisateurs externes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $data['reservations'] }}</div>
                    <div class="stat-label">Réservations totales</div>
                </div>
            </div>
        </div>
    @endif

    @if($type === 'membres')
        <!-- Rapport membres -->
        <div class="info-section">
            <h2>Liste des Membres</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Personnel</th>
                        <th>Type</th>
                        <th>Rôle</th>
                        <th>Date d'affectation</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['membres'] as $membre)
                    <tr>
                        <td>{{ $membre->persLab->id_pers_lab ?? 'N/A' }}</td>
                        <td>{{ $membre->persLab->type_pers_lab ?? 'N/A' }}</td>
                        <td>{{ $membre->roleLabo->lib_rl ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($membre->date_affectation)->format('d/m/Y') }}</td>
                        <td>{{ $membre->date_fin_affectation ? \Carbon\Carbon::parse($membre->date_fin_affectation)->format('d/m/Y') : 'En cours' }}</td>
                        <td>{{ ucfirst($membre->statut) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($type === 'projets')
        <!-- Rapport projets -->
        <div class="info-section">
            <h2>Projets de Recherche</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                        <th>Budget</th>
                        <th>Participants</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['projets'] as $projet)
                    <tr>
                        <td>{{ $projet->theme_projet }}</td>
                        <td>{{ Str::limit($projet->description_projet, 100) }}</td>
                        <td>{{ \Carbon\Carbon::parse($projet->debut_projet)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($projet->fin_projet)->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($projet->statut_projet) }}</td>
                        <td>{{ $projet->budget ?? 'N/A' }}</td>
                        <td>{{ $projet->participants->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($type === 'equipements')
        <!-- Rapport équipements -->
        <div class="info-section">
            <h2>Inventaire des Équipements</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Numéro de série</th>
                        <th>Statut</th>
                        <th>Date d'acquisition</th>
                        <th>Prix d'acquisition</th>
                        <th>Entretiens</th>
                        <th>Réservations</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['equipements'] as $equipement)
                    <tr>
                        <td>{{ $equipement->nom_equip }}</td>
                        <td>{{ Str::limit($equipement->desc_equip, 100) }}</td>
                        <td>{{ $equipement->ref_equip }}</td>
                        <td>{{ ucfirst($equipement->etat) }}</td>
                        <td>{{ \Carbon\Carbon::parse($equipement->date_achat)->format('d/m/Y') }}</td>
                        <td>{{ $equipement->valeur ?? 'N/A' }}</td>
                        <td>{{ $equipement->entretiens->count() }}</td>
                        <td>{{ $equipement->reservations->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($type === 'utilisations')
        <!-- Rapport utilisations -->
        <div class="info-section">
            <h2>Historique des Utilisations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Équipement</th>
                        <th>Utilisateur</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Durée (heures)</th>
                        <th>Statut</th>
                        <th>Motif</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['reservations'] as $reservation)
                    @php
                        $debut = \Carbon\Carbon::parse($reservation->debut_reserv);
                        $fin = \Carbon\Carbon::parse($reservation->fin_reserv);
                        $duree = $debut->diffInHours($fin);
                    @endphp
                    <tr>
                        <td>{{ $reservation->equipement->nom_equip ?? 'N/A' }}</td>
                        <td>{{ $reservation->personnel->id_pers_lab ?? 'N/A' }}</td>
                        <td>{{ $debut->format('d/m/Y H:i') }}</td>
                        <td>{{ $fin->format('d/m/Y H:i') }}</td>
                        <td>{{ $duree }}</td>
                        <td>{{ ucfirst($reservation->statut) }}</td>
                        <td>{{ Str::limit($reservation->motif ?? 'N/A', 50) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Pied de page -->
    <div class="footer">
        <p>Rapport généré automatiquement par le système de gestion des laboratoires</p>
        <p>Page 1</p>
    </div>
</body>
</html>

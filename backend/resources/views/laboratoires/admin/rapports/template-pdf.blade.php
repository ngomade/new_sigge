<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titre }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
            font-size: 12px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .info-section {
            margin-bottom: 30px;
            padding: 0 30px;
        }

        .info-section h2 {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .info-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            margin: 20px 0 10px 0;
        }

        .info-section p {
            margin-bottom: 10px;
            text-align: justify;
        }

        .info-section ul, .info-section ol {
            margin: 10px 0 10px 20px;
        }

        .info-section li {
            margin-bottom: 5px;
        }

        .lab-info {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .lab-info h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .lab-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .lab-info-item {
            display: flex;
            align-items: center;
        }

        .lab-info-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 120px;
        }

        .lab-info-value {
            color: #2d3748;
        }

        .content-section {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .content-section h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f7fafc;
            border-top: 1px solid #e2e8f0;
            padding: 15px 30px;
            text-align: center;
            font-size: 10px;
            color: #718096;
        }

        .page-number {
            position: fixed;
            bottom: 15px;
            right: 30px;
            font-size: 10px;
            color: #718096;
        }

        .page-break {
            page-break-before: always;
        }

        /* Styles pour le contenu formaté */
        .content-formatted {
            line-height: 1.8;
        }

        .content-formatted h2 {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin: 25px 0 15px 0;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
        }

        .content-formatted h3 {
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            margin: 20px 0 10px 0;
        }

        .content-formatted p {
            margin-bottom: 12px;
            text-align: justify;
        }

        .content-formatted ul, .content-formatted ol {
            margin: 10px 0 10px 20px;
        }

        .content-formatted li {
            margin-bottom: 8px;
        }

        .content-formatted strong {
            font-weight: 600;
            color: #2d3748;
        }

        .content-formatted em {
            font-style: italic;
            color: #4a5568;
        }

        /* Tableau de contenu */
        .toc {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .toc h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .toc ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .toc li {
            margin-bottom: 8px;
            padding-left: 15px;
            position: relative;
        }

        .toc li:before {
            content: "•";
            color: #667eea;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .toc a {
            color: #4a5568;
            text-decoration: none;
        }

        .toc a:hover {
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>{{ $titre }}</h1>
        <p><strong>Laboratoire :</strong> {{ $laboratoire->label_labo }}</p>
        <p><strong>Code :</strong> {{ $laboratoire->code_lab }}</p>
        <p><strong>Date de génération :</strong> {{ $date_generation }}</p>
        <p><strong>Code du rapport :</strong> {{ $rapport->code_rl }}</p>
    </div>

    <!-- Informations du laboratoire -->
    <div class="info-section">
        <div class="lab-info">
            <h3>Informations du Laboratoire</h3>
            <div class="lab-info-grid">
                <div class="lab-info-item">
                    <span class="lab-info-label">Nom :</span>
                    <span class="lab-info-value">{{ $laboratoire->label_labo }}</span>
                </div>
                <div class="lab-info-item">
                    <span class="lab-info-label">Code :</span>
                    <span class="lab-info-value">{{ $laboratoire->code_lab }}</span>
                </div>
                <div class="lab-info-item">
                    <span class="lab-info-label">Type :</span>
                    <span class="lab-info-value">{{ $laboratoire->type_labo ?? 'Non spécifié' }}</span>
                </div>
                <div class="lab-info-item">
                    <span class="lab-info-label">Responsable :</span>
                    <span class="lab-info-value">{{ $laboratoire->responsable_labo ?? 'Non spécifié' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu du rapport -->
    <div class="info-section">
        <div class="content-section">
            <h3>Contenu du Rapport</h3>
            <div class="content-formatted">
                {!! nl2br(e($contenu)) !!}
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>Rapport généré automatiquement par le système de gestion des laboratoires</p>
        <p>Laboratoire {{ $laboratoire->label_labo }} - {{ $date_generation }}</p>
    </div>

    <div class="page-number">
        Page 1
    </div>
</body>
</html>

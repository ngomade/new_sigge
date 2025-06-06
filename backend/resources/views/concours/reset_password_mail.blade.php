<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation de mot de passe - ESTLC</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #2d3748;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #547ea5 0%, #2359a6 100%);
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1a365d;
            margin-bottom: 20px;
        }
        .code-container {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .code {
            font-size: 32px;
            font-weight: 700;
            color: #2c5282;
            letter-spacing: 4px;
            margin: 10px 0;
        }
        .info-list {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }
        .info-list li {
            background-color: #f8fafc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }
        .info-list li strong {
            color: #4a5568;
            min-width: 180px;
            display: inline-block;
        }
        .warning {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .warning p {
            margin: 0;
            color: #c53030;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            padding: 30px;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
            color: #4a5568;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 12px;
            }
            .content {
                padding: 20px;
            }
            .code {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
{{--            <img src="{{ asset('images/logo.png') }}" alt="ESTLC Logo" class="logo">--}}
            <h1>ESTLC</h1>
            <p>Réinitialisation de mot de passe</p>
        </div>
        <div class="content">
            <div class="greeting">
                Bonjour {{ $user->ca_prenom ?? $user->prenom_pers ?? '' }} {{ $user->ca_nom ?? $user->nom_pers ?? '' }},
            </div>

            <p>Vous avez demandé la réinitialisation de votre mot de passe. Voici votre nouveau de connexion :</p>
            <p>Vous pourriez changer ce mot de passe, en définissant le votre dans vos réglages. </p>

            <div class="code-container">
                <div class="code">{{ $code }}</div>
            </div>

            <ul class="info-list">
                <li>
                    <strong>Email :</strong>
                    <span>{{ $user->ca_email ?? $user->email_pers ?? '' }}</span>
                </li>
                @if(isset($user->ca_num_recu))
                    <li>
                        <strong>Numéro de reçu :</strong>
                        <span>{{ $user->ca_num_recu }}</span>
                    </li>
                @elseif(isset($user->login_pers))
                    <li>
                        <strong>Login :</strong>
                        <span>{{ $user->login_pers }}</span>
                    </li>
                @endif
            </ul>

{{--            <div class="warning">--}}
{{--                <p>⚠️ Ce code est valable pendant 24 heures. Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>--}}
{{--            </div>--}}
        </div>
        <div class="footer">
            <p>Pour toute assistance, contactez le support</p>
            <p>© {{ date('Y') }} ESTLC - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>

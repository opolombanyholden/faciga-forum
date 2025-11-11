<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dossier Approuvé - FACIGA 2025</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #3A75C4, #009E49);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .success-badge {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .success-badge h2 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #28a745;
        }
        .success-badge .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .credentials-box {
            background: #f8f9fa;
            border-left: 4px solid #3A75C4;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .credentials-box h3 {
            margin: 0 0 20px;
            color: #3A75C4;
            font-size: 20px;
        }
        .credential-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .credential-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            font-family: 'Courier New', monospace;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #3A75C4, #009E49);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
        }
        .info-list {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .info-list h3 {
            margin: 0 0 15px;
            color: #856404;
            font-size: 18px;
        }
        .info-list ol {
            margin: 0;
            padding-left: 20px;
        }
        .info-list li {
            margin-bottom: 10px;
            color: #856404;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
        .footer p {
            margin: 5px 0;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 FACIGA 2025 🎉</h1>
            <p>Forum d'Affaires Côte d'Ivoire - Gabon</p>
            <p style="font-size: 14px; margin-top: 5px;">9-10 Octobre 2025 | Libreville, Gabon</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Badge -->
            <div class="success-badge">
                <div class="icon">✅</div>
                <h2>Félicitations !</h2>
                <p style="margin: 0; font-size: 16px;">Votre dossier a été approuvé</p>
            </div>

            <p style="font-size: 16px;">
                Bonjour,
            </p>

            <p style="font-size: 16px;">
                Nous avons le plaisir de vous informer que le dossier d'inscription de 
                <strong>{{ $company->name }}</strong> au FACIGA 2025 a été <strong>validé avec succès</strong> 
                par notre comité d'organisation.
            </p>

            <!-- Identifiants de connexion -->
            <div class="credentials-box">
                <h3>🔐 Vos identifiants de connexion</h3>
                <p style="margin: 0 0 20px; color: #666;">
                    Utilisez ces identifiants pour accéder à votre espace entreprise :
                </p>

                <div class="credential-item">
                    <div class="credential-label">Adresse email</div>
                    <div class="credential-value">{{ $company->email }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Mot de passe temporaire</div>
                    <div class="credential-value" style="color: #dc3545;">{{ $password }}</div>
                </div>
            </div>

            <div class="warning-box">
                <strong>⚠️ Important :</strong> Ce mot de passe est temporaire. 
                Nous vous recommandons de le modifier dès votre première connexion pour des raisons de sécurité.
            </div>

            <!-- Bouton de connexion -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/login') }}" class="btn-primary">
                    🔓 Accéder à mon espace entreprise
                </a>
            </div>

            <div class="divider"></div>

            <!-- Prochaines étapes -->
            <div class="info-list">
                <h3>📋 Prochaines étapes</h3>
                <ol>
                    <li><strong>Connectez-vous</strong> à votre espace entreprise avec vos identifiants ci-dessus</li>
                    <li><strong>Confirmez votre participation</strong> pour activer toutes les fonctionnalités</li>
                    <li><strong>Ajoutez vos participants</strong> (maximum 3 personnes par entreprise)</li>
                    <li><strong>Consultez l'annuaire</strong> des entreprises participantes</li>
                    <li><strong>Demandez des rendez-vous</strong> B2B ou B2G selon vos intérêts</li>
                </ol>
            </div>

            <p style="font-size: 16px; margin-top: 30px;">
                <strong>Au plaisir de vous accueillir à Libreville pour cet événement historique !</strong>
            </p>

            <p style="font-size: 16px;">
                Cordialement,<br>
                <strong>L'équipe d'organisation FACIGA 2025</strong><br>
                ANPI-Gabon
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>ANPI-Gabon</strong></p>
            <p>104 Rue Gustave ANGUILE, Serena Mall</p>
            <p>Libreville, Gabon</p>
            <p>📧 contact@investingabon.ga | 📞 +241 11 76 48 48</p>
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p style="font-size: 12px; color: #999;">
                    Cet email contient des informations confidentielles. Si vous l'avez reçu par erreur, 
                    merci de nous en informer et de le supprimer.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
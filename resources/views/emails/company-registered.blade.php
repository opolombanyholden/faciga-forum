<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #3A75C4, #009E49); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #3A75C4; }
        .alert { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        ul { padding-left: 20px; }
        li { margin: 8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 FACIGA 2025</h1>
            <p>Forum d'Affaires Côte d'Ivoire - Gabon</p>
        </div>
        
        <div class="content">
            <h2>✅ Confirmation de réception de votre inscription</h2>
            
            <p>Bonjour <strong>{{ $company->name }}</strong>,</p>
            
            <p>Nous accusons bonne réception de votre dossier d'inscription au <strong>Forum FACIGA 2025</strong>.</p>
            
            <div class="info-box">
                <h3>📋 Informations enregistrées</h3>
                <ul>
                    <li><strong>Entreprise :</strong> {{ $company->name }}</li>
                    <li><strong>Pays :</strong> {{ $company->country }}</li>
                    <li><strong>Secteur :</strong> {{ $company->sector }}</li>
                    <li><strong>Email :</strong> {{ $company->email }}</li>
                    <li><strong>Téléphone :</strong> {{ $company->phone }}</li>
                </ul>
            </div>
            
            <h3>📌 Prochaines étapes</h3>
            <ol>
                <li><strong>Examen du dossier :</strong> Notre équipe va examiner votre dossier sous 48-72h</li>
                <li><strong>Notification :</strong> Vous recevrez un email de validation ou de demande de complément d'information</li>
                <li><strong>Accès participant :</strong> Une fois validé, vous recevrez vos identifiants d'accès</li>
            </ol>
            
            <div class="alert">
                <strong>⚠️ Important :</strong> Pensez à vérifier vos spams/courriers indésirables pour ne manquer aucune communication de notre part.
            </div>
            
            <h3>📞 Contact</h3>
            <p>Pour toute question :</p>
            <ul>
                <li>📧 Email : event@investingabon.ga</li>
                <li>📱 Téléphone : +241 (0)74 58 24 24</li>
            </ul>
            
            <p style="margin-top: 30px;">Cordialement,<br><strong>Le comité d'organisation ANPI-GABON</strong></p>
        </div>
        
        <div class="footer">
            <p>© 2025 FACIGA - Forum d'Affaires Côte d'Ivoire - Gabon<br>
            📍 Hôtel le Nomad, Libreville | 📅 18-19 Novembre 2025</p>
        </div>
    </div>
</body>
</html>
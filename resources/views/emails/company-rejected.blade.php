<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #dc3545, #6c757d); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #dc3545; border-radius: 4px; }
        .reason-box { background: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; margin: 20px 0; border-radius: 4px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 30px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        ul { padding-left: 20px; }
        li { margin: 8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>❌ FACIGA 2025</h1>
            <p>Forum d'Affaires Côte d'Ivoire - Gabon</p>
        </div>
        
        <div class="content">
            <h2>Votre candidature au FACIGA 2025</h2>
            
            <p>Bonjour <strong>{{ $company->name }}</strong>,</p>
            
            <p>Nous vous remercions de l'intérêt que vous portez au Forum FACIGA 2025 et du temps que vous avez consacré à votre candidature.</p>
            
            <div class="reason-box">
                <h3>⚠️ Statut de votre dossier</h3>
                <p>Après examen attentif de votre dossier, nous regrettons de vous informer que votre candidature n'a pas été retenue pour cette édition du forum.</p>
                
                @if($rejectionReason)
                <p><strong>Motif :</strong><br>{{ $rejectionReason }}</p>
                @endif
            </div>
            
            <div class="info-box">
                <h3>📋 Informations de votre dossier</h3>
                <ul>
                    <li><strong>Entreprise :</strong> {{ $company->name }}</li>
                    <li><strong>Pays :</strong> {{ $company->country }}</li>
                    <li><strong>Secteur :</strong> {{ $company->sector }}</li>
                    <li><strong>Date de candidature :</strong> {{ $company->created_at->format('d/m/Y') }}</li>
                </ul>
            </div>
            
            <h3>🔄 Prochaines étapes</h3>
            <p>Nous vous encourageons vivement à :</p>
            <ul>
                <li>Compléter votre dossier si des informations manquent</li>
                <li>Revoir les critères de participation sur notre site web</li>
                <li>Nous contacter pour plus d'informations</li>
                <li>Soumettre une nouvelle candidature pour les prochaines éditions</li>
            </ul>
            
            <h3>📞 Contact</h3>
            <p>Pour toute question ou clarification concernant votre dossier :</p>
            <ul>
                <li>📧 Email : event@investingabon.ga</li>
                <li>📱 Téléphone : +241 (0)74 58 24 24</li>
            </ul>
            
            <p style="margin-top: 30px;">Nous vous remercions de votre compréhension et espérons avoir l'opportunité de collaborer avec vous lors de nos prochains événements.</p>
            
            <p>Cordialement,<br><strong>Le comité d'organisation FACIGA 2025</strong></p>
        </div>
        
        <div class="footer">
            <p>© 2025 FACIGA - Forum d'Affaires Côte d'Ivoire - Gabon<br>
            📍 Hôtel le Nomad, Libreville | 📅 18-19 Novembre 2025</p>
        </div>
    </div>
</body>
</html>
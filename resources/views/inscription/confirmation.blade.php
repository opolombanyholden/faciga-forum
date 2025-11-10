<!-- resources/views/inscription/confirmation.blade.php -->
@extends('layouts.app')

@section('title', 'Confirmation d\'inscription - FACIGA 2025')

@section('content')
<style>
    .confirmation-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }
    
    .confirmation-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        padding: 60px 40px;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        animation: scaleIn 0.5s ease-out;
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .success-icon i {
        font-size: 50px;
        color: white;
    }
    
    .confirmation-title {
        color: var(--blue-gabon);
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
    }
    
    .confirmation-text {
        color: #666;
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 30px;
    }
    
    .info-box {
        background: linear-gradient(135deg, rgba(58, 117, 196, 0.05), rgba(0, 158, 73, 0.05));
        border-left: 4px solid var(--blue-gabon);
        padding: 20px;
        margin: 30px 0;
        border-radius: 8px;
        text-align: left;
    }
    
    .info-box strong {
        color: var(--blue-gabon);
    }
    
    .next-steps {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin-top: 30px;
        text-align: left;
    }
    
    .next-steps h4 {
        color: var(--blue-gabon);
        margin-bottom: 20px;
    }
    
    .step-item {
        display: flex;
        align-items: start;
        margin-bottom: 15px;
    }
    
    .step-number {
        width: 30px;
        height: 30px;
        background: var(--blue-gabon);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--blue-gabon), var(--green-gabon));
        border: none;
        padding: 15px 40px;
        border-radius: 30px;
        color: white;
        font-weight: 600;
        transition: transform 0.3s;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(58, 117, 196, 0.3);
    }
</style>

<div class="confirmation-container">
    <div class="container">
        <div class="confirmation-card">
            <div class="success-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            
            <h1 class="confirmation-title">Inscription Confirmée !</h1>
            
            <p class="confirmation-text">
                Votre dossier de candidature pour le <strong>FACIGA 2025</strong> a été soumis avec succès.
                Nous avons bien reçu toutes vos informations.
            </p>
            
            <div class="info-box">
                <p class="mb-2"><strong>Numéro de dossier :</strong> #FACIGA-{{ $company->id ?? date('Ymd') }}</p>
                <p class="mb-2"><strong>Entreprise :</strong> {{ $company->name ?? 'Votre entreprise' }}</p>
                <p class="mb-0"><strong>Email :</strong> {{ $company->email ?? 'Votre email' }}</p>
            </div>
            
            <div class="next-steps">
                <h4><i class="bi bi-list-check me-2"></i> Prochaines étapes</h4>
                
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Vérification du dossier</strong>
                        <p class="mb-0 text-muted small">Notre équipe examine votre candidature sous 48h ouvrables</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Validation par email</strong>
                        <p class="mb-0 text-muted small">Vous recevrez un email de confirmation avec vos identifiants de connexion</p>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Accès à votre espace</strong>
                        <p class="mb-0 text-muted small">Connectez-vous pour finaliser votre participation et accéder aux ressources</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                Un email de confirmation a été envoyé à <strong>{{ $company->email ?? 'votre adresse email' }}</strong>
            </div>
            
            <div class="mt-4">
                <a href="/" class="btn btn-primary-custom me-2">
                    <i class="bi bi-house me-2"></i> Retour à l'accueil
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-envelope me-2"></i> Nous contacter
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Contact - FACIGA 2025')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="mb-4 text-center">Contactez-nous</h2>
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="bi bi-building"></i> ANPI-Gabon
                            </h5>
                            <p class="card-text">
                                <strong>Adresse:</strong><br>
                                104 Rue Gustave ANGUILE, Serena Mall<br>
                                BP: 3404 Libreville - Gabon
                            </p>
                            <p class="card-text">
                                <strong><i class="bi bi-telephone"></i> Téléphone:</strong><br>
                                011 76 48 48
                            </p>
                            <p class="card-text">
                                <strong><i class="bi bi-envelope"></i> Email:</strong><br>
                                contact@investingabon.ga
                            </p>
                            <p class="card-text">
                                <strong><i class="bi bi-globe"></i> Site web:</strong><br>
                                <a href="https://www.investingabon.ga" target="_blank">www.investingabon.ga</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                <i class="bi bi-building"></i> CEPICI - Côte d'Ivoire
                            </h5>
                            <p class="card-text">
                                <strong>Centre de Promotion des Investissements en Côte d'Ivoire</strong>
                            </p>
                            <p class="card-text">
                                Pour plus d'informations sur le CEPICI, veuillez consulter leur site web officiel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Envoyez-nous un message</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle"></i> 
                <strong>Note:</strong> Pour toute question concernant votre inscription, veuillez d'abord consulter 
                votre espace participant ou contacter directement l'ANPI-Gabon.
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Inscription - FACIGA 2025')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Formulaire d'inscription</h3>
                </div>
                <div class="card-body p-4">
                    <p class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Merci de remplir ce formulaire pour participer au FACIGA 2025
                    </p>

                    <form action="{{ route('inscription.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="mb-3 text-primary">Informations sur l'entreprise</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom de l'entreprise *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="form-label">Pays *</label>
                                <select class="form-select @error('country') is-invalid @enderror" 
                                        id="country" name="country" required>
                                    <option value="">Sélectionnez...</option>
                                    <option value="Côte d'Ivoire" {{ old('country') == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                                    <option value="Gabon" {{ old('country') == 'Gabon' ? 'selected' : '' }}>Gabon</option>
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sector" class="form-label">Secteur d'activité *</label>
                            <input type="text" class="form-control @error('sector') is-invalid @enderror" 
                                   id="sector" name="sector" value="{{ old('sector') }}" required>
                            @error('sector')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo de l'entreprise</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mb-3 text-primary mt-4">Informations de contact</h5>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone WhatsApp (avec indicatif) *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="Ex: +241 XX XX XX XX" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" required>
                            </div>
                        </div>

                        <h5 class="mb-3 text-primary mt-4">Motivations et intérêts</h5>

                        <div class="mb-3">
                            <label for="motivation" class="form-label">Motivation principale de votre participation</label>
                            <textarea class="form-control @error('motivation') is-invalid @enderror" 
                                      id="motivation" name="motivation" rows="3">{{ old('motivation') }}</textarea>
                            @error('motivation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Secteurs d'intérêt * (plusieurs choix possibles)</label>
                            <div class="row">
                                @php
                                $sectors = [
                                    'Maraîchers et céréales',
                                    'Anacarde',
                                    'Elevage (porcs, poulets)',
                                    'Aquaculture (poissons et fruits de mer)',
                                    'Palmier à huile',
                                    'Energies renouvelables (solaires et gaz)',
                                    'Mines',
                                    'Infrastructures routières',
                                    'Banque-Assurance',
                                    'Production biométrique',
                                    'Projets Startup et Innovations',
                                    'Projets Monétiques'
                                ];
                                @endphp
                                @foreach($sectors as $sector)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sectors_interest[]" 
                                               value="{{ $sector }}" id="sector_{{ $loop->index }}">
                                        <label class="form-check-label" for="sector_{{ $loop->index }}">
                                            {{ $sector }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('sectors_interest')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mb-3 text-primary mt-4">Types de rencontres souhaitées</h5>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wants_btob" 
                                       value="1" id="btob" {{ old('wants_btob') ? 'checked' : '' }}>
                                <label class="form-check-label" for="btob">
                                    <strong>Rencontres B2B</strong> (Business to Business)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wants_btog" 
                                       value="1" id="btog" {{ old('wants_btog') ? 'checked' : '' }}>
                                <label class="form-check-label" for="btog">
                                    <strong>Rencontres B2G</strong> (Business to Government)
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="other_meetings" class="form-label">Autres types de rencontres souhaitées</label>
                            <input type="text" class="form-control @error('other_meetings') is-invalid @enderror" 
                                   id="other_meetings" name="other_meetings" value="{{ old('other_meetings') }}">
                            @error('other_meetings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte que mes informations soient utilisées dans le cadre de cet événement *
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Valider mon inscription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
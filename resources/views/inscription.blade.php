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
                        <i class="bi bi-info-circle"></i> Merci de remplir ce formulaire pour participer au FACIGA 2025. 
                        <strong>Vos identifiants de connexion vous seront envoyés par email après validation de votre dossier.</strong>
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

                        <h5 class="mb-3 text-primary mt-4">Contact principal</h5>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email de l'entreprise *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> C'est à cette adresse que vous recevrez vos identifiants de connexion
                            </small>
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

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo de l'entreprise</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" accept="image/*">
                            <small class="form-text text-muted">Format accepté : JPG, PNG (max 2 Mo)</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                    'Infrastructures',
                                    'Hôtellerie et restauration',
                                    'Services',
                                    'Immobilier',
                                    'Santé',
                                    'Autre'
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
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type de rencontres souhaitées</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wants_btob" id="wants_btob" 
                                       {{ old('wants_btob') ? 'checked' : '' }}>
                                <label class="form-check-label" for="wants_btob">
                                    Rencontres B2B (Business to Business)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wants_btog" id="wants_btog"
                                       {{ old('wants_btog') ? 'checked' : '' }}>
                                <label class="form-check-label" for="wants_btog">
                                    Rencontres B2G (Business to Government)
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="other_meetings" class="form-label">Autres attentes ou besoins spécifiques</label>
                            <textarea class="form-control" id="other_meetings" name="other_meetings" 
                                      rows="2">{{ old('other_meetings') }}</textarea>
                        </div>

                        <!-- ✅ SECTION ACCEPTATION DES CONDITIONS -->
                        <div class="mb-4">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h5 class="mb-3 text-primary">
                                        <i class="bi bi-file-text"></i> Conditions de participation
                                    </h5>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input @error('accept_terms') is-invalid @enderror" 
                                               type="checkbox" 
                                               name="accept_terms" 
                                               id="accept_terms" 
                                               value="1"
                                               {{ old('accept_terms') ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="accept_terms">
                                            <strong>Je certifie que les informations fournies sont exactes et j'accepte les 
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-primary">
                                                conditions générales de participation
                                            </a> au FACIGA 2025 *</strong>
                                        </label>
                                        @error('accept_terms')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-check mt-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="accept_data_processing" 
                                               id="accept_data_processing" 
                                               value="1"
                                               {{ old('accept_data_processing') ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="accept_data_processing">
                                            J'accepte que mes données soient traitées dans le cadre de l'organisation du FACIGA 2025 
                                            et partagées avec les autres participants *
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send"></i> Soumettre ma candidature
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Retour à l'accueil
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conditions Générales -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="bi bi-file-text"></i> Conditions Générales de Participation - FACIGA 2025
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary">Article 1 : Objet</h6>
                <p>
                    Le présent règlement a pour objet de définir les conditions de participation au 
                    Forum d'Affaires Côte d'Ivoire - Gabon (FACIGA 2025) qui se tiendra les 9 et 10 octobre 2025 
                    au Radisson Blu Okoumé Hôtel de Libreville, Gabon.
                </p>

                <h6 class="text-primary">Article 2 : Conditions d'éligibilité</h6>
                <p>
                    Peuvent participer au FACIGA 2025 :
                </p>
                <ul>
                    <li>Les entreprises légalement constituées en Côte d'Ivoire ou au Gabon</li>
                    <li>Les représentants d'institutions gouvernementales des deux pays</li>
                    <li>Les organisations professionnelles reconnues</li>
                </ul>

                <h6 class="text-primary">Article 3 : Processus d'inscription</h6>
                <p>
                    L'inscription se fait exclusivement en ligne via le formulaire officiel. 
                    Toute candidature est soumise à validation par le comité d'organisation. 
                    Les identifiants de connexion seront envoyés par email après approbation du dossier.
                </p>

                <h6 class="text-primary">Article 4 : Participants</h6>
                <p>
                    Chaque entreprise peut inscrire un maximum de 3 participants. 
                    Les modifications de participants doivent être signalées au plus tard 48h avant l'événement.
                </p>

                <h6 class="text-primary">Article 5 : Traitement des données personnelles</h6>
                <p>
                    Les données collectées sont destinées à l'organisation du FACIGA 2025. 
                    Conformément à la réglementation en vigueur, vous disposez d'un droit d'accès, 
                    de rectification et de suppression de vos données personnelles.
                </p>

                <h6 class="text-primary">Article 6 : Partage d'informations</h6>
                <p>
                    Les informations fournies (nom de l'entreprise, secteur d'activité, coordonnées) 
                    seront partagées dans l'annuaire des participants pour faciliter les échanges B2B et B2G.
                </p>

                <h6 class="text-primary">Article 7 : Annulation</h6>
                <p>
                    Toute annulation doit être notifiée par écrit à l'adresse : contact@investingabon.ga
                </p>

                <h6 class="text-primary">Article 8 : Responsabilité</h6>
                <p>
                    Les organisateurs ne sauraient être tenus responsables des dommages directs ou indirects 
                    résultant de la participation au FACIGA 2025.
                </p>

                <h6 class="text-primary">Article 9 : Modification du règlement</h6>
                <p>
                    Les organisateurs se réservent le droit de modifier le présent règlement. 
                    Toute modification sera communiquée aux participants inscrits.
                </p>

                <hr>

                <p class="text-muted small">
                    <strong>Contact :</strong><br>
                    ANPI-Gabon<br>
                    104 Rue Gustave ANGUILE, Serena Mall<br>
                    Libreville, Gabon<br>
                    Email : contact@investingabon.ga<br>
                    Tél : +241 11 76 48 48
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection
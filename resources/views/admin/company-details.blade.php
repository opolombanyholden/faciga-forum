@extends('admin.layouts.app')

@section('title', 'Détails de l\'entreprise')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <a href="{{ route('admin.companies') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                    <h3 class="mb-1">{{ $company->name }}</h3>
                    <p class="text-muted mb-0">
                        Dossier #{{ $company->id }} • Inscrit le {{ $company->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div>
                    @if($company->status === 'pending' && $admin->canManageCompanies())
                    <!-- Formulaire d'approbation -->
                    <form action="{{ route('admin.approve', $company->id) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Approuver l\'entreprise {{ addslashes($company->name) }} ?\n\nUn email de confirmation sera envoyé automatiquement.')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Approuver
                        </button>
                    </form>
                    
                    <!-- Bouton modal de rejet -->
                    <button type="button" 
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Rejeter
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informations générales</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Nom de l'entreprise</label>
                            <p class="mb-0"><strong>{{ $company->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Pays</label>
                            <p class="mb-0">
                                @if($company->country === 'Côte d\'Ivoire')
                                <span class="badge bg-warning bg-opacity-10 text-warning">
                                    🇨🇮 {{ $company->country }}
                                </span>
                                @else
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    🇬🇦 {{ $company->country }}
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Secteur d'activité</label>
                            <p class="mb-0">{{ $company->sector }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Téléphone</label>
                            <p class="mb-0">
                                <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Logo</label>
                            <p class="mb-0">
                                @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" 
                                     alt="{{ $company->name }}" 
                                     class="img-thumbnail"
                                     style="max-height: 80px;">
                                @else
                                <span class="text-muted">Aucun logo</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivation et intérêts -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-chat-square-text me-2"></i>Motivation et intérêts</h5>
                </div>
                <div class="card-body">
                    @if($company->motivation)
                    <div class="mb-4">
                        <label class="text-muted small">Motivation pour participer</label>
                        <p class="mb-0">{{ $company->motivation }}</p>
                    </div>
                    @endif

                    @if($company->sectors_interest)
                    <div class="mb-4">
                        <label class="text-muted small">Secteurs d'intérêt</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(is_array($company->sectors_interest) ? $company->sectors_interest : json_decode($company->sectors_interest, true) as $sector)
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $sector }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <label class="text-muted small">Intérêt B2B</label>
                            <p class="mb-0">
                                @if($company->wants_btob)
                                <span class="badge bg-success"><i class="bi bi-check"></i> Oui</span>
                                @else
                                <span class="badge bg-secondary">Non</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Intérêt B2G</label>
                            <p class="mb-0">
                                @if($company->wants_btog)
                                <span class="badge bg-success"><i class="bi bi-check"></i> Oui</span>
                                @else
                                <span class="badge bg-secondary">Non</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($company->other_meetings)
                    <div class="mt-4">
                        <label class="text-muted small">Autres rencontres souhaitées</label>
                        <p class="mb-0">{{ $company->other_meetings }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Participants -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Participants ({{ $company->participants->count() }}/3)</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($company->participants->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($company->participants as $participant)
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $participant->name }}</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-briefcase"></i> {{ $participant->function }}
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-envelope"></i> {{ $participant->email }}<br>
                                        <i class="bi bi-telephone"></i> {{ $participant->phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-person-x fs-1"></i>
                        <p class="mt-2">Aucun participant enregistré</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Statut -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-flag me-2"></i>Statut</h5>
                </div>
                <div class="card-body">
                    @if($company->status === 'pending')
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-clock fs-4"></i>
                        <h6 class="mt-2 mb-1">En attente</h6>
                        <small>Le dossier est en cours d'examen</small>
                    </div>
                    @elseif($company->status === 'approved')
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle fs-4"></i>
                        <h6 class="mt-2 mb-1">Approuvé</h6>
                        <small>Le dossier a été validé</small>
                    </div>
                    @if($company->confirmed)
                    <div class="alert alert-primary mt-2 mb-0">
                        <i class="bi bi-hand-thumbs-up fs-4"></i>
                        <h6 class="mt-2 mb-1">Participation confirmée</h6>
                        <small>L'entreprise a confirmé sa présence</small>
                    </div>
                    @endif
                    @else
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-x-circle fs-4"></i>
                        <h6 class="mt-2 mb-1">Rejeté</h6>
                        @if($company->rejection_reason)
                        <small class="d-block mt-2">
                            <strong>Motif :</strong><br>
                            {{ $company->rejection_reason }}
                        </small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dates -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar me-2"></i>Dates</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Date d'inscription</label>
                        <p class="mb-0">{{ $company->created_at->format('d/m/Y à H:i') }}</p>
                        <small class="text-muted">Il y a {{ $company->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Dernière mise à jour</label>
                        <p class="mb-0">{{ $company->updated_at->format('d/m/Y à H:i') }}</p>
                        <small class="text-muted">Il y a {{ $company->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Actions admin -->
            @if($admin->canManageCompanies())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions administrateur</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($company->status === 'pending')
                        <!-- Formulaire d'approbation -->
                        <form action="{{ route('admin.approve', $company->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Approuver l\'entreprise {{ addslashes($company->name) }} ?\n\nUn email de confirmation sera envoyé automatiquement.')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Approuver le dossier
                            </button>
                        </form>
                        
                        <!-- Bouton modal de rejet -->
                        <button type="button" 
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Rejeter le dossier
                        </button>
                        
                        @elseif($company->status === 'rejected')
                        <!-- Formulaire de réexamen -->
                        <form action="{{ route('admin.approve', $company->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Réexaminer et approuver {{ addslashes($company->name) }} ?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-arrow-counterclockwise"></i> Réexaminer et approuver
                            </button>
                        </form>
                        @endif

                        <hr>

                        <a href="mailto:{{ $company->email }}" class="btn btn-outline-primary">
                            <i class="bi bi-envelope"></i> Envoyer un email
                        </a>

                        @if($admin->isSuperAdmin())
                        <hr>
                        <!-- Formulaire de suppression -->
                        <form action="{{ route('admin.companies', $company->id) }}" 
                              method="POST"
                              onsubmit="return confirm('⚠️ ATTENTION ⚠️\n\nÊtes-vous sûr de vouloir SUPPRIMER définitivement l\'entreprise {{ addslashes($company->name) }} ?\n\nCette action est irréversible et supprimera également tous les participants associés.') && confirm('Confirmez-vous vraiment cette suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Supprimer l'entreprise
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de rejet -->
@if($admin->canManageCompanies())
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.reject', $company->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle text-danger"></i> Rejeter l'entreprise
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Vous êtes sur le point de rejeter : <strong>{{ $company->name }}</strong></p>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Cette action enverra un email de notification à l'entreprise.
                    </div>

                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">
                            Motif du rejet <small class="text-muted">(optionnel)</small>
                        </label>
                        <textarea class="form-control" 
                                  id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="4"
                                  placeholder="Ex: Dossier incomplet, informations manquantes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
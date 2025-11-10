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
                    <button type="button" 
                            class="btn btn-success"
                            onclick="approveCompany({{ $company->id }}, '{{ $company->name }}')">
                        <i class="bi bi-check-circle"></i> Approuver
                    </button>
                    <button type="button" 
                            class="btn btn-danger"
                            onclick="openRejectModal({{ $company->id }}, '{{ $company->name }}')">
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
                                    <p class="text-muted mb-1 small">{{ $participant->function }}</p>
                                    <p class="mb-0 small">
                                        <i class="bi bi-envelope"></i> {{ $participant->email }}<br>
                                        <i class="bi bi-telephone"></i> {{ $participant->phone }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-people fs-1"></i>
                        <p class="mt-2 mb-0">Aucun participant enregistré</p>
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
                    <h5 class="mb-0"><i class="bi bi-flag me-2"></i>Statut du dossier</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Statut de validation</label>
                        <p class="mb-0">
                            @if($company->status === 'pending')
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-hourglass-split"></i> En attente de validation
                            </span>
                            @elseif($company->status === 'approved')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle"></i> Approuvé
                            </span>
                            @else
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-x-circle"></i> Rejeté
                            </span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Participation confirmée</label>
                        <p class="mb-0">
                            @if($company->confirmed)
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-hand-thumbs-up"></i> Oui
                            </span>
                            @else
                            <span class="badge bg-secondary fs-6">Non</span>
                            @endif
                        </p>
                    </div>

                    @if($company->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Motif du rejet :</strong><br>
                        {{ $company->rejection_reason }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dates importantes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Dates importantes</h5>
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
                        <button type="button" 
                                class="btn btn-success"
                                onclick="approveCompany({{ $company->id }}, '{{ $company->name }}')">
                            <i class="bi bi-check-circle"></i> Approuver le dossier
                        </button>
                        <button type="button" 
                                class="btn btn-danger"
                                onclick="openRejectModal({{ $company->id }}, '{{ $company->name }}')">
                            <i class="bi bi-x-circle"></i> Rejeter le dossier
                        </button>
                        @elseif($company->status === 'rejected')
                        <button type="button" 
                                class="btn btn-warning"
                                onclick="approveCompany({{ $company->id }}, '{{ $company->name }}')">
                            <i class="bi bi-arrow-counterclockwise"></i> Réexaminer et approuver
                        </button>
                        @endif

                        <hr>

                        <a href="mailto:{{ $company->email }}" class="btn btn-outline-primary">
                            <i class="bi bi-envelope"></i> Envoyer un email
                        </a>

                        @if($admin->isSuperAdmin())
                        <hr>
                        <button type="button" 
                                class="btn btn-outline-danger"
                                onclick="deleteCompany({{ $company->id }}, '{{ $company->name }}')">
                            <i class="bi bi-trash"></i> Supprimer l'entreprise
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle text-danger"></i> Rejeter l'entreprise
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Vous êtes sur le point de rejeter : <strong id="companyNameReject"></strong></p>
                    
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

<script>
// Approbation
function approveCompany(id, name) {
    if (confirm(`Approuver l'entreprise "${name}" ?\n\nUn email de confirmation sera envoyé automatiquement.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/companies/${id}/approve`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

// Rejet
function openRejectModal(id, name) {
    document.getElementById('companyNameReject').textContent = name;
    document.getElementById('rejectForm').action = `/admin/companies/${id}/reject`;
    
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Suppression (Super Admin)
function deleteCompany(id, name) {
    if (confirm(`⚠️ ATTENTION ⚠️\n\nÊtes-vous sûr de vouloir SUPPRIMER définitivement l'entreprise "${name}" ?\n\nCette action est irréversible et supprimera également tous les participants associés.`)) {
        if (confirm('Confirmez-vous vraiment cette suppression ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/companies/${id}`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    }
}
</script>
@endsection
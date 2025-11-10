@extends('admin.layouts.app')

@section('title', 'Gestion des entreprises')

@section('content')
<div class="container-fluid py-4">
    <!-- Header avec filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="bi bi-building"></i> Gestion des entreprises
                        </h4>
                        <div>
                            <a href="{{ route('admin.export.companies') }}" class="btn btn-success">
                                <i class="bi bi-download"></i> Exporter CSV
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>

                    <!-- Formulaire de filtres -->
                    <form action="{{ route('admin.companies') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Recherche</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Nom, email, secteur..."
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                    Approuvé
                                </option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                    Rejeté
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">Pays</label>
                            <select name="country" class="form-select">
                                <option value="">Tous</option>
                                <option value="Côte d'Ivoire" {{ request('country') === 'Côte d\'Ivoire' ? 'selected' : '' }}>
                                    Côte d'Ivoire
                                </option>
                                <option value="Gabon" {{ request('country') === 'Gabon' ? 'selected' : '' }}>
                                    Gabon
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">Confirmation</label>
                            <select name="confirmed" class="form-select">
                                <option value="">Tous</option>
                                <option value="1" {{ request('confirmed') === '1' ? 'selected' : '' }}>
                                    Confirmé
                                </option>
                                <option value="0" {{ request('confirmed') === '0' ? 'selected' : '' }}>
                                    Non confirmé
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small text-muted">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="bi bi-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.companies') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Résultats -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ $companies->total() }}</strong> entreprise(s) trouvée(s)
                        </span>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.companies', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => 'desc'])) }}" 
                               class="btn btn-outline-secondary {{ request('sort') === 'created_at' ? 'active' : '' }}">
                                <i class="bi bi-sort-down"></i> Plus récents
                            </a>
                            <a href="{{ route('admin.companies', array_merge(request()->all(), ['sort' => 'name', 'direction' => 'asc'])) }}" 
                               class="btn btn-outline-secondary {{ request('sort') === 'name' ? 'active' : '' }}">
                                <i class="bi bi-sort-alpha-down"></i> A-Z
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($companies->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 25%">Entreprise</th>
                                    <th style="width: 10%">Pays</th>
                                    <th style="width: 15%">Secteur</th>
                                    <th style="width: 15%">Contact</th>
                                    <th style="width: 10%" class="text-center">Participants</th>
                                    <th style="width: 10%">Statut</th>
                                    <th style="width: 10%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $company)
                                <tr>
                                    <td class="text-muted">{{ $company->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($company->logo)
                                            <img src="{{ asset('storage/' . $company->logo) }}" 
                                                 alt="{{ $company->name }}" 
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: contain; border: 1px solid #dee2e6;">
                                            @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; border: 1px solid #dee2e6;">
                                                <i class="bi bi-building text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $company->name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i> {{ $company->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($company->country === 'Côte d\'Ivoire')
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            🇨🇮 CI
                                        </span>
                                        @else
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            🇬🇦 GA
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($company->sector, 30) }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="bi bi-envelope"></i> {{ Str::limit($company->email, 20) }}<br>
                                            <i class="bi bi-telephone"></i> {{ $company->phone }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            {{ $company->participants->count() }}/3
                                        </span>
                                    </td>
                                    <td>
                                        @if($company->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock"></i> En attente
                                        </span>
                                        @elseif($company->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Approuvé
                                        </span>
                                        @if($company->confirmed)
                                        <br><span class="badge bg-primary mt-1">
                                            <i class="bi bi-hand-thumbs-up"></i> Confirmé
                                        </span>
                                        @endif
                                        @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Rejeté
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.company.show', $company->id) }}" 
                                               class="btn btn-outline-primary"
                                               title="Voir les détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if($admin->canManageCompanies())
                                                @if($company->status === 'pending')
                                                <!-- Formulaire d'approbation -->
                                                <form action="{{ route('admin.approve', $company->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Approuver l\'entreprise {{ addslashes($company->name) }} ?\n\nUn email de confirmation sera envoyé automatiquement.')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" 
                                                            class="btn btn-outline-success"
                                                            title="Approuver">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                
                                                <!-- Bouton pour ouvrir le modal de rejet -->
                                                <button type="button" 
                                                        class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $company->id }}"
                                                        title="Rejeter">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Modal de rejet pour cette entreprise -->
                                @if($admin->canManageCompanies() && $company->status === 'pending')
                                <div class="modal fade" id="rejectModal{{ $company->id }}" tabindex="-1">
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
                                                        <label for="rejection_reason{{ $company->id }}" class="form-label">
                                                            Motif du rejet <small class="text-muted">(optionnel)</small>
                                                        </label>
                                                        <textarea class="form-control" 
                                                                  id="rejection_reason{{ $company->id }}" 
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-white border-0">
                        {{ $companies->links() }}
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-3">Aucune entreprise trouvée</p>
                        @if(request()->hasAny(['search', 'status', 'country', 'confirmed']))
                        <a href="{{ route('admin.companies') }}" class="btn btn-primary btn-sm">
                            Réinitialiser les filtres
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table td {
    vertical-align: middle;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}
</style>
@endsection
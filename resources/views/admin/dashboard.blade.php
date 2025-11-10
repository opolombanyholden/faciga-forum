@extends('admin.layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Tableau de bord</h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-person-badge"></i> {{ $admin->name }} 
                        <span class="badge bg-primary ms-2">{{ ucfirst($admin->role) }}</span>
                    </p>
                </div>
                <div>
                    <span class="text-muted">
                        <i class="bi bi-clock"></i> {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">En attente</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                            <small class="text-muted">Dossiers à valider</small>
                        </div>
                    </div>
                    @if($stats['pending'] > 0)
                    <div class="mt-3">
                        <a href="{{ route('admin.companies', ['status' => 'pending']) }}" class="btn btn-sm btn-warning w-100">
                            <i class="bi bi-eye"></i> Voir les dossiers
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Approuvés</h6>
                            <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                            <small class="text-muted">Dossiers validés</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-hand-thumbs-up fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Confirmés</h6>
                            <h3 class="mb-0">{{ $stats['confirmed'] }}</h3>
                            <small class="text-muted">Participations confirmées</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Participants</h6>
                            <h3 class="mb-0">{{ $stats['participants_total'] }}</h3>
                            <small class="text-muted">Total inscrits</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques secondaires -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Répartition par pays</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-circle-fill text-warning me-2"></i> Côte d'Ivoire</span>
                        <strong>{{ $stats['cote_ivoire'] }}</strong>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ $stats['total'] > 0 ? ($stats['cote_ivoire'] / $stats['total']) * 100 : 0 }}%"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-circle-fill text-success me-2"></i> Gabon</span>
                        <strong>{{ $stats['gabon'] }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['total'] > 0 ? ($stats['gabon'] / $stats['total']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Statut général</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-circle-fill text-danger me-2"></i> Rejetés</span>
                        <strong>{{ $stats['rejected'] }}</strong>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-danger" style="width: {{ $stats['total'] > 0 ? ($stats['rejected'] / $stats['total']) * 100 : 0 }}%"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="bi bi-circle-fill text-secondary me-2"></i> Total</span>
                        <strong>{{ $stats['total'] }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-secondary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Taux de confirmation</h6>
                    <div class="text-center py-3">
                        <h2 class="mb-0 text-primary">
                            {{ $stats['approved'] > 0 ? round(($stats['confirmed'] / $stats['approved']) * 100) : 0 }}%
                        </h2>
                        <small class="text-muted">{{ $stats['confirmed'] }} / {{ $stats['approved'] }} confirmés</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Dernières inscriptions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Dernières inscriptions</h5>
                        <a href="{{ route('admin.companies') }}" class="btn btn-sm btn-outline-primary">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentCompanies->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Entreprise</th>
                                    <th>Pays</th>
                                    <th>Secteur</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCompanies as $company)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($company->logo)
                                            <img src="{{ asset('storage/' . $company->logo) }}" 
                                                 alt="{{ $company->name }}" 
                                                 class="rounded me-2"
                                                 style="width: 32px; height: 32px; object-fit: contain;">
                                            @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="bi bi-building text-muted"></i>
                                            </div>
                                            @endif
                                            <strong>{{ $company->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $company->country }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ Str::limit($company->sector, 20) }}</small></td>
                                    <td><small class="text-muted">{{ $company->created_at->format('d/m/Y') }}</small></td>
                                    <td>
                                        @if($company->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-hourglass-split"></i> En attente
                                        </span>
                                        @elseif($company->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Approuvé
                                        </span>
                                        @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Rejeté
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.company.show', $company->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2 mb-0">Aucune inscription récente</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top secteurs -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Top secteurs</h5>
                </div>
                <div class="card-body">
                    @if($sectorStats->count() > 0)
                        @foreach($sectorStats as $sector)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">{{ Str::limit($sector->sector, 20) }}</small>
                                <strong>{{ $sector->total }}</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: {{ ($sector->total / $stats['approved']) * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0 mt-2">Aucune donnée</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.companies', ['status' => 'pending']) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-hourglass-split me-2"></i>Dossiers en attente ({{ $stats['pending'] }})
                        </a>
                        <a href="{{ route('admin.participants') }}" 
                           class="btn btn-outline-info">
                            <i class="bi bi-people me-2"></i>Voir les participants
                        </a>
                        <a href="{{ route('admin.export.companies') }}" 
                           class="btn btn-outline-success">
                            <i class="bi bi-download me-2"></i>Exporter en CSV
                        </a>
                        @if($admin->isSuperAdmin())
                        <a href="{{ route('admin.activity-logs') }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-list-check me-2"></i>Logs d'activité
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table td {
    vertical-align: middle;
}
</style>
@endsection
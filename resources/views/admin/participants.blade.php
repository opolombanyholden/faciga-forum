@extends('admin.layouts.app')

@section('title', 'Gestion des participants')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="bi bi-people"></i> Gestion des participants
                        </h4>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>

                    <!-- Formulaire de filtres -->
                    <form action="{{ route('admin.participants') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Entreprise</label>
                            <select name="company_id" class="form-select">
                                <option value="">Toutes les entreprises</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }} ({{ $company->country }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted">Recherche</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Nom, email, fonction..."
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="bi bi-search"></i> Filtrer
                                </button>
                                <a href="{{ route('admin.participants') }}" class="btn btn-outline-secondary">
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
                            <strong>{{ $participants->total() }}</strong> participant(s) trouvé(s)
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-info-circle"></i> Maximum 3 participants par entreprise
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($participants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 25%">Participant</th>
                                    <th style="width: 20%">Fonction</th>
                                    <th style="width: 25%">Contact</th>
                                    <th style="width: 20%">Entreprise</th>
                                    <th style="width: 5%" class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participants as $participant)
                                <tr>
                                    <td class="text-muted">{{ $participant->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $participant->name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i> {{ $participant->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ $participant->function }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="bi bi-envelope"></i> {{ $participant->email }}<br>
                                            <i class="bi bi-telephone"></i> {{ $participant->phone }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($participant->company)
                                        <div class="d-flex align-items-center">
                                            @if($participant->company->logo)
                                            <img src="{{ asset('storage/' . $participant->company->logo) }}" 
                                                 alt="{{ $participant->company->name }}" 
                                                 class="rounded me-2"
                                                 style="width: 30px; height: 30px; object-fit: contain; border: 1px solid #dee2e6;">
                                            @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 30px; height: 30px; border: 1px solid #dee2e6;">
                                                <i class="bi bi-building text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong class="d-block">{{ $participant->company->name }}</strong>
                                                <small class="text-muted">
                                                    @if($participant->company->country === 'Côte d\'Ivoire')
                                                    🇨🇮 CI
                                                    @else
                                                    🇬🇦 GA
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($participant->company && $participant->company->status === 'approved')
                                            @if($participant->company->confirmed)
                                            <span class="badge bg-success" title="Participation confirmée">
                                                <i class="bi bi-check-circle"></i>
                                            </span>
                                            @else
                                            <span class="badge bg-warning" title="Approuvé mais non confirmé">
                                                <i class="bi bi-clock"></i>
                                            </span>
                                            @endif
                                        @elseif($participant->company && $participant->company->status === 'pending')
                                        <span class="badge bg-secondary" title="En attente de validation">
                                            <i class="bi bi-hourglass-split"></i>
                                        </span>
                                        @else
                                        <span class="badge bg-danger" title="Entreprise rejetée">
                                            <i class="bi bi-x-circle"></i>
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-white border-0">
                        {{ $participants->links() }}
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-3">Aucun participant trouvé</p>
                        @if(request()->hasAny(['company_id', 'search']))
                        <a href="{{ route('admin.participants') }}" class="btn btn-primary btn-sm">
                            Réinitialiser les filtres
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary mb-0">{{ \App\Models\Participant::count() }}</h3>
                    <small class="text-muted">Total participants</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success mb-0">
                        {{ \App\Models\Participant::whereHas('company', function($q) {
                            $q->where('status', 'approved')->where('confirmed', true);
                        })->count() }}
                    </h3>
                    <small class="text-muted">Participations confirmées</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-info mb-0">
                        {{ number_format(\App\Models\Participant::count() / max(\App\Models\Company::where('status', 'approved')->count(), 1), 1) }}
                    </h3>
                    <small class="text-muted">Moyenne par entreprise</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table td {
    vertical-align: middle;
}
</style>
@endsection
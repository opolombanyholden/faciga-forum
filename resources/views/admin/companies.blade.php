@extends('layouts.app')

@section('title', 'Gestion des entreprises')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des entreprises inscrites</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Pays</th>
                            <th>Secteur</th>
                            <th>Contact</th>
                            <th>Statut</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td>
                                @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" style="height: 30px;" class="me-2">
                                @endif
                                <strong>{{ $company->name }}</strong>
                            </td>
                            <td>{{ $company->country }}</td>
                            <td>{{ $company->sector }}</td>
                            <td>
                                {{ $company->email }}<br>
                                <small>{{ $company->phone }}</small>
                            </td>
                            <td>
                                @if($company->status === 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($company->status === 'approved')
                                    <span class="badge bg-success">Approuvé</span>
                                    @if($company->confirmed)
                                        <span class="badge bg-primary">Confirmé</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">Rejeté</span>
                                @endif
                            </td>
                            <td>{{ $company->participants->count() }}</td>
                            <td>
                                @if($company->status === 'pending')
                                <form action="{{ route('admin.approve', $company->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" title="Approuver">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reject', $company->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Rejeter" onclick="return confirm('Rejeter cette entreprise ?')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $company->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Détails -->
                        <div class="modal fade" id="detailsModal{{ $company->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails - {{ $company->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Pays:</strong> {{ $company->country }}</p>
                                                <p><strong>Secteur:</strong> {{ $company->sector }}</p>
                                                <p><strong>Email:</strong> {{ $company->email }}</p>
                                                <p><strong>Téléphone:</strong> {{ $company->phone }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>B2B:</strong> {{ $company->wants_btob ? 'Oui' : 'Non' }}</p>
                                                <p><strong>B2G:</strong> {{ $company->wants_btog ? 'Oui' : 'Non' }}</p>
                                                <p><strong>Autres rencontres:</strong> {{ $company->other_meetings ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($company->motivation)
                                        <p><strong>Motivation:</strong><br>{{ $company->motivation }}</p>
                                        @endif

                                        @if($company->sectors_interest)
                                        <p><strong>Secteurs d'intérêt:</strong></p>
                                        <ul>
                                            @foreach($company->sectors_interest as $interest)
                                            <li>{{ $interest }}</li>
                                            @endforeach
                                        </ul>
                                        @endif

                                        @if($company->participants->count() > 0)
                                        <h6>Participants:</h6>
                                        <ul>
                                            @foreach($company->participants as $participant)
                                            <li>{{ $participant->name }} - {{ $participant->function }} ({{ $participant->email }})</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucune entreprise inscrite</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
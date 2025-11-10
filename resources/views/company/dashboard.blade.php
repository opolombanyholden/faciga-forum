@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Tableau de bord - {{ $company->name }}</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Statut du dossier</h6>
                    @if($company->status === 'pending')
                        <span class="badge bg-warning text-dark fs-6">En attente de validation</span>
                    @elseif($company->status === 'approved')
                        <span class="badge bg-success fs-6">Approuvé</span>
                    @else
                        <span class="badge bg-danger fs-6">Rejeté</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Participation confirmée</h6>
                    @if($company->confirmed)
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Oui</span>
                    @else
                        <span class="badge bg-secondary fs-6">Non</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Participants inscrits</h6>
                    <h4>{{ $company->participants->count() }} / 3</h4>
                </div>
            </div>
        </div>
    </div>

    @if($company->status === 'pending')
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Votre dossier est en cours d'analyse par le comité d'organisation. 
        Vous recevrez un email dès que votre dossier sera validé.
    </div>
    @endif

    @if($company->status === 'approved' && !$company->confirmed)
    <div class="alert alert-success">
        <h5><i class="bi bi-check-circle"></i> Félicitations ! Votre dossier a été approuvé</h5>
        <p>Veuillez confirmer votre participation pour accéder à toutes les fonctionnalités.</p>
        <form action="{{ route('company.confirm') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-hand-thumbs-up"></i> Confirmer ma participation
            </button>
        </form>
    </div>
    @endif

    @if($company->confirmed)
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Mes participants</h5>
                </div>
                <div class="card-body">
                    @if($company->participants->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($company->participants as $participant)
                            <li class="list-group-item">
                                <strong>{{ $participant->name }}</strong><br>
                                <small class="text-muted">{{ $participant->function }}</small><br>
                                <small>{{ $participant->email }} | {{ $participant->phone }}</small>
                                <form action="{{ route('participants.destroy', $participant->id) }}" method="POST" class="d-inline float-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce participant ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucun participant ajouté</p>
                    @endif
                    
                    @if($company->participants->count() < 3)
                    <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
                        <i class="bi bi-plus-circle"></i> Ajouter un participant
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Mes rendez-vous</h5>
                </div>
                <div class="card-body">
                    @php
                    $sentRequests = $company->sentMeetingRequests;
                    $receivedRequests = $company->receivedMeetingRequests;
                    @endphp
                    
                    @if($sentRequests->count() + $receivedRequests->count() > 0)
                        <h6>Demandes envoyées:</h6>
                        @foreach($sentRequests as $request)
                        <div class="mb-2">
                            <strong>{{ $request->receiver->name }}</strong>
                            <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'accepted' ? 'success' : 'danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        @endforeach
                        
                        <h6 class="mt-3">Demandes reçues:</h6>
                        @foreach($receivedRequests as $request)
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $request->sender->name }}</strong><br>
                                <small>{{ $request->meeting_type }}</small>
                            </div>
                            @if($request->status === 'pending')
                            <div>
                                <form action="{{ route('meeting.respond', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-sm btn-success">Accepter</button>
                                </form>
                                <form action="{{ route('meeting.respond', $request->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-danger">Refuser</button>
                                </form>
                            </div>
                            @else
                            <span class="badge bg-{{ $request->status === 'accepted' ? 'success' : 'danger' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">Aucun rendez-vous pour le moment</p>
                    @endif
                    
                    <a href="{{ route('company.directory') }}" class="btn btn-success mt-3">
                        <i class="bi bi-search"></i> Rechercher des entreprises
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Ajouter Participant -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('participants.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="participant_name" class="form-label">Nom et prénoms *</label>
                        <input type="text" class="form-control" id="participant_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="participant_function" class="form-label">Fonction *</label>
                        <input type="text" class="form-control" id="participant_function" name="function" required>
                    </div>
                    <div class="mb-3">
                        <label for="participant_email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="participant_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="participant_phone" class="form-label">Téléphone *</label>
                        <input type="tel" class="form-control" id="participant_phone" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
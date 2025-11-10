@extends('layouts.app')

@section('title', 'Annuaire des entreprises')

@section('content')
<div class="container my-5">
    <h2 class="mb-4"><i class="bi bi-building"></i> Annuaire des entreprises participantes</h2>
    
    <p class="text-muted">Découvrez les entreprises inscrites et confirmées pour le FACIGA 2025</p>

    <div class="row">
        @forelse($companies as $company)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 card-faciga">
                <div class="card-body">
                    @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" class="img-fluid mb-3" alt="{{ $company->name }}" style="max-height: 80px;">
                    @endif
                    
                    <h5 class="card-title">{{ $company->name }}</h5>
                    <p class="card-text">
                        <i class="bi bi-geo-alt text-primary"></i> {{ $company->country }}<br>
                        <i class="bi bi-briefcase text-success"></i> {{ $company->sector }}
                    </p>
                    
                    @if($company->sectors_interest)
                    <p class="card-text">
                        <small class="text-muted">
                            <strong>Intérêts:</strong><br>
                            @foreach(array_slice($company->sectors_interest, 0, 3) as $interest)
                                <span class="badge bg-light text-dark">{{ $interest }}</span>
                            @endforeach
                            @if(count($company->sectors_interest) > 3)
                                <span class="badge bg-light text-dark">+{{ count($company->sectors_interest) - 3 }}</span>
                            @endif
                        </small>
                    </p>
                    @endif
                    
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#meetingModal{{ $company->id }}">
                        <i class="bi bi-calendar-plus"></i> Demander un rendez-vous
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Demande de rendez-vous -->
        <div class="modal fade" id="meetingModal{{ $company->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Demander un rendez-vous avec {{ $company->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('meeting.request') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_company_id" value="{{ $company->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Type de rendez-vous *</label>
                                <select class="form-select" name="meeting_type" required>
                                    <option value="">Sélectionnez...</option>
                                    <option value="btob">B2B (Business to Business)</option>
                                    <option value="btog">B2G (Business to Government)</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message (optionnel)</label>
                                <textarea class="form-control" name="message" rows="3" placeholder="Décrivez brièvement l'objet du rendez-vous..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Aucune entreprise disponible pour le moment.
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
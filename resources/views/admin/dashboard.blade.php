@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tableau de bord administrateur</h2>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Dossiers en attente</h6>
                    <h2>{{ $stats['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Dossiers approuvés</h6>
                    <h2>{{ $stats['approved'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Participations confirmées</h6>
                    <h2>{{ $stats['confirmed'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Gestion des inscriptions</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.companies') }}" class="btn btn-primary">
                <i class="bi bi-list"></i> Voir toutes les entreprises
            </a>
        </div>
    </div>
</div>
@endsection
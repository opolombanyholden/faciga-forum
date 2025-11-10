<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MeetingRequestController;

// Pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

// Inscription
Route::get('/inscription', [CompanyController::class, 'create'])->name('inscription');
Route::post('/inscription', [CompanyController::class, 'store'])->name('inscription.store');
Route::get('/inscription/confirmation', [CompanyController::class, 'confirmation'])->name('inscription.confirmation');

// Authentification entreprise
Route::get('/login', [CompanyController::class, 'loginForm'])->name('login');
Route::post('/login', [CompanyController::class, 'login'])->name('login.submit');
Route::post('/logout', [CompanyController::class, 'logout'])->name('logout');

// Espace entreprise (authentifié)
Route::middleware('auth:company')->prefix('dashboard')->group(function () {
    Route::get('/', [CompanyController::class, 'dashboard'])->name('company.dashboard');
    Route::get('/profile', [CompanyController::class, 'profile'])->name('company.profile');
    Route::put('/profile', [CompanyController::class, 'updateProfile'])->name('company.update');
    Route::post('/confirm-participation', [CompanyController::class, 'confirmParticipation'])->name('company.confirm');
    Route::resource('participants', \App\Http\Controllers\ParticipantController::class);
    
    // Annuaire
    Route::get('/directory', [CompanyController::class, 'directory'])->name('company.directory');
    
    // Demandes de rendez-vous
    Route::post('/meeting-request', [MeetingRequestController::class, 'store'])->name('meeting.request');
    Route::put('/meeting-request/{id}/respond', [MeetingRequestController::class, 'respond'])->name('meeting.respond');
});

// Espace administrateur
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/companies', [AdminController::class, 'companies'])->name('admin.companies');
        Route::put('/companies/{id}/approve', [AdminController::class, 'approve'])->name('admin.approve');
        Route::put('/companies/{id}/reject', [AdminController::class, 'reject'])->name('admin.reject');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});
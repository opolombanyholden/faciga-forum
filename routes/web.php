<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MeetingRequestController;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
*/

// ============================================================================
// PAGES PUBLIQUES
// ============================================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

// ============================================================================
// INSCRIPTION
// ============================================================================

Route::get('/inscription', [CompanyController::class, 'create'])->name('inscription');
Route::post('/inscription', [CompanyController::class, 'store'])->name('inscription.store');
Route::get('/inscription/confirmation', [CompanyController::class, 'confirmation'])->name('inscription.confirmation');

// ============================================================================
// AUTHENTIFICATION ENTREPRISE
// ============================================================================

Route::get('/login', [CompanyController::class, 'loginForm'])->name('login');
Route::post('/login', [CompanyController::class, 'login'])->name('login.submit');
Route::post('/logout', [CompanyController::class, 'logout'])->name('logout');

// ============================================================================
// ESPACE ENTREPRISE (Authentifié)
// ============================================================================

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

// ============================================================================
// ESPACE ADMINISTRATEUR - COMPLET ET AMÉLIORÉ
// ============================================================================

Route::prefix('admin')->name('admin.')->group(function () {
    
    // ========================================================================
    // Login (sans authentification)
    // ========================================================================
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
    
    // ========================================================================
    // Routes protégées (avec authentification admin)
    // ========================================================================
    Route::middleware('auth:admin')->group(function () {
        
        // Dashboard principal
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // ====================================================================
        // Gestion des entreprises (avec groupe nommé)
        // ====================================================================
        Route::prefix('companies')->name('companies.')->group(function () {
            Route::get('/', [AdminController::class, 'companies'])->name('index');
            Route::get('/{id}', [AdminController::class, 'showCompany'])->name('show');
            Route::put('/{id}/approve', [AdminController::class, 'approve'])->name('approve');
            Route::put('/{id}/reject', [AdminController::class, 'reject'])->name('reject');
            Route::delete('/{id}', [AdminController::class, 'deleteCompany'])->name('delete');
        });
        
        // Raccourcis pour compatibilité avec l'ancien système
        Route::get('/companies', [AdminController::class, 'companies'])->name('companies');
        Route::get('/companies/{id}', [AdminController::class, 'showCompany'])->name('company.show');
        Route::put('/companies/{id}/approve', [AdminController::class, 'approve'])->name('approve');
        Route::put('/companies/{id}/reject', [AdminController::class, 'reject'])->name('reject');
        
        // ====================================================================
        // Gestion des participants
        // ====================================================================
        Route::get('/participants', [AdminController::class, 'participants'])->name('participants');
        
        // ====================================================================
        // Export des données
        // ====================================================================
        Route::get('/export/companies', [AdminController::class, 'exportCompanies'])->name('export.companies');
        
        // ====================================================================
        // Logs d'activité (Super Admin et Moderator uniquement)
        // ====================================================================
        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
        
        // ====================================================================
        // Déconnexion
        // ====================================================================
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        
        // Raccourci pour compatibilité
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

// ============================================================================
// NOTES DE DÉVELOPPEMENT
// ============================================================================
/*
 * Routes Admin disponibles:
 * 
 * GET  /admin/login                       - Formulaire de connexion
 * POST /admin/login                       - Traiter la connexion
 * GET  /admin                             - Dashboard principal
 * GET  /admin/companies                   - Liste des entreprises (avec filtres)
 * GET  /admin/companies/{id}              - Détails d'une entreprise
 * PUT  /admin/companies/{id}/approve      - Approuver une entreprise
 * PUT  /admin/companies/{id}/reject       - Rejeter une entreprise
 * DELETE /admin/companies/{id}            - Supprimer une entreprise (Super Admin)
 * GET  /admin/participants                - Liste des participants
 * GET  /admin/export/companies            - Exporter les données en CSV
 * GET  /admin/activity-logs               - Consulter les logs d'activité
 * POST /admin/logout                      - Déconnexion
 * 
 * Permissions:
 * - Super Admin: Accès total
 * - Moderator: Validation + Gestion participants + Logs
 * - Analyst: Lecture seule + Export
 * - Webmaster: Contenu web uniquement (pas d'accès entreprises)
 */
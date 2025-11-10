<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_sessions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('admin_id')
                  ->constrained('admins')
                  ->onDelete('cascade')
                  ->comment('ID de l\'administrateur');
            
            $table->string('session_id')
                  ->unique()
                  ->comment('ID unique de la session Laravel');
            
            $table->string('ip_address', 45)
                  ->comment('Adresse IP de connexion');
            
            $table->text('user_agent')
                  ->comment('User Agent du navigateur');
            
            $table->string('device_type')
                  ->nullable()
                  ->comment('Type d\'appareil: desktop, mobile, tablet');
            
            $table->string('browser')
                  ->nullable()
                  ->comment('Navigateur utilisé');
            
            $table->string('location')
                  ->nullable()
                  ->comment('Localisation géographique si disponible');
            
            $table->timestamp('login_at')
                  ->comment('Timestamp de connexion');
            
            $table->timestamp('logout_at')
                  ->nullable()
                  ->comment('Timestamp de déconnexion');
            
            $table->timestamp('last_activity')
                  ->nullable()
                  ->comment('Dernière activité enregistrée');
            
            $table->boolean('is_active')
                  ->default(true)
                  ->comment('Indique si la session est active');
            
            $table->boolean('forced_logout')
                  ->default(false)
                  ->comment('Indique si la déconnexion a été forcée');
            
            $table->text('logout_reason')
                  ->nullable()
                  ->comment('Raison de la déconnexion si applicable');
            
            $table->timestamps();

            // Index pour optimiser les performances
            $table->index(['admin_id', 'is_active'], 'idx_admin_sessions_admin_active');
            $table->index(['session_id', 'is_active'], 'idx_admin_sessions_session_active');
            $table->index('last_activity', 'idx_admin_sessions_activity');
            $table->index(['login_at', 'logout_at'], 'idx_admin_sessions_login_logout');
            $table->index('ip_address', 'idx_admin_sessions_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sessions');
    }
};
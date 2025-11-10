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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('admin_id')
                  ->constrained('admins')
                  ->onDelete('cascade')
                  ->comment('ID de l\'administrateur qui a effectué l\'action');
            
            $table->string('action')
                  ->comment('Action effectuée: login, logout, approve_company, reject_company, create_participant, update_content, create_category, etc.');
            
            $table->string('model_type')
                  ->nullable()
                  ->comment('Type de modèle affecté: Company, Participant, WebContent, WebCategory, etc.');
            
            $table->unsignedBigInteger('model_id')
                  ->nullable()
                  ->comment('ID du modèle affecté');
            
            $table->json('old_values')
                  ->nullable()
                  ->comment('Anciennes valeurs avant modification (format JSON)');
            
            $table->json('new_values')
                  ->nullable()
                  ->comment('Nouvelles valeurs après modification (format JSON)');
            
            $table->string('ip_address', 45)
                  ->nullable()
                  ->comment('Adresse IP de l\'utilisateur');
            
            $table->text('user_agent')
                  ->nullable()
                  ->comment('User Agent du navigateur');
            
            $table->text('description')
                  ->nullable()
                  ->comment('Description détaillée de l\'action effectuée');
            
            $table->timestamps();

            // Index pour optimiser les performances des requêtes
            $table->index(['admin_id', 'created_at'], 'idx_admin_activity_admin_date');
            $table->index(['action', 'created_at'], 'idx_admin_activity_action_date');
            $table->index(['model_type', 'model_id'], 'idx_admin_activity_model');
            $table->index('created_at', 'idx_admin_activity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
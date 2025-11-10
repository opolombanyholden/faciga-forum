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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('phone')
                  ->nullable()
                  ->after('email')
                  ->comment('Numéro de téléphone');
            
            $table->enum('role', ['super_admin', 'moderator', 'analyst', 'webmaster'])
                  ->default('moderator')
                  ->after('phone')
                  ->comment('Rôle de l\'administrateur: super_admin (tous droits), moderator (validation dossiers + participants), analyst (consultation), webmaster (gestion contenu web)');
            
            $table->enum('status', ['active', 'inactive', 'suspended'])
                  ->default('active')
                  ->after('role')
                  ->comment('Statut du compte administrateur');
            
            $table->timestamp('last_login_at')
                  ->nullable()
                  ->after('status')
                  ->comment('Dernière connexion');
            
            $table->json('permissions')
                  ->nullable()
                  ->after('last_login_at')
                  ->comment('Permissions granulaires: peut inclure create_categories, manage_content, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role', 
                'status', 
                'last_login_at', 
                'permissions'
            ]);
        });
    }
};
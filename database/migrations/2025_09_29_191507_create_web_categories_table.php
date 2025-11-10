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
        Schema::create('web_categories', function (Blueprint $table) {
            $table->id();
            
            $table->string('name')
                  ->comment('Nom de la rubrique');
            
            $table->string('slug')
                  ->unique()
                  ->comment('Slug URL-friendly de la rubrique');
            
            $table->text('description')
                  ->nullable()
                  ->comment('Description de la rubrique');
            
            $table->unsignedBigInteger('parent_id')
                  ->nullable()
                  ->comment('ID de la rubrique parente (pour la hiérarchie)');
            
            $table->integer('sort_order')
                  ->default(0)
                  ->comment('Ordre d\'affichage dans la hiérarchie');
            
            $table->boolean('is_active')
                  ->default(true)
                  ->comment('Indique si la rubrique est active');
            
            $table->string('icon')
                  ->nullable()
                  ->comment('Icône pour l\'interface d\'administration');
            
            $table->string('color')
                  ->nullable()
                  ->comment('Couleur pour l\'interface d\'administration');
            
            $table->json('metadata')
                  ->nullable()
                  ->comment('Métadonnées supplémentaires');
            
            $table->foreignId('created_by')
                  ->constrained('admins')
                  ->comment('Administrateur qui a créé la rubrique');
            
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('admins')
                  ->comment('Dernier administrateur qui a modifié la rubrique');
            
            $table->timestamps();

            // Index pour optimiser les performances
            $table->index(['parent_id', 'sort_order'], 'idx_web_categories_parent_order');
            $table->index(['is_active', 'sort_order'], 'idx_web_categories_active_order');
            $table->index('slug', 'idx_web_categories_slug');
        });
        
        // Ajouter la contrainte de clé étrangère pour parent_id APRÈS la création de la table
        Schema::table('web_categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('web_categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_categories');
    }
};
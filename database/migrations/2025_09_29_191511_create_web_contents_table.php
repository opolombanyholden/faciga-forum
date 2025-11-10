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
        Schema::create('web_contents', function (Blueprint $table) {
            $table->id();
            
            $table->string('key')
                  ->unique()
                  ->comment('Clé unique pour identifier le contenu (ex: home_banner, about_text)');
            
            $table->string('title')
                  ->comment('Titre descriptif du contenu');
            
            $table->longText('content')
                  ->comment('Contenu principal (texte, HTML, URL, etc.)');
            
            $table->enum('content_type', ['text', 'html', 'image', 'video', 'file'])
                  ->default('text')
                  ->comment('Type de contenu');
            
            $table->foreignId('category_id')
                  ->constrained('web_categories')
                  ->onDelete('cascade')
                  ->comment('ID de la rubrique à laquelle appartient le contenu');
            
            $table->boolean('is_active')
                  ->default(true)
                  ->comment('Indique si le contenu est actif/publié');
            
            $table->string('meta_description')
                  ->nullable()
                  ->comment('Description pour le SEO et l\'administration');
            
            $table->json('metadata')
                  ->nullable()
                  ->comment('Métadonnées supplémentaires (alt text, dimensions, etc.)');
            
            $table->integer('sort_order')
                  ->default(0)
                  ->comment('Ordre d\'affichage si applicable');
            
            $table->foreignId('created_by')
                  ->constrained('admins')
                  ->comment('Administrateur qui a créé le contenu');
            
            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('admins')
                  ->comment('Dernier administrateur qui a modifié le contenu');
            
            $table->timestamps();

            // Index pour optimiser les performances
            $table->index(['key', 'is_active'], 'idx_web_content_key_active');
            $table->index(['content_type', 'is_active'], 'idx_web_content_type_active');
            $table->index(['category_id', 'sort_order'], 'idx_web_content_category_order');
            $table->index('is_active', 'idx_web_content_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_contents');
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebCategory;
use App\Models\Admin;

class WebCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le super admin pour les créations initiales
        $superAdmin = Admin::where('role', Admin::ROLE_SUPER_ADMIN)->first();
        
        if (!$superAdmin) {
            $this->command->error('Aucun super admin trouvé. Exécutez d\'abord AdminSeeder.');
            return;
        }

        // Catégories principales
        $homeCategory = WebCategory::create([
            'name' => 'Page d\'accueil',
            'slug' => 'home',
            'description' => 'Contenu de la page d\'accueil du site',
            'sort_order' => 1,
            'icon' => 'house',
            'color' => '#3A75C4',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        $aboutCategory = WebCategory::create([
            'name' => 'À propos',
            'slug' => 'about',
            'description' => 'Informations sur l\'événement FACIGA',
            'sort_order' => 2,
            'icon' => 'info-circle',
            'color' => '#009E49',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        $programCategory = WebCategory::create([
            'name' => 'Programme',
            'slug' => 'program',
            'description' => 'Programme détaillé de l\'événement',
            'sort_order' => 3,
            'icon' => 'calendar-event',
            'color' => '#FF8C00',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        $contactCategory = WebCategory::create([
            'name' => 'Contact',
            'slug' => 'contact',
            'description' => 'Informations de contact',
            'sort_order' => 4,
            'icon' => 'envelope',
            'color' => '#6c757d',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        // Sous-catégories de la page d'accueil
        WebCategory::create([
            'name' => 'Bannière principale',
            'slug' => 'home-banner',
            'description' => 'Éléments de la bannière principale',
            'parent_id' => $homeCategory->id,
            'sort_order' => 1,
            'icon' => 'image',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        WebCategory::create([
            'name' => 'Statistiques',
            'slug' => 'home-stats',
            'description' => 'Statistiques affichées sur la page d\'accueil',
            'parent_id' => $homeCategory->id,
            'sort_order' => 2,
            'icon' => 'graph-up',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        // Sous-catégories du programme
        WebCategory::create([
            'name' => 'Jour 1',
            'slug' => 'program-day1',
            'description' => 'Programme du premier jour',
            'parent_id' => $programCategory->id,
            'sort_order' => 1,
            'icon' => 'calendar',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        WebCategory::create([
            'name' => 'Jour 2',
            'slug' => 'program-day2',
            'description' => 'Programme du deuxième jour',
            'parent_id' => $programCategory->id,
            'sort_order' => 2,
            'icon' => 'calendar',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        // Catégorie pour les secteurs
        WebCategory::create([
            'name' => 'Secteurs prioritaires',
            'slug' => 'sectors',
            'description' => 'Informations sur les secteurs prioritaires',
            'sort_order' => 5,
            'icon' => 'briefcase',
            'color' => '#dc3545',
            'is_active' => true,
            'created_by' => $superAdmin->id,
            'updated_by' => $superAdmin->id,
        ]);

        $this->command->info('Catégories web créées avec succès !');
    }
}
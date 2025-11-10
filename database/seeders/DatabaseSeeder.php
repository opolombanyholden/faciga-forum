<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Démarrage du seeding de la base de données...');
        $this->command->newLine();

        $this->call([
            AdminSeeder::class,
            WebCategorySeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Seeding terminé avec succès !');
    }
}
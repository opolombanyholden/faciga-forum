<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Création des administrateurs...');

        // Super Admin principal
        $superAdmin = Admin::updateOrCreate(
            ['email' => 'superadmin@faciga.ga'],
            [
                'name' => 'Super Administrateur FACIGA',
                'password' => Hash::make('SuperAdmin2025!'),
                'role' => Admin::ROLE_SUPER_ADMIN,
                'status' => Admin::STATUS_ACTIVE,
                'phone' => '+241 11 76 48 48',
            ]
        );
        $this->command->info('✅ Super Admin créé');

        // Modérateur ANPI-Gabon
        Admin::updateOrCreate(
            ['email' => 'moderateur@anpi-gabon.ga'],
            [
                'name' => 'Modérateur ANPI-Gabon',
                'password' => Hash::make('Moderateur2025!'),
                'role' => Admin::ROLE_MODERATOR,
                'status' => Admin::STATUS_ACTIVE,
                'phone' => '+241 11 76 48 48',
            ]
        );
        $this->command->info('✅ Modérateur créé');

        // Modérateur CEPICI
        Admin::updateOrCreate(
            ['email' => 'moderateur@cepici.ci'],
            [
                'name' => 'Modérateur CEPICI',
                'password' => Hash::make('Moderateur2025!'),
                'role' => Admin::ROLE_MODERATOR,
                'status' => Admin::STATUS_ACTIVE,
                'phone' => '+225 XX XX XX XX',
            ]
        );
        $this->command->info('✅ Modérateur CEPICI créé');

        // Analyste
        Admin::updateOrCreate(
            ['email' => 'analyste@faciga.ga'],
            [
                'name' => 'Analyste FACIGA',
                'password' => Hash::make('Analyste2025!'),
                'role' => Admin::ROLE_ANALYST,
                'status' => Admin::STATUS_ACTIVE,
                'phone' => '+241 XX XX XX XX',
            ]
        );
        $this->command->info('✅ Analyste créé');

        // Webmaster
        Admin::updateOrCreate(
            ['email' => 'webmaster@faciga.ga'],
            [
                'name' => 'Webmaster FACIGA',
                'password' => Hash::make('Webmaster2025!'),
                'role' => Admin::ROLE_WEBMASTER,
                'status' => Admin::STATUS_ACTIVE,
                'phone' => '+241 XX XX XX XX',
            ]
        );
        $this->command->info('✅ Webmaster créé');

        $this->command->newLine();
        $this->command->info('🎉 Tous les administrateurs ont été créés avec succès !');
        $this->command->newLine();
        $this->command->warn('📋 IDENTIFIANTS PAR DÉFAUT :');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Super Admin', 'superadmin@faciga.ga', 'SuperAdmin2025!'],
                ['Modérateur Gabon', 'moderateur@anpi-gabon.ga', 'Moderateur2025!'],
                ['Modérateur CI', 'moderateur@cepici.ci', 'Moderateur2025!'],
                ['Analyste', 'analyste@faciga.ga', 'Analyste2025!'],
                ['Webmaster', 'webmaster@faciga.ga', 'Webmaster2025!'],
            ]
        );
        $this->command->newLine();
        $this->command->warn('⚠️  IMPORTANT : Changez ces mots de passe en production !');
    }
}
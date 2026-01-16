<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'Administrateur
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@datacenter.com', // LOGIN
            'password' => Hash::make('password'), // MOT DE PASSE
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Création d'un Responsable Technique
        $manager = User::create([
            'name' => 'Jean Responsable',
            'email' => 'manager@datacenter.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        // 3. Création de Ressources de test
        Resource::create([
            'label' => 'Serveur Dell PowerEdge R740',
            'category' => 'Serveur Physique',
            'description' => 'Serveur haute performance pour calcul intensif.',
            'location' => 'Baie A - Rack 4',
            'status' => 'available',
            'specifications' => json_encode(['CPU' => 'Intel Xeon Gold', 'RAM' => '64GB', 'Disk' => '2TB SSD']),
            'manager_id' => $manager->id,
        ]);

        Resource::create([
            'label' => 'VM Ubuntu 22.04',
            'category' => 'Machine Virtuelle',
            'description' => 'Instance virtuelle pour hébergement web.',
            'location' => 'Cluster Virtualisation',
            'status' => 'available',
            'specifications' => json_encode(['vCPU' => '4', 'RAM' => '8GB', 'OS' => 'Ubuntu']),
            'manager_id' => $manager->id,
        ]);
    }
}
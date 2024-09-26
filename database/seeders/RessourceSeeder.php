<?php

namespace Database\Seeders;

use App\Models\Engrais;
use App\Models\Semence;
use App\Models\Ressource;
use App\Models\Equipement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RessourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ressource::create(); // Créer des ressources génériques

        Ressource::create();

        Ressource::create();
    }
}

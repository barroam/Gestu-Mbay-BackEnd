<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EquipementRessource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipementRessourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        EquipementRessource::create([

            'ressource_id' => 1, // Ressource associée
            'equipement_id' => 1, // Equipement associé
        ]);

        EquipementRessource::create([
 
            'ressource_id' => 2,
            'equipement_id' => 2,
        ]);
    }
}

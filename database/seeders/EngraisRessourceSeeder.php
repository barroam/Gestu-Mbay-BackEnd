<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EngraisRessource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EngraisRessourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        EngraisRessource::create([
            'variete' => 'Engrais bio',
            'quantite' => 50,
            'ressource_id' => 1, // Ressource associée
            'engrais_id' => 1, // Engrais associé
        ]);

        EngraisRessource::create([
            'variete' => 'Engrais chimique puissant',
            'quantite' => 75,
            'ressource_id' => 2,
            'engrais_id' => 2,
        ]);
    }
}

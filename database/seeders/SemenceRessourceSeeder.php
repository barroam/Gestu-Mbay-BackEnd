<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SemenceRessource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SemenceRessourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SemenceRessource::create([
            'variete' => 'Blé dur',
            'quantite' => 100,
            'ressource_id' => 1, // Ressource associée
            'semence_id' => 1, // Semence associée
        ]);

        SemenceRessource::create([
            'variete' => 'Maïs hybride',
            'quantite' => 200,
            'ressource_id' => 2,
            'semence_id' => 2,
        ]);
    }
}

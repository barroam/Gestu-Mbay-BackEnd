<?php

namespace Database\Seeders;

use App\Models\Equipement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipement::create([
            'nom' => 'Tracteur agricole',
            'description' => 'Tracteur moderne pour grandes exploitations',
            'image' => 'https://img.freepik.com/photos-gratuite/tracteur-travaillant-terrain_342744-535.jpg?ga=GA1.1.2086964233.1726844894&semt=ais_hybrid',
        ]);

        Equipement::create([
            'nom' => 'Moissonneuse-batteuse',
            'description' => 'Machine pour récolter les céréales',
            'image' => 'https://img.freepik.com/photos-gratuite/tracteur-travaillant-terrain_342744-535.jpg?ga=GA1.1.2086964233.1726844894&semt=ais_hybrid',
        ]);

        Equipement::create([
            'nom' => 'Pulvérisateur',
            'description' => 'Équipement pour traitement des cultures',
            'image' => 'https://img.freepik.com/photos-gratuite/vue-du-tracteur-graphique-3d_23-2150849079.jpg?ga=GA1.1.2086964233.1726844894&semt=ais_hybrid',
        ]);
    
    }
}

<?php

namespace Database\Seeders;

use App\Models\Engrais;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EngraisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Insérer des engrais de test
         Engrais::create([
            'nom' => 'Engrais NPK 15-15-15',
            'image' => 'https://img.freepik.com/photos-gratuite/bouteille-pulverisation-pres-du-sac-outils-jardinage_23-2147714846.jpg?ga=GA1.1.2086964233.1726844894&semt=ais_hybrid',
        ]);

        Engrais::create([
            'nom' => 'Engrais urée',
            'image' => 'https://img.freepik.com/photos-gratuite/briller_23-2148110888.jpg?ga=GA1.1.2086964233.1726844894',
        ]);

        Engrais::create([
            'nom' => 'Engrais organique',
            'image' => 'https://img.freepik.com/photos-gratuite/fond-goutte-eau_23-2147787453.jpg?ga=GA1.1.2086964233.1726844894',
        ]);
    
    }
}

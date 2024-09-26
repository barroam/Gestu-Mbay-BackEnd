<?php

namespace Database\Seeders;

use App\Models\Semence;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SemenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Semence::create([
            'nom' => 'Maïs',
            'image' => 'https://img.freepik.com/photos-gratuite/graines-pop-organique-manger-du-mais-doux_1203-5997.jpg?ga=GA1.1.2086964233.1726844894', // Chemin ou nom de l'image
        ]);

        Semence::create([
            'nom' => 'Riz',
            'image' => 'https://img.freepik.com/photos-gratuite/riz-blanc_144627-34090.jpg?t=st=1727351766~exp=1727355366~hmac=02d37a5ed08ea95b7a76865a5dd379a7997581995f21989431a4867610678646&w=740',
        ]);

        Semence::create([
            'nom' => 'Blé',
            'image' => 'https://img.freepik.com/photos-gratuite/gros-plan-details-du-papier-peint-riz-paddy_1150-34305.jpg?t=st=1727351844~exp=1727355444~hmac=58a0617b8ec402c36d5bb65157a48d0df15db455491a211a20345a7455a0ded9&w=740',
        ]);
    
    }
}

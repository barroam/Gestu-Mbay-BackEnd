<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\EngraisSeeder;
use Database\Seeders\SemenceSeeder;
use Database\Seeders\RessourceSeeder;
use Database\Seeders\EquipementSeeder;
use Database\Seeders\EngraisRessourceSeeder;
use Database\Seeders\SemenceRessourceSeeder;
use Database\Seeders\EquipementRessourceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SemenceSeeder::class,
            EngraisSeeder::class,
            EquipementSeeder::class,
            RessourceSeeder::class,
            SemenceRessourceSeeder::class,
            EngraisRessourceSeeder::class,
            EquipementRessourceSeeder::class,
        ]);
    
    }
}

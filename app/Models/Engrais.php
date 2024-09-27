<?php

namespace App\Models;

use App\Models\Ressource;
use App\Models\EngraisRessource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Engrais extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'engrais_ressources')
                    ->withPivot('variete', 'quantite'); // Ajoutez les colonnes de la table pivot si nécessaire
    }
}

<?php

namespace App\Models;

use App\Models\Ressource;
use App\Models\SemenceRessource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semence extends Model
{
    use HasFactory;
    protected $guard= [];

    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'semence_ressources')
                    ->withPivot('variete', 'quantite');
    }
}

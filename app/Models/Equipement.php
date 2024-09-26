<?php

namespace App\Models;

use App\Models\Ressource;
use App\Models\EquipementRessource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipement extends Model
{
    use HasFactory;
    protected $guard= [];
    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'equipement_ressources');
                   
    }
}

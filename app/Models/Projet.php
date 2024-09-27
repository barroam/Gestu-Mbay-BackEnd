<?php

namespace App\Models;

use App\Models\Contrat;
use App\Models\AvisProjet;
use App\Models\HistoriqueProjet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function historiqueProjets()
    {
        return $this->hasMany(HistoriqueProjet::class);
    }

    public function contrats()
    {
        return $this->hasOne(Contrat::class);
    }

    public function avis()
    {
        return $this->hasMany(AvisProjet::class);
    }
}

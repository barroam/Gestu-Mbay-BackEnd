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

    public function historiques()
    {
        return $this->hasMany(HistoriqueProjet::class, 'projet_id');
    }
    public function addHistorique()
    {
        $this->historiques()->create([
            'type_activite' => $this->type_activite,
            'date' => $this->date,
            'etat' => $this->etat,
            'attentes' => $this->attentes,
            'obstacles' => $this->obstacles,
            'solutions' => $this->solutions,
            'date_fin' => $this->date_fin,
            'projet_id' => $this->id,
        ]);
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

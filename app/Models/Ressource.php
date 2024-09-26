<?php

namespace App\Models;

use App\Models\Contrat;
use App\Models\Demande;
use App\Models\Engrais;
use App\Models\Semence;
use App\Models\Equipement;
use App\Models\Rendezvous;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ressource extends Model
{
    use HasFactory;
    protected $guard= [];

    public function Demande () {
        return $this->hasOne(Demande::class);
    } 
    public function Equipement () {
        return $this->belongsToMany(Equipement::class);
    } 
      public function Engrais() {
        return $this->belongsToMany(Engrais::class);
    } 
      public function Semences () {
        return $this->belongsToMany( Semence::class);
    } 
    public function Contrat()
    {
        return $this->hasOne(Contrat::class);
    }
    public function Rendezvous(){
        return $this->hasOne(Rendezvous::class);
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ressource;
use App\Models\Rendezvous;
use App\Models\InfoDemande;
use App\Models\ControleDemande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demande extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }

    public function controleDemande()
    {
        return $this->belongsTo(ControleDemande::class);
    }

    public function infoDemande()
    {
        return $this->belongsTo(InfoDemande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

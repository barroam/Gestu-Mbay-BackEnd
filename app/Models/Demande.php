<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ressource;
use App\Models\Rendezvous;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demande extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Ressource () {
        return $this->hasOne(Ressource::class);
    } 
    public function Rendezvous()
    {
        return $this->hasOne(Rendezvous::class);
    }
    public function info_demande(){
        return $this->hasOne(InfoDemande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

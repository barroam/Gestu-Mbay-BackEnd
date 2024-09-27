<?php

namespace App\Models;

use App\Models\Demande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControleDemande extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Demande () {
        return $this->hasOne(Demande::class);
    } 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;
    protected $guard= [];
    public function Ressource () {
        return $this->belongsToMany(Ressource::class);
    } 
}

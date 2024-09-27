<?php

namespace App\Models;

use App\Models\User;
use App\Models\Projet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvisProjet extends Model
{
    use HasFactory;
    
    protected $guarded = [];


    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

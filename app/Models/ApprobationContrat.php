<?php

namespace App\Models;

use App\Models\User;
use App\Models\Projet;
use App\Models\Contrat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprobationContrat extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use App\Models\User;
use App\Models\Demande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rendezvous extends Model
{
    use HasFactory;
    protected $guard= [];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}

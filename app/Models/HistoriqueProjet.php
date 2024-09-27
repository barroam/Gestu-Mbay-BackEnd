<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueProjet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }
}

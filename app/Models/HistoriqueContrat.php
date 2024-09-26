<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueContrat extends Model
{
    use HasFactory;
    protected $guard= [];
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }
}

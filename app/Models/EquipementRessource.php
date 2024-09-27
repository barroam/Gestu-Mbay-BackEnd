<?php

namespace App\Models;

use App\Models\Equipement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipementRessource extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }
}

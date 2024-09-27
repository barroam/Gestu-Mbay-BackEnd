<?php

namespace App\Models;

use App\Models\Engrais;
use App\Models\Ressource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EngraisRessource extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }

    public function engrais()
    {
        return $this->belongsTo(Engrais::class);
    }
}

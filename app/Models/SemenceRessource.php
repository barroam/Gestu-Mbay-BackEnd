<?php

namespace App\Models;

use App\Models\Semence;
use App\Models\Ressource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SemenceRessource extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ressource()
    {
        return $this->belongsTo(Ressource::class);
    }

    public function semence()
    {
        return $this->belongsTo(Semence::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    
    protected $fillable= ['Nombre','id_club','torneo_id'];
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
    use HasFactory;
}

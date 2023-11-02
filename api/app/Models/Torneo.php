<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    protected $fillable=['nombre','fecha'];
    public $timestamps = false;
    public function participantes()
    {
        return $this->hasMany(Participante::class);
    }
    use HasFactory;
}

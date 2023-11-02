<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participante; // Importa el modelo Participantes correctamente
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index()
    {
        $participantes = Participante::all();
        return $participantes;
    }
    public function store(Request $request){
        $participante=new Participante();
        $participante->Nombre=$request->Nombre;
        $participante->save();
    }
    public function show(string $id){
        $participante=Participante::find($id);
        return $participante;
    }
    public function update(Request $request){
        $participante=Participante::findOrfail($request->id);
        $participante->nombre=$request->nombre;
        $participante->save();
        return $participante;
    }
    public function destroy (string $id){
        $participante=Participante::destroy($id);
        return $participante;
    }
}

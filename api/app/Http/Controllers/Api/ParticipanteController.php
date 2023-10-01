<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participantes; // Importa el modelo Participantes correctamente
use Illuminate\Http\Request;

class ParticipanteController extends Controller
{
    public function index()
    {
        $participantes = Participantes::all();
        return $participantes;
    }
    public function store(Request $request){
        $participante=new Participantes();
        $participante->Nombre=$request->Nombre;
        $participante->save();
    }
    public function show(string $id){
        $participante=Participantes::find($id);
        return $participante;
    }
    public function update(Request $request){
        $participante=Participantes::findOrfail($request->id);
        $participante->nombre=$request->nombre;
        $participante->save();
        return $participante;
    }
    public function destroy (string $id){
        $participante=Participantes::destroy($id);
        return $participante;
    }
}

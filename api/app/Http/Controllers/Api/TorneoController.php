<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participantes;

class TorneoController extends Controller
{
    private $participantes;
    private $rondas = [];

    public function __construct()
    {
        $this->participantes = Participantes::all()->pluck('Nombre')->toArray();
    }
    public function esPotenciaDeDos($numero)
    {
        return ($numero & ($numero - 1)) == 0;
    }

    public function calcularJornadas($numEquipos) {
     $nj=1;
     $n=2;
     while ($n<$numEquipos){
        $n*=2;
        $nj++;
     }
     return $nj;
    }
    public function simularEnfrentamiento($participante1, $participante2)
    {
        $ganador = rand(0, 1) == 0 ? $participante1 : $participante2;
        return $ganador;
    }
    
    public function generarBracketDobleEliminacion()
    {
        $numParticipantes = count($this->participantes); // Número de equipos en tu torneo
        $numJornadas = $this->calcularJornadas($numParticipantes);
        $numByes = $numJornadas - $numParticipantes;
        $numAjustes = $numParticipantes - (2 ** ($numJornadas - 1));
        $bracketPrincipal= [];
        $bracketPerdedores=[];
        $bracketGanadores=[];
        $enfrentamientosGanadores=[];
        $enfrentamientosPerdedores=[];
        if ($numParticipantes < 2) {
            die("Debe haber al menos 2 participantes.");
        }
        $ronda=1;
        for($i=0;$i<$numParticipantes;$i++){
            $bracketPrincipal [$i]=$this->participantes[$i];
        }
        while (count($bracketPrincipal) > 1) {
            $p1 = null;
            $p2 = null;
            $ganador = null;

            for ($i = 0; $i < $numParticipantes; $i += 2) {
                if (isset($bracketPrincipal[$i]) && isset($bracketPrincipal[$i + 1])) {
                    $p1 = $bracketPrincipal[$i];
                    $p2 = $bracketPrincipal[$i + 1];
                    $ganador = $this->simularEnfrentamiento($p1, $p2);
                    $bracketGanadores[]=$ganador;
                } else {
                    if (isset($bracketPrincipal[$i])) {
                        $p1 = $bracketPrincipal[$i];
                        $p2 = 'Bye';
                        $ganador = $p1;
                        $bracketGanadores[]=$ganador;
                    } elseif (isset($bracketPrincipal[$i + 1])) {
                        $p1 = 'Bye';
                        $p2 = $bracketPrincipal[$i + 1];
                        $ganador = $p2;
                        $bracketGanadores[]=$ganador;
                    }
                }
                $enfrentamientosGanadores[] = [
                    'Participante 1' => $p1,
                    'Participante 2' => $p2,
                    'GANADOR' => $ganador,
                ];
                
            }
            break;
        }
        

        return [
            'Enfrentamientos'=> $enfrentamientosGanadores,
        ];
    }
}




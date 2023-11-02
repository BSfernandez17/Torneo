<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Torneo;

use function PHPUnit\Framework\countOf;

class TorneoController extends Controller
{
    private $participantes;

    public function index(){
        $torneos=Torneo::all();
        return $torneos;
    }
    public function store(request $request){
        $torneo=new Torneo();
        $torneo->nombre=$request->nombre;
        $torneo->fecha=$request->fecha;
        $torneo->save();
    }
    public function show($torneo_id){
        $torneo=Torneo::find($torneo_id);
        return $torneo;
    }
    public function update(Request $request,$torneo_id){
        $torneo=Torneo::findOrFail($torneo_id);
        $torneo->nombre=$request->nombre;
        $torneo->fecha=$request->fecha;
        $torneo->save();
        return $torneo;
    }
    public function destroy($torneo_id){
        $torneo=Torneo::destroy($torneo_id);
        return $torneo;
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
    
    public function generarBracketDobleEliminacion($torneo_id)
    {
            // Obtener los nombres de los participantes del torneo específico
        $participantes = Participante::where('torneo_id', $torneo_id)->pluck('nombre')->toArray();

        // Verificar si se encontraron participantes
        if (empty($participantes)) {
            die("No se encontraron participantes para este torneo.");
        }

        $this->participantes = $participantes;
        $numParticipantes = count($this->participantes); // Número de equipos en tu torneo
        $numJornadas = $this->calcularJornadas($numParticipantes);
        $numByes =$numParticipantes- $numJornadas;
        $numAjustes = $numParticipantes - (2 ** ($numJornadas - 1));
        $bracketPrincipal= [];
        $bracketPerdedores=[];
        $ganadoresRonda=[];
        $enfrentamientosGanadores=[];
        $enfrentamientosPerdedores=[];
        $ganadoresRondaBracketP=[];
        $final=[];
        $finalista1=null;
        $finalista2=null;
        $campeon=null;

        if ($numParticipantes < 2) {
            die("Debe haber al menos 2 participantes.");
        }
        $ronda=1;
        for($i=0;$i<$numParticipantes;$i++){
            $bracketPrincipal [$i]=$this->participantes[$i];
        }
        while ($ronda <=$numJornadas) {
            $p1 = null;
            $p2 = null;
            $ganador = null;
            $numParticipantes = count($bracketPrincipal);
            $arrayAux=[];
            if($this->esPotenciaDeDos($numParticipantes)){

            for ($i = 0; $i < $numParticipantes; $i += 2) {
                if (isset($bracketPrincipal[$i]) && isset($bracketPrincipal[$i + 1])) {
                    $p1 = $bracketPrincipal[$i];
                    $p2 = $bracketPrincipal[$i + 1];
                    $ganador = $this->simularEnfrentamiento($p1, $p2);
                    
                } else {
                    if (isset($bracketPrincipal[$i])) {
                        $p1 = $bracketPrincipal[$i];
                        $p2 = 'Bye';
                        $ganador = $p1;
                    } elseif (isset($bracketPrincipal[$i + 1])) {
                        $p1 = 'Bye';
                        $p2 = $bracketPrincipal[$i + 1];
                        $ganador = $p2;
                    }
                    
                }
                $ganadoresRonda[]=$ganador;
                if ($p1 != $ganador && $p1 != 'Bye') {
                    $bracketPerdedores[] = $p1;
                } elseif ($p2 != $ganador && $p2 != 'Bye') {
                    $bracketPerdedores[] = $p2;
                }
                $enfrentamientosGanadores[] = [
                    'Participante 1' => $p1,
                    'Participante 2' => $p2,
                    'GANADOR' => $ganador,
                    'Ronda' => $ronda
                ];

            }
            array_splice($bracketPrincipal, 0);
            for($j=0;$j<count($ganadoresRonda);$j++){
                $bracketPrincipal [$j]=$ganadoresRonda[$j];
            }
            array_splice($ganadoresRonda, 0);
        }
        else{
            for($i=0;$i<$numByes;$i++){
                $p1=$bracketPrincipal[$i];
                $p2='bye';
                $ganador=$p1;
                $enfrentamientosGanadores[] = [
                    'Participante 1' => $p1,
                    'Participante 2' => $p2,
                    'GANADOR' => $ganador,
                    'Ronda' => $ronda
                ];
                $ganadoresRonda[]=$ganador;
            }
            
            for($i=$numByes;$i<$numParticipantes;$i+=2){
                $p1=$bracketPrincipal[$i];
                $p2=$bracketPrincipal[$i+1];
                $ganador=$this->simularEnfrentamiento($p1,$p2);
                $enfrentamientosGanadores[] = [
                    'Participante 1' => $p1,
                    'Participante 2' => $p2,
                    'GANADOR' => $ganador,
                    'Ronda' => $ronda
                ];
                $ganadoresRonda[]=$ganador;
                if ($p1 != $ganador && $p1 != 'Bye') {
                    $bracketPerdedores[] = $p1;
                } elseif ($p2 != $ganador && $p2 != 'Bye') {
                    $bracketPerdedores[] = $p2;
                }
            }
            array_splice($bracketPrincipal, 0);
            for($j=0;$j<count($ganadoresRonda);$j++){
                $bracketPrincipal [$j]=$ganadoresRonda[$j];
            }
            array_splice($ganadoresRonda, 0);

            
        }
        //Ahora vamos con la parte de los perdedores!!

        if($ronda>=2){
            for($i=0;$i<count($bracketPerdedores);$i+=2){
                if (isset($bracketPerdedores[$i]) && isset($bracketPerdedores[$i + 1])) {
                    $p1 = $bracketPerdedores[$i];
                    $p2 = $bracketPerdedores[$i + 1];
                    $ganador = $this->simularEnfrentamiento($p1, $p2);
                    $ganadoresRondaBracketP[]=$ganador;
                } else {
                    if (isset($bracketPerdedores[$i])) {
                        $p1 = $bracketPerdedores[$i];
                        $p2 = 'Bye';
                        $ganador = $p1;
                    } elseif (isset($bracketPerdedores[$i + 1])) {
                        $p1 = 'Bye';
                        $p2 = $bracketPerdedores[$i + 1];
                        $ganador = $p2;
                    }
                    $ganadoresRondaBracketP[]=$ganador;
                }
                
                $enfrentamientosPerdedores[] = [
                    'Participante 1' => $p1,
                    'Participante 2' => $p2,
                    'GANADOR' => $ganador,
                    'Ronda' => $ronda
                ];
            }
            array_splice($bracketPerdedores, 0);
            for($j=0;$j<count($ganadoresRondaBracketP);$j++){
                $bracketPerdedores [$j]=$ganadoresRondaBracketP[$j];
            }
            array_splice($ganadoresRondaBracketP, 0);
                
            }
            $ronda++;
    }

        
    $p1 = null;
    $p2 = null;
    $ganadorPerdedores = null;

    $p1 = $bracketPerdedores[0];
    $p2 = $bracketPerdedores[1];
    $ganadorPerdedores = $this->simularEnfrentamiento($p1, $p2);
    $enfrentamientosPerdedores[] = [
        'Participante 1' => $p1,
        'Participante 2' => $p2,
        'GANADOR' => $ganadorPerdedores,
        'Ronda' => $ronda
    ];

    // Guarda el ganador del bracket de perdedores
    $ganadorBracketPerdedores = $ganadorPerdedores;

    $finalista1 = $bracketPrincipal[0];
    $finalista2 = $ganadorBracketPerdedores; // Utiliza el ganador del bracket de perdedores
    $campeon = $this->simularEnfrentamiento($finalista1, $finalista2);
    $match=1;
    $final[] = [
        'Finalista 1' => $finalista1,
        'Finalista 2' => $finalista2,
        'CAMPEON!!' => $campeon,
        'Cantidad de juegos' =>$match
    ];

    if($campeon==$finalista2){
        $campeon = $this->simularEnfrentamiento($finalista1, $finalista2);
    $match=1;
    $final[] = [
        'Finalista 1' => $finalista1,
        'Finalista 2' => $finalista2,
        'CAMPEON!!' => $campeon,
        'Cantidad de juegos' =>$match+1
    ];
    }
       return [
            'Enfrentamientos Ganadores'=> $enfrentamientosGanadores,
            'Enfrentamientos Perdedores'=> $enfrentamientosPerdedores,
            'FINAL!!'=>$final
        ];
    }
}
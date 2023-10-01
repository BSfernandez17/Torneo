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

    public function simularEnfrentamiento($participante1, $participante2)
    {
        $ganador = rand(0, 1) == 0 ? $participante1 : $participante2;
        return $ganador;
    }

    public function generarBracketDobleEliminacion()
    {
        // Asegurémonos de que el número de participantes sea al menos 2
        $totalParticipantes = count($this->participantes);
        if ($totalParticipantes < 2) {
            die("Debe haber al menos 2 participantes.");
        }

        $ronda = 1;

        // Inicializa las matrices para el bracket principal (ganadores) y el bracket de los perdedores
        $bracketGanadores = [];
        $bracketPerdedores = [];

        for ($i = 0; $i < $totalParticipantes; $i++) {
            $bracketGanadores[$ronda][$i] = $this->participantes[$i];
        }

        while (count($bracketGanadores[$ronda]) > 1) {
            $ronda++;
            $enfrentamientosGanadoresRonda = [];
            $enfrentamientosPerdedoresRonda = [];

            $totalParticipantes = count($bracketGanadores[$ronda - 1]);
            for ($i = 0; $i < $totalParticipantes; $i += 2) {
                $jugador1 = $bracketGanadores[$ronda - 1][$i];
                $jugador2 = $bracketGanadores[$ronda - 1][$i + 1];
                $ganador = $this->simularEnfrentamiento($jugador1, $jugador2);

                // Almacenar el enfrentamiento de ganadores
                $enfrentamientosGanadoresRonda[] = [
                    'Jugador1' => $jugador1,
                    'Jugador2' => $jugador2,
                    'Ganador' => $ganador,
                    'NumeroRonda' => $ronda,
                ];

                // Colocar al ganador en la próxima ronda de ganadores
                $bracketGanadores[$ronda][] = $ganador;

                // Si un jugador perdió, muévelo al bracket de perdedores
                $perdedor = ($ganador === $jugador1) ? $jugador2 : $jugador1;
                $bracketPerdedores[$ronda][] = $perdedor;
            }

            // Almacenar los enfrentamientos de ganadores en la matriz general de ganadores
            $enfrentamientosGanadores[] = $enfrentamientosGanadoresRonda;
            if (isset($bracketPerdedores[$ronda - 1]) && count($bracketPerdedores[$ronda - 1]) > 0) {


                            // Generar los enfrentamientos de perdedores
            if (count($bracketPerdedores[$ronda - 1]) > 0) {
                // Si hay participantes en el bracket de perdedores, enfrentarlos
                $totalPerdedores = count($bracketPerdedores[$ronda - 1]);
                for ($i = 0; $i < $totalPerdedores; $i += 2) {
                    $jugador1 = $bracketPerdedores[$ronda - 1][$i];
                    $jugador2 = $bracketPerdedores[$ronda - 1][$i + 1];
                    $ganador = $this->simularEnfrentamiento($jugador1, $jugador2);

                    // Almacenar el enfrentamiento de perdedores
                    $enfrentamientosPerdedoresRonda[] = [
                        'Jugador1' => $jugador1,
                        'Jugador2' => $jugador2,
                        'Ganador' => $ganador,
                        'NumeroRonda' => $ronda,
                    ];

                    // Colocar al ganador en la próxima ronda de perdedores
                    $bracketPerdedores[$ronda][] = $ganador;
                }
            } else {
                // Si no hay participantes en el bracket de perdedores, avanzar con "descansos" en esa ronda
                $totalPerdedores = count($bracketGanadores[$ronda - 1]);
                for ($i = 0; $i < $totalPerdedores; $i += 2) {
                    $enfrentamientosPerdedoresRonda[] = [
                        'Jugador1' => 'Descanso',
                        'Jugador2' => 'Descanso',
                        'Ganador' => 'Descanso',
                        'NumeroRonda' => $ronda,
                    ];
                }
            }
            } else {
              
            }
            


            // Almacenar los enfrentamientos de perdedores en la matriz general de perdedores
            $enfrentamientosPerdedores[] = $enfrentamientosPerdedoresRonda;

        }

        // Almacena los enfrentamientos de todas las rondas de ganadores y perdedores
        $this->rondas['Ganadores'] = $enfrentamientosGanadores;
        $this->rondas['Perdedores'] = $enfrentamientosPerdedores;

        $rondaFinal=[];
        // El ganador final del torneo se encuentra en la última ronda de ganadores
        $finalista1 = $bracketGanadores[$ronda][0];
        $finalista2=$bracketPerdedores[$ronda][0];
        $rondaFinal=[$finalista1,$finalista2];


        $final= $this->simularEnfrentamiento($finalista1 ,$finalista2);
        $ganadorFinal=null;
        if($final==$finalista2){
            $ganadorFinal=$this->simularEnfrentamiento($finalista1,$finalista2);
        }else{
            $ganadorFinal=$finalista1;
        }

        return [
            'Final' => $rondaFinal,
            'Campeon' => $ganadorFinal,
            'Enfrentamientos' => $this->rondas,
            
        ];
    }
}

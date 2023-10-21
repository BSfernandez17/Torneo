<?php
use App\Http\Controllers\Api\ParticipanteController;
use App\Http\Controllers\Api\TorneoController;
use App\Models\Participantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ParticipanteController::class)->group(function (){
Route::get('/participantes','index');
Route::post('/participante','store');
Route::get('/participante/{id}','show');
Route::put('/participante/{id}','update');
Route::delete('/participante/{id}','destroy');
});

Route::controller(TorneoController::class)->group(function(){
    Route::post('/torneo/simular-torneo','generarBracketDobleEliminacion');
    Route::post('/torneo/mostrarBracket','mostrarBracket');
});
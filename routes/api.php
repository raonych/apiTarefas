<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\TarefasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//requerem autenticação no firebase para serem utilizadas
Route::middleware(['firebase.auth'])->group(function () {
    Route::get('/', [TarefasController::class,'index']);
    Route::post('/tarefas', [TarefasController::class,'store']);
    Route::get('/tarefas/{id}', [TarefasController::class,'show']);
    Route::update('/tarefas/{id}', [TarefasController::class,'update']);
    Route::get('/tarefas/{id}', [TarefasController::class,'destroy']);
});
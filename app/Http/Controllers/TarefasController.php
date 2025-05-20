<?php

namespace App\Http\Controllers;

use App\Models\Tarefas;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TarefasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)// id do usuario
    {
        $tarefas = Tarefas::Quer()
        ->where('userId', '=', $id)
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   

        $tarefa = $request->all();
        $validatedData = Validator::make($tarefa, [
            'nome' => 'required',
            'descricao' => 'required',
            'dataLimite' => 'required',
            'userId' => 'required'
        ]);

        if($validatedData->fails()) {
            return response()->json([
                'mensagem' => 'Registros Faltantes',
                'erros'=> $validatedData->erros()
            ], Response::HTTP_NO_CONTENT);
        } 

        $tarefaCriada = Tarefas::create($tarefa);


        if($tarefaCriada){
            return response()->json([
                'mensagem' => 'Tarefa criada com sucesso',
                'tarefa' => $tarefaCriada
            ], Response::HTTP_CREATED);
        }else{
            return response()->json([
                'mensagem' => 'Erro ao criar tarefa'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefas $tarefas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefas $tarefas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefas $tarefas)
    {
        //
    }
}

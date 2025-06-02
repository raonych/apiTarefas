<?php

namespace App\Http\Controllers;

use App\Models\Tarefas;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TarefasController extends Controller
{

    public function index(Request $request)
    {
        try{
            $userId = $request->header('X-User-Id');

            $tarefas = Tarefas::where('userId', $userId)->get();

            if(count($tarefas) == 0){
                return response()->json([
                    'mensagem' => 'o usuário não possui nenhuma tarefa',
                    'tarefas' => $tarefas
                ], 200);
            }

            return response()->json([
            'mensagem' => 'retornando tarefas do usuário',
            'tarefas' => $tarefas
            ],200);

        }catch (\Exception $error) {
        return response()->json([
            'mensagem' => $error->getMessage()
        ], 500);
    } 
        
    }


    public function store(Request $request)
    {   
        $tarefa = $request->all();
        $tarefa['userId'] = $request->header('X-User-Id');

        $validatedData = Validator::make($tarefa, [
            'titulo' => 'required',
            'descricao' => 'required',
            'dataLimite' => 'required',
            'userId' => 'required'
        ]);
        
        if ($validatedData->fails()) {
            return response()->json([
                'mensagem' => 'Registros faltantes',
                'erros' => $validatedData->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $tarefaCriada = Tarefas::create($tarefa);

        if ($tarefaCriada) {
            return response()->json([
                'mensagem' => 'Tarefa criada com sucesso',
                'tarefa' => $tarefaCriada
            ], Response::HTTP_CREATED); 
        } else {
            return response()->json([
                'mensagem' => 'Erro ao criar tarefa'
            ], Response::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }

    public function show(Request $request, int $id)
    {
        try{
            $tarefa = Tarefas::find($id);
            if(!$tarefa){
                return response()->json([
                    'mensagem' => 'Tarefa não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'mensagem' => 'retornando tarefa',
                'tarefa' => $tarefa
            ], 200);
            
        }catch(Exception $error){
            return response()->json([
                'mensagem' => $error->getMessage()
            ],500);
        }
        
    }

    public function update(Request $request, int $id)
    {
         try{

            $tarefa =  $request->all();
            $tarefa['userId'] = $request->header('X-User-Id');
            
            $validatedData = Validator::make($tarefa, [
                'titulo' => 'required',
                'descricao' => 'required',
                'dataLimite' => 'required',
                'userId' => 'required'
            ]);

            if ($validatedData->fails()) {
            return response()->json([
                'mensagem' => 'Registros faltantes',
                'erros' => $validatedData->errors()
            ], Response::HTTP_NOT_FOUND);
            }

            $tarefaAtt = Tarefas::find($id);

            if(!$tarefaAtt){
                return response()->json([
                    'mensagem' => 'Tarefa não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }
        

            $tarefaAtt->update($tarefa);
            

            return response()->json([
                'mensagem' => 'retornando tarefa',
                'tarefa' => $tarefa
            ], 200);
            
        }catch(Exception $error){
            return response()->json([
                'mensagem' => $error->getMessage()
            ],500);
        }
    }

    public function destroy(int $id)
    {
        try{

            $tarefa = Tarefas::find($id);
            
            if(!$tarefa){
                return response()->json([
                    'mensagem' => 'Tarefa não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }
        

            $tarefa->destroy($tarefa);
            

            return response()->json([
                'mensagem' => 'tarefa deletada com sucesso'
            ], 200);
            
        }catch(Exception $error){
            return response()->json([
                'mensagem' => $error->getMessage()
            ],500);
        }
    }

    public function toggle(int $id)
    {
        try{

            $tarefa = Tarefas::find($id);
            
            if(!$tarefa){
                return response()->json([
                    'mensagem' => 'Tarefa não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }
        
            $tarefa->concluido = !$tarefa->concluido;
            
            $tarefa->save();

            return response()->json([
                'mensagem' => 'tarefa atualizada com sucesso'
            ], 200);
            
        }catch(Exception $error){
            return response()->json([
                'mensagem' => $error->getMessage()
            ],500);
        }
    }
}

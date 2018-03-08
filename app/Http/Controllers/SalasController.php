<?php

namespace App\Http\Controllers;

use App\Salas;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSalasRequest;
use App\Http\Requests\UpdateSalasRequest;

/**
 * @resource Salas
 *
 * Salas possuem atributos nome, capacidade,
 * se possuem ou não ar condicionado.
 * Uma sala é atribuida à um relacionamento
 * turma/disciplina/horario quando é realizado 
 * o ensalamento
 */
class SalasController extends Controller
{

    /**
     * Obter todas as salas
     * 
     * Todas as salas cadastradas serão retornadas
     */
    public function index()
    {
        $salas = Salas::all();
        return response()->json($salas);
    }

    /**
     * Criar uma sala
     * 
     * Será criado uma sala e os dados do objeto
     * será retornado. Em caso de erro será retornado um arquivo
     * json com cabeçalho 404 e um atributo message com a mensagem
     * de erro.
     */
    public function store(StoreSalasRequest $request)
    {
        $sala = new Salas();
        $sala->fill($request->all());

        try {
            $sala->save();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($sala, 201);
    }

    /**
     * Obter uma sala
     * 
     * Em caso de falha ao encontrar a sala é retornado um 
     * cabeçalho 404 com uma mensagem de erro.
     * 
     * @param int $id Identificador unico da sala
     * @response {
     *  data: {
     *    "id": 1,
     *    "nome": "A101",
     *    "capacidade": 50,
     *    "arCondicionado": 1,
     *    "created_at": "2018-02-01 21:13:53",
     *    "updated_at": "2018-02-01 21:13:53"
     *   }
     * }
     */
    public function show($id)
    {
        $sala = Salas::find($id);

        if(!$sala) {
            return response()->json([
                'message' => 'Sala não encontrada'
            ], 404);
        }

        return response()->json($sala);
    }

    /**
     * Atualizar uma sala
     * 
     * No atributo {sala} deve ser passado o id da turma.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar a turma é retornado mensagem com cabeçalho 404
     * 
     */
    public function update(UpdateSalasRequest $request, $id)
    {
        $sala = Salas::find($id);
        
        if ($sala) {
            $sala->fill($request->all());    

            try {
                $sala->save();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json($sala);
        }

        return response()->json([
            'message' => 'Sala não encontrada'
        ], 404);
    }

    /**
     * Remover uma sala
     * 
     * É retornado cabeçalho 202 com mensagem de sucesso em caso de
     * sucesso e cabeçalho 404 caso não encontre a turma para remoção.
     */
    public function destroy($id)
    {
        $sala = Salas::find($id);

        if(!$sala) {
            return response()->json([
                'message' => 'Sala não encontrada'
            ], 404);
        }

        try {
            $sala->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json([
            'message' => 'Sala removida com sucesso'
        ], 202);
    }

    /**
     * @hideFromAPIDocumentation
     */
    public function create()
    {

    }

    /**
     * @hideFromAPIDocumentation
     */
    public function edit()
    {

    }
}

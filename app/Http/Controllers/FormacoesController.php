<?php

namespace App\Http\Controllers;

use App\Formacoes;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFormacoesRequest;

/**
 * @resource Formacoes
 * 
 * Formações possui o atributo nome
 */
class FormacoesController extends Controller
{

    /**
     * Obter todas as formações
     *
     * Todas as formações cadastradas serão retornadas
     */
    public function index()
    {
        $formacoes = Formacoes::all();
        return response()->json($formacoes);
    }

    /**
     * Criar uma formação
     *
     * Será criado uma formação e os dados do objeto será retornada.
     * Em caso de erro será retornado um arquivo json com o cabeçalho
     * 404 e um atributo message com a mensagem de erro.
     */
    public function store(StoreFormacoesRequest $request)
    {
        $formacao = new Formacoes();
        $formacao->fill($request->all());

        try {
            $formacao->save();
        }
        catch(\Illuminate\Datavase\QueryException $e)
        {
            return response()->json([
                'message' =>  is_array($e->erroInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($formacao, 201);
    }

    /**
     * Obter uma formação
     * 
     * Em caso de falha ao encontrar a formação é retornado um 
     * cabeçalho 404 com uma mensagem de erro.
     * 
     * @param int $id Identificador unico da disciplina
     * @response {
     *  data: {
     *      "id": 1,
     *      "nome": "Matemática",
     *      "created_at": "2018-01-31 22:10:41",
     *      "updated_at": "2018-01-31 22:10:41"
     *     }
     *  }
     */
    public function show($id)
    {
        $formacao = Formacoes::find($id);

        if(!$formacao) {
            return response()->json([
                'message' => 'Formação não encontrada'
            ], 404);
        }

        return response()->json($formacao);
    }

    /**
     * Atualizar uma formação
     * 
     * No atributo {formacao} deve ser passado o id da turma.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar a disciplina é retornado mensagem com cabeçalho 404
     * 
     */
    public function update(StoreFormacoesRequest $request, $id)
    {
        $formacao = Formacoes::find($id);
        
        if ($formacao) {
            $formacao->fill($request->all());

            try {
                $formacao->save();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json($formacao);
        }

        return response()->json([
            'message' => 'Formação não encontrada'
        ], 404);
    }

    /**
     * Remover uma formação
     * 
     * É retornado cabeçalho 202 com mensagem de sucesso em caso de
     * sucesso e cabeçalho 404 caso não encontre a turma para remoção.
     */
    public function destroy($id)
    {
        $formacao = Formacoes::find($id);

        if(!$formacao) {
            return response()->json([
                'message' => 'Formações não encontrada'
            ], 404);
        }

        try {
            $formacao->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json([
            'message' => 'Formação removida com sucesso'
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

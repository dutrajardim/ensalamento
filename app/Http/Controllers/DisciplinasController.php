<?php

namespace App\Http\Controllers;

use App\Disciplinas;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDisciplinasRequest;

/**
 * @resource Disciplinas
 *
 * Disciplinas possui o atributo nome
 * e é relacionado a turma antes de ser atribuida
 * à um horario
 */
class DisciplinasController extends Controller
{

    /**
     * Obter todas as disciplinas
     * 
     * Todas as disciplinas cadastradas serão retornadas
     */
    public function index()
    {
        $disciplinas = Disciplinas::all();
        return response()->json($disciplinas);
    }

    /**
     * Criar uma disciplina
     * 
     * Será criado uma disciplina e os dados do objeto
     * será retornado. Em caso de erro será retornado um arquivo
     * json com cabeçalho 404 e um atributo message com a mensagem
     * de erro.
     */
    public function store(StoreDisciplinasRequest $request)
    {
        $disciplina = new Disciplinas();
        $disciplina->fill($request->all());

        try {
            $disciplina->save();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($disciplina, 201);
    }

    /**
     * Obter uma disciplina
     * 
     * Em caso de falha ao encontrar a disciplina é retornado um 
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
        $disciplina = Disciplinas::find($id);

        if(!$disciplina) {
            return response()->json([
                'message' => 'Disciplina não encontrada'
            ], 404);
        }

        return response()->json($disciplina);
    }

    /**
     * Atualizar uma disciplina
     * 
     * No atributo {disciplina} deve ser passado o id da turma.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar a disciplina é retornado mensagem com cabeçalho 404
     * 
     */
    public function update(StoreDisciplinasRequest $request, $id)
    {
        $disciplina = Disciplinas::find($id);
        
        if ($disciplina) {
            $disciplina->fill($request->all());

            try {
                $disciplina->save();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json($disciplina);
        }

        return response()->json([
            'message' => 'Disciplina não encontrada'
        ], 404);
    }

    /**
     * Remover uma disciplina
     * 
     * É retornado cabeçalho 202 com mensagem de sucesso em caso de
     * sucesso e cabeçalho 404 caso não encontre a turma para remoção.
     */
    public function destroy($id)
    {
        $disciplina = Disciplinas::find($id);

        if(!$disciplina) {
            return response()->json([
                'message' => 'Disciplina não encontrada'
            ], 404);
        }

        try {
            $disciplina->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json([
            'message' => 'Disciplina removida com sucesso'
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

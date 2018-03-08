<?php

namespace App\Http\Controllers;

use App\Turmas;
use App\Ensalamentos;
use App\Horarios;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTurmasRequest;
use App\Http\Requests\UpdateTurmasRequest;
use App\Http\Requests\StoreDisciplinasTurmasRequest;
use App\Http\Requests\UpdateDisciplinasTurmasRequest;
use App\Http\Requests\TurmasSyncDisciplinasRequest;

/**
 * @resource Turmas
 *
 * Turmas possuem os atributos abreviação,
 * descrição e datas de atualização e criação.
 * Se relacionam com disciplinas.
 */
class TurmasController extends Controller
{

    /**
     * Obter todas as turmas
     * 
     * Todas as turmas serão retornadas
     */
    public function index()
    {
        $turmas = Turmas::all();
        return response()->json($turmas);
    }

    /**
     * Criar uma turma
     * 
     * Será criado e retornado o objeto inserido. Em caso de erro será retornado um arquivo
     * json com cabeçalho 404 e um atributo message com a mensagem
     * de erro.
     */
    public function store(StoreTurmasRequest $request)
    {
        $turma = new Turmas();
        $turma->fill($request->all());

        try {
            $turma->save();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($turma, 201);
    }

    /**
     * Obter uma turma
     * 
     * Em caso de falha ao encontrar a turma é retornado um 
     * cabeçalho 404 com uma mensagem de erro.
     * 
     * @param int $id Identificador unico da turma
     * @response {
     *  data: {
     *    "id": 1,
     *    "abreviacao": "SIN1AN-BAR",
     *    "descricao": "Sistemas de Informação Noite Barreiro",
     *    "created_at": "2018-01-31 22:03:02",
     *    "updated_at": "2018-01-31 22:03:02"
     *  }
     * }
     */
    public function show($id)
    {
        $turma = Turmas::find($id);
        if ($turma) return response()->json($turma);
        return response()->toJson([
            'message' => 'Turma não encontrada',
        ], 404);
    }

     /**
     * Atualizar uma turma
     * 
     * No atributo {turma} deve ser passado o id da turma.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar a turma é retornado mensagem com cabeçalho 404
     * 
     */
    public function update(UpdateTurmasRequest $request, $id)
    {
        $turma = Turmas::find($id);
        
        if ($turma) {
            $turma->fill($request->all());
            
            try {
                $turma->save();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json($turma);
        }

        return response()->json([
            'message' => 'Turma não encontrada'
        ], 404);
    }

    /**
     * Remover uma turma
     * 
     * É retornado cabeçalho 202 com mensagem de sucesso em caso de
     * sucesso e cabeçalho 404 caso não encontre a turma para remoção.
     */
    public function destroy($id)
    {
        $turma = Turmas::find($id);

        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }

        try {
            $turma->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json([
            'message' => 'Turma removida com sucesso'
        ], 202);
    }

    /**
     * Cadastrar uma disciplina
     * 
     * Cadastra uma disciplina para uma turma. Retorna 
     * mensagem de erro caso falhe.
     *
     * @param StoreDisciplinasTurmasRequest $request
     * @param integer $id
     * @return void
     */
    public function storeDisciplina(StoreDisciplinasTurmasRequest $request, $id)
    {
        $turma = Turmas::find($id);

        if ($turma) {
            try {
                $data = $request->all();
                $disciplinasId = $data['disciplinas_id'];
                unset($data['disciplinas_id']);

                $turma->disciplinas()->attach($disciplinasId, $data);
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json([
                'message' => 'Disciplina cadastrada com sucesso'
            ], 202);
        }

        return response()->json([
            'message' => 'Turma não encontrada'
        ], 404);
    }

    /**
     * Atualizar disciplina cadastrada
     * 
     * Atualiza informações de disciplina cadastrada
     * para uma turma
     *
     * @param UpdateDisciplinasTurmasRequest $request
     * @param integer $id
     * @param integer $disciplinaId
     * @return void
     */
    public function updateDisciplina(UpdateDisciplinasTurmasRequest $request, $id, $disciplinaId)
    {
        $turma = Turmas::find($id);

        if ($turma) {
            try {
                $turma->disciplinas()->updateExistingPivot($disciplinaId, $request->all());
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json([
                'message' => 'Cadastro de disciplina atualizado com sucesso'
            ], 202);
        }

        return response()->json([
            'message' => 'Turma não encontrada'
        ], 404);
    }
   
    /**
     * Remover cadastro de disciplina
     * 
     * Remove o cadastro de uma disciplina para uma turma
     *
     * @param [type] $id
     * @param [type] $disciplinaId
     * @return void
     */
    public function destroyDisciplina($id, $disciplinaId)
    {
        $turma = Turmas::find($id);

        if ($turma) {
            try {
                $turma->disciplinas()->detach($disciplinaId);
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json([
                'message' => 'Remoção de cadastro de disciplina realizado com sucesso'
            ], 202);
        }

        return response()->json([
            'message' => 'Turma não encontrada'
        ], 404);
    }

    /**
     * Obter disciplinas dada uma turma
     * 
     * É necessário passar o id/codigo da turma para qual deseja
     * que seja retornado o array com as disciplinas.
     * Caso a turma não seja encontrada é retornado uma messagem com 
     * cabeçalho 404
     */
    public function disciplinas($id)
    {
        $turma = Turmas::find($id);
        
        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }
        $disciplinas = $turma->disciplinas()->get();
        foreach ($disciplinas as $key => $disciplina) {
            $disciplinas[$key]->semestre = $disciplina->pivot->semestre;
            $disciplinas[$key]->ano = $disciplina->pivot->ano;
            $disciplinas[$key]->alunos_qtd = $disciplina->pivot->alunos_qtd;
            $disciplinas[$key]->relationship_id = $disciplina->pivot->id;
            unset($disciplinas[$key]->pivot);
            
        }
        return response()->json($disciplinas);
    }

    /**
     * Obter horarios dada uma turma
     * 
     * É necessário passar o id/codigo da turma para qual deseja
     * que seja retornado o array com as disciplinas e horários.
     * Caso a turma não seja encontrada é retornado uma messagem com 
     * cabeçalho 404
     */
    public function horarios($id)
    {
        $turma = Turmas::find($id);
        
        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }

        $horarios = $turma->horarios()->get();
        foreach ($horarios as $key => $horario) {
            $horarios[$key]->disciplina = $horario->disciplina()->get()[0];
        }

        return response()->json($horarios);
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

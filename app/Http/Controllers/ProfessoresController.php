<?php

namespace App\Http\Controllers;

use App\Professores;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProfessoresRequest;
use App\Http\Requests\StoreProfessoresFormacoesRequest;
use App\Http\Requests\UpdateProfessoresFormacoesRequest;
use App\Http\Requests\StoreProfessoresDisciplinasRequest;


/**
 * @resource Professores
 * 
 * Professores possui o atributo nome
 */
class ProfessoresController extends Controller
{
    /**
     * Obter todas as formações
     *
     * Todas as formações cadastradas serão retornadas
     */
    public function index()
    {
        $professores = Professores::all();
        return response()->json($professores);
    }

    /**
     * Criar um professor
     *
     * Será criado um professor e os dados do objeto será retornada.
     * Em caso de erro será retornado um arquivo json com o cabeçalho
     * 404 e um atributo message com a mensagem de erro.
     */
    public function store(StoreProfessoresRequest $request)
    {
        $professor = new Professores();
        $professor->fill($request->all());

        try {
            $professor->save();
        }
        catch(\Illuminate\Datavase\QueryException $e)
        {
            return response()->json([
                'message' =>  is_array($e->erroInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($professor, 201);
    }

    /**
     * Obter um professor
     * 
     * Em caso de falha ao encontrar a formação é retornado um 
     * cabeçalho 404 com uma mensagem de erro.
     * 
     * @param int $id Identificador unico do professor
     * @response {
     *  data: {
     *      "id": 1,
     *      "nome": "Paulo Ricardo",
     *      "created_at": "2018-01-31 22:10:41",
     *      "updated_at": "2018-01-31 22:10:41"
     *     }
     *  }
     */
    public function show($id)
    {
        $professor = Professores::find($id);

        if(!$professor) {
            return response()->json([
                'message' => 'Professor não encontrada'
            ], 404);
        }

        return response()->json($professor);
    }

    /**
     * Atualizar um professor
     * 
     * No atributo {professor} deve ser passado o id do professor.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar o professor é retornado mensagem com cabeçalho 404
     * 
     */
    public function update(StoreProfessoresRequest $request, $id)
    {
        $professor = Professores::find($id);
        
        if ($professor) {
            $professor->fill($request->all());

            try {
                $professor->save();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json($professor);
        }

        return response()->json([
            'message' => 'Professor não encontrada'
        ], 404);
    }

    /**
     * Remover um professor
     * 
     * É retornado cabeçalho 202 com mensagem de sucesso em caso de
     * sucesso e cabeçalho 404 caso não encontre o prefessor para remoção.
     */
    public function destroy($id)
    {
        $professor = Professores::find($id);

        if(!$professor) {
            return response()->json([
                'message' => 'Professor não encontrada'
            ], 404);
        }

        try {
            $professor->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json([
            'message' => 'Professor removida com sucesso'
        ], 202);
    }

    /**
     * Obter formacoes dada uma turma
     * 
     * É necessário passar o id/codigo do professor para qual deseja
     * que seja retornado o array com as formaçoes.
     * Caso o professor não seja encontrada é retornado uma messagem com 
     * cabeçalho 404
     */
    public function formacoes($id)
    {
        $professor = Professores::find($id);
        
        if(!$professor) {
            return response()->json([
                'message' => 'Professor não encontrada'
            ], 404);
        }
        $formacoes = $professor->formacoes()->get();
        foreach ($formacoes as $key => $formacao) {
            $formacoes[$key]->ano = $formacao->pivot->ano;
            $formacoes[$key]->grau = $formacao->pivot->grau;
            $formacoes[$key]->relationship_id = $formacao->pivot->id;
            unset($formacoes[$key]->pivot);
            
        }
        return response()->json($formacoes);
    }

    /**
     * Cadastrar uma formação
     * 
     * Cadastra uma formação para um professor. Retorna 
     * mensagem de erro caso falhe.
     *
     * @param StoreProfessoresFormacoesRequest $request
     * @param integer $id
     * @return void
     */
    public function storeFormacao(StoreProfessoresFormacoesRequest $request, $id)
    {
        $professor = Professores::find($id);

        if ($professor) {
            try {
                $data = $request->all();
                $formacoesId = $data['formacoes_id'];
                unset($data['formacoes_id']);

                $professor->formacoes()->attach($formacoesId, $data);
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json([
                'message' => 'Formação cadastrada com sucesso'
            ], 202);
        }

        return response()->json([
            'message' => 'Professor não encontrada'
        ], 404);
    }

     /**
     * Atualizar formação cadastrada
     * 
     * Atualiza informações de formação cadastrada
     * para um professor
     *
     * @param UpdateProfessoresFormacoesRequest $request
     * @param integer $id
     * @param integer $formacaoId
     * @return void
     */
    public function updateFormacao(UpdateProfessoresFormacoesRequest $request, $id, $formacaoId)
    {
        $professor = Professores::find($id);

        if ($professor) {
            try {
                $professor->formacoes()->updateExistingPivot($formacaoId, $request->all());
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json([
                'message' => 'Cadastro de formação atualizado com sucesso'
            ], 202);
        }

        return response()->json([
            'message' => 'Professor não encontrada'
        ], 404);
    }

    /**
     * Remover cadastro de formacao
     * 
     * Remove o cadastro de uma formacao para um professor
     *
     * @param [type] $id
     * @param [type] $formacaoId
     * @return void
     */
    public function destroyFormacao($id, $formacaoId)
    {
        $professor = Professores::find($id);

        if ($professor) {
            try {
                $professor->formacoes()->detach($formacaoId);
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json([
                'message' => 'Remoção de cadastro de formação realizado com sucesso'
            ], 202);
        }

        return response()->json([
            'message' => 'Professor não encontrada'
        ], 404);
    }

    /**
     * Obter disciplinas dado um professor
     * 
     * É necessário passar o id/codigo do professor para qual deseja
     * que seja retornado o array com as disciplinas.
     * Caso o professor não seja encontrada é retornado uma messagem com 
     * cabeçalho 404
     */
    public function disciplinas($id)
    {
        $professor = Professores::find($id);
        
        if(!$professor) {
            return response()->json([
                'message' => 'Professor não encontrada'
            ], 404);
        }
        $disciplinas = $professor->disciplinas()->get();
        foreach ($disciplinas as $key => $disciplina) {
            $disciplinas[$key]->relationship_id = $disciplina->pivot->id;
            unset($disciplinas[$key]->pivot);
        }
        return response()->json($disciplinas);
    }

    /**
     * Cadastrar uma disciplina
     * 
     * Cadastra uma disciplina para um professor. Retorna 
     * mensagem de erro caso falhe.
     *
     * @param integer $id
     * @return void
     */
    public function storeDisciplina($id, $disciplinaId)
    {
        $professor = Professores::find($id);

        if ($professor) {
            try {
                $professor->disciplinas()->attach($disciplinaId);
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
            'message' => 'Professor não encontrada'
        ], 404);
    }

    /**
     * Remover cadastro de disciplina
     * 
     * Remove o cadastro de uma disciplina para um professor
     *
     * @param [type] $id
     * @param [type] $disciplinaId
     * @return void
     */
    public function destroyDisciplinas($id, $disciplinaId)
    {
        $professor = Professores::find($id);

        if ($professor) {
            try {
                $professor->disciplinas()->detach($disciplinaId);
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
            'message' => 'Professor não encontrada'
        ], 404);
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

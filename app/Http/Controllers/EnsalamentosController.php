<?php

namespace App\Http\Controllers;

use App\Ensalamentos;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEnsalamentosRequest;
use App\Http\Requests\UpdateEnsalamentosRequest;
use App\Http\Requests\ReplaceEnsalamentoRequest;
use App\Http\Requests\EnsalarRequest;
use App\Jobs\CallGurobi;
use App\Horarios;
use Illuminate\Support\Facades\DB;

/**
 * @resource Ensalamentos
 * 
 * O ensalamento representa um evento do gurobi
 * que relaciona um horario (com disciplina e turma) à 
 * uma sala.
 */
class EnsalamentosController extends Controller
{
    /**
     * Obter todos os ensalamentos
     * 
     * Todos os dados de ensalamentos é retornado
     */
    public function index()
    {
       $ensalamentos = Ensalamentos::all();
       return response()->json($ensalamentos) ;
    }

    /**
     * Criar um ensalamento
     * 
     * Será criado um ensalamento e os dados do objeto será
     * retornado. Em caso de erro será retornado um arquivo
     * json com cabeçalho 404 e um atributo message com a mensagem
     * de erro
     *
     * @param  \Illuminate\Http\StoreEnsalamentosRequest  $request
     */
    public function store(StoreEnsalamentosRequest $request)
    {
        $ensalamento = new Ensalamentos();
        $ensalamento->titulo = $request->input('titulo');
        $ensalamento->status = 'A';

        try {
            $ensalamento->save();
            CallGurobi::dispatch($ensalamento->id, $request->input('ano'), $request->input('semestre'));
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($ensalamento, 201);
    }

    /**
     * Obter uma ensalamento
     * 
     * Em caso de falha ao encontrar um ensalamento é retornado um 
     * cabeçalho 404 com uma mensagem de erro.
     * 
     * @param int $id Identificador unico do ensalamento
     * @response {
     *  data: {
     *    "id": 2,
     *    "titulo": "Primeiro ensalamento 2018/1",
     *    "status": "A",
     *    "created_at": "2018-02-24 20:45:35",
     *    "updated_at": "2018-02-24 20:45:35"
     *  }
     * }
     */
    public function show($id)
    {
        $ensalamento = Ensalamentos::find($id);

        if (!$ensalamento) {
            return response()->json([
                'message' => 'Ensalamento não encontrado'
            ], 404);
        }

        $horarios = $ensalamento->horarios()->get();

        foreach ($horarios as $key => $horario) {
            $disciplina = [
                'id' => $horario->disciplina_id,
                'nome' => $horario->disciplina_nome
            ];
            $turma = [
                'id' => $horario->turma_id,
                'abreviacao' => $horario->turma_abreviacao,
                'descricao' => $horario->turma_descricao,
            ];
            $sala = [
                'id' => $horario->sala_id,
                'nome' => $horario->sala_nome,
                'ar' => $horario->sala_ar,
                'capacidade' => $horario->sala_capacidade
            ];
            unset($horario->disciplina_id,$horario->disciplina_nome,
                $horario->turma_id,$horario->turma_abreviacao,$horario->turma_descricao,
                $horario->sala_id,$horario->sala_nome,$horario->sala_capacidade,$horario->sala_ar);
            
            $horario->disciplina = (object) $disciplina;
            $horario->turma = (object) $turma;
            $horario->sala = (object) $sala;

            $horarios[$key] = $horario;            
        }

        $ensalamento->ensalamentos = $horarios;
        return response()->json($ensalamento);
    }

    /**
     * Atualizar um ensalamento
     * 
     * No atributo {ensalamento} deve ser passado o id do ensalamento.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar a turma é retornado uma mensagem com cabeçalho 404.
     * O atributo satatus aceita os seguites valores [A - Aguardando, 
     * T - Em trabalho, E - Erro, P - Pronto]
     *
     * @param  \Illuminate\Http\UpdateEnsalamentosRequest  $request
     * @param  int  $id
     */
    public function update(UpdateEnsalamentosRequest $request, $id)
    {
        $ensalamento = Ensalamentos::find($id);

        if ($ensalamento) {
            $ensalamento->fill($request->all());

            try {
                $ensalamento->save();
            }
            catch(\Iliminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }

            return response()->json($ensalamento);
        }

        return response()->json([
            'message' => 'Ensalamento não encontrado'
        ], 404);
    }

    /**
     * Remover um ensalamento
     * 
     * É retornado cabeçalho 2020 com mensagem de sucesso em caso
     * de sucesso e cabeçalho 404 caso não encontre o ensalamento
     * para remoção.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $ensalamento = Ensalamentos::find($id);

        if (!$ensalamento) {
            return response()->json([
                'message' => 'Ensalamento não encontrado'
            ], 404);
        }

        try {
            $ensalamento->delete();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json([
            'message' => 'Ensalamento removido com sucesso'
        ], 202);
    }

    
    public function ensalar(EnsalarRequest $request, $id)
    {
        $ensalamento = Ensalamentos::find($id);
        if (!$ensalamento) {
            return response()->json([
                'message' => 'Ensalamento não encontrado'
            ], 202);
        }
        
        $ensalamentos = $request->input('ensalamentos');
        if (!$ensalamentos || !is_array($ensalamentos)) {
            return response()->json([
                'message' => 'Propriedade ensalamentos não encontrada'
            ], 400);
        }

        try {
            DB::beginTransaction();
            foreach ($ensalamentos as $ensalamento) {
                DB::insert('insert into horarios_salas (salas_id, ensalamentos_id, horarios_id) values (?,?,?)', [
                    $ensalamento['salas_id'], $id, $ensalamento['horarios_id']
                ]);    
            }
            DB::commit();
        }
        catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ],400);    
        }

        return response()->json([
            'message' => 'Ensalamentos criados com sucesso'
        ]);
    }

    public function replace(ReplaceEnsalamentoRequest $request, $id)
    {
        $ensalamento = Ensalamentos::find($id);
        $from = $request->input('from');
        $to = $request->input('to');
        
        // Retira o horario de sua origim
        $horarioFrom = Horarios::find($from['horarios_id']);
        $horarioFrom->salas()->wherePivot('ensalamentos_id', $id)->detach($from['salas_id']);
        
        // Verifica se destino possui ensalamento
        // caso possua aloca ele na origim
        $ensalamentoTo = $ensalamento->horarioSala($to)->first();
        if ($ensalamentoTo) {
            $horarioTo = Horarios::find($ensalamentoTo->horarios_id);
            $horarioTo->salas()->wherePivot('ensalamentos_id', $id)->detach($to['salas_id']);
            $horarioTo->salas()->attach($from['salas_id'], ['ensalamentos_id' => $id]);
        }
        
        // Aloca horario de origem no destino solicitado
        $horarioFrom->salas()->attach($to['salas_id'], ['ensalamentos_id' => $id]);

        return response()->json($horarioFrom);
    }

    /**
     * Show the form for creating a new resource.
     * @hideFromAPIDocumentation
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @hideFromAPIDocumentation
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
}

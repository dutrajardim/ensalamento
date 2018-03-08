<?php

namespace App\Http\Controllers;

use App\Horarios;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHorariosRequest;
use App\Http\Requests\UpdateHorariosRequest;

use App\Traits\QueryParamtersTrait;
/**
 * @resource Horarios
 *
 * Horarios representam a hora aula, uma instancia de horario
 * representa a atribuição de uma disciplina/turma à um horario
 * na semana.
 * Os atributos de um horário são id do relacionamento de turma
 * com disciplina, o dia da semana(1 ate 7 representando domingo
 * à segunda) e o horario no dia.
 */
class HorariosController extends Controller
{
    use QueryParamtersTrait;

    protected $alowedFilterFields = [
        'semestre',
        'ano'
    ];

    /**
     * Obter todas os horarios
     * 
     * Todos os horarios cadastradas serão retornadas.
     */
    public function index(Request $request)
    {
        if ($request->input('filters')) {
            $filters = explode($request->input('filters'),',',10);
            $wheres = $this->getFilters($request->input('filters'), $this->alowedFilterFields);
        }

        $queryHoraios = Horarios::allPopulated();
        if (isset($wheres) && count($wheres)) $queryHoraios = $queryHoraios->where($wheres);
        $horarios = $queryHoraios->get();

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
            unset($horario->disciplina_id,$horario->disciplina_nome,
                $horario->turma_id,$horario->turma_abreviacao,$horario->turma_descricao);
            
            $horario->disciplina = (object) $disciplina;
            $horario->turma = (object) $turma;
            $horarios[$key] = $horario;            
        }
        return response()->json($horarios);
    }


    /**
     * Criar um horario
     * 
     * Será atribuido uma disciplina/turma à um horário.
     * Em caso de erro será retornado um arquivo
     * json com cabeçalho 404 e um atributo message com a mensagem
     * de erro.
     */
    public function store(StoreHorariosRequest $request)
    {
        $horario = new Horarios();
        $horario->fill($request->all());

        try {
            $horario->save();
        }
        catch(\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
            ], 404);
        }

        return response()->json($horario, 201);
    }

    /**
     * Obter uma horario
     * 
     * No atributo {horario} deve ser passado o id do horario.
     * Em caso de falha ao encontrar a disciplina é retornado um 
     * cabeçalho 404 com uma mensagem de erro.
     * 
     * @param int $id Identificador unico do horario
     * @response {
     *  data: {
     *      "id": 1,
     *      "disciplinas_turmas_id": 1,
     *      "dia": "1",
     *      "horario": 1,
     *      "created_at": "2018-01-31 22:32:44",
     *      "updated_at": "2018-01-31 22:32:44"
     *    }
     *  }
     */
    public function show($id)
    {
        $horario = Horarios::find($id);

        if(!$horario) {
            return response()->json([
                'message' => 'Horario não encontrada'
            ], 404);
        }

        return response()->json($horario);
    }

    /**
     * Atualizar um horario
     * 
     * No atributo {horario} deve ser passado o id do horario.
     * Será retornado o objeto atualizado. Em caso de falha
     * ao encontrar o horario é retornado mensagem com cabeçalho
     * 404.
     */
    public function update(UpdateHorariosRequest $request, $id)
    {
        $horario = Horarios::find($id);
        
        if ($disciplina) {
            $horario->fill($request->all());

            try {
                $horario->save();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return response()->json([
                    'message' => is_array($e->errorInfo)?implode(" - ",$e->errorInfo):$e->errorInfo
                ], 404);
            }
            return response()->json($horario);
        }

        return response()->json([
            'message' => 'Horario não encontrada'
        ], 404);
    }

    /**
     * Remover um horario
     * 
     * É retornado cabeçalho 202 com mensagem de sucesso em caso de
     * sucesso e cabeçalho 404 caso não encontre a turma para remoção.
     */
    public function destroy($id)
    {
        $horario = Horarios::find($id);

        if(!$horario) {
            return response()->json([
                'message' => 'Horario não encontrada'
            ], 404);
        }

        try {
            $horario->delete();
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

<?php

namespace App\Http\Controllers;

use App\Turmas;
use App\Ensalamentos;
use Illuminate\Http\Request;

/**
 * @resource Turmas
 *
 * Turmas
 */
class TurmasController extends Controller
{
    public function index()
    {
        $turmas = Turmas::all();
        return response()->json($turmas);
    }

    public function store(Request $request)
    {
        $turma = new Turmas();
        $turma->fill($request->all());
        $turma->save();

        return response()->json($turma, 201);
    }

    public function disciplinas($id)
    {
        $turma = Turmas::find($id);
        
        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }
        $disciplinas = $turma->disciplinas()->get();
        foreach ($disciplinas as $key => $value) {
            $disciplinas[$key]->pivot->turmas_abreviacao = $turma->abreviacao;
            $disciplinas[$key]->pivot->turmas_descricao = $turma->descricao;
        }
        return response()->json($disciplinas);
    }

    public function horarios($id)
    {
        $turma = Turmas::find($id);
        
        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }
        return response()->json($turma->horarios()->whereNull('horarios_id')->get());
    }

    public function storeHorarios(Request $request, $id)
    {
        $turma = Turmas::find($id);

        Ensalamentos::where('turmas_id', $id)->whereNull('horarios_id')->delete();
        $turma->horarios()->createMany($request->all());

        return response()->json($turma->ensalamentos, 200);
    }

    public function horariosTurmas()
    {
        $horarios = Turmas::whereHas('horarios')->get()->load('horarios');
        return response()->json($horarios);
    }

    public function disciplinasTurmas()
    {
        $turmas = Turmas::whereHas('disciplinas')->get()->load('disciplinas');
        return response()->json($turmas);
    }

    public function storeDisciplina(Request $request, $id)
    {
        $turma = Turmas::find($id);

        $disciplinas = [];

        foreach ($request->all() as $rel) {
            $disciplinas[$rel['disciplina_id']] = ["alunos_qtd" => $rel['alunos_qtd']];
        }
        $turma->disciplinas()->sync($disciplinas);

        return response()->json($turma->disciplinas, 200);
    }

    public function create()
    {

    }

    public function show($id)
    {
        $turma = Turmas::find($id);

        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }

        return response()->json($turma);
    }

    public function update(Request $request, $id)
    {
        $turma = Turmas::find($id);
        
        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }

        $turma->fill($request->all());
        $turma->save();

        return response()->json($turma);
    }

    public function destroy($id)
    {
        $turma = Turmas::find($id);

        if(!$turma) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }

        $turma->delete();
    }

    public function edit()
    {

    }
}

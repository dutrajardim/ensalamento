<?php

namespace App\Http\Controllers;

use App\Turmas;
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
        
        return response()->json($turma->disciplinas()->get());
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
            $disciplinas[$rel['disciplina_id']] = ["horarios" => $rel['horarios']];
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

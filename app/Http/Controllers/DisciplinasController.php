<?php

namespace App\Http\Controllers;

use App\Disciplinas;
use Illuminate\Http\Request;

/**
 * @resource Disciplinas
 *
 * Disciplinas
 */
class DisciplinasController extends Controller
{
    public function index()
    {
        $disciplinas = Disciplinas::all();
        return response()->json($disciplinas);
    }

    public function store(Request $request)
    {
        $disciplina = new Disciplinas();
        $disciplina->fill($request->all());
        $disciplina->save();

        return response()->json($disciplina, 201);
    }

    public function create()
    {

    }

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

    public function update(Request $request, $id)
    {
        $disciplina = Disciplinas::find($id);
        
        if(!$disciplina) {
            return response()->json([
                'message' => 'Disciplina não encontrada'
            ], 404);
        }

        $disciplina->fill($request->all());
        $disciplina->save();

        return response()->json($disciplina);
    }

    public function destroy($id)
    {
        $disciplina = Disciplinas::find($id);

        if(!$disciplina) {
            return response()->json([
                'message' => 'Disciplina não encontrada'
            ], 404);
        }

        $disciplina->delete();
    }

    public function edit()
    {

    }
}

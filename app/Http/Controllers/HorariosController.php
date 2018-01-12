<?php

namespace App\Http\Controllers;

use App\Horarios;
use App\Ensalamentos;
use Illuminate\Http\Request;
use App\Jobs\CallGurobi;

class HorariosController extends Controller
{
    public function index()
    {
        $horarios = Horarios::all();
        return response()->json($horarios);
    }

    public function pendentes()
    {
        $horarios = Ensalamentos::whereNull('horarios_id')
            ->withQtd()
            ->select('ensalamentos.dia', 'ensalamentos.horario','ensalamentos.disciplinas_id','ensalamentos.turmas_id', 'ensalamentos.id', 'dt.alunos_qtd')
            ->get();

        $horarios->load('disciplina','turma');
        return response()->json($horarios);
    }

    // public function callgurobi() {
    //     CallGurobi::dispatch('1');
    // }
    
    public function store(Request $request)
    {
        $horario = new Horarios();
        $horario->fill($request->all());
        $horario->save();
        
        CallGurobi::dispatch($horario->id);

        return response()->json($horario, 201);
    }

    public function ensalamentos($id)
    {
        $horario = Horarios::find($id);
        
        if(!$horario) {
            return response()->json([
                'message' => 'Turma não encontrada'
            ], 404);
        }
        
        return response()->json($horario->ensalamentos()->get());
    }

    public function storeEnsalamento(Request $request, $id)
    {
        $horario = Horarios::find($id);

        Ensalamentos::where('horarios_id', $id)->delete();
        $horario->ensalamentos()->createMany($request->all());

        return response()->json($horario->ensalamentos, 200);
    }

    public function create()
    {

    }

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

    public function update(Request $request, $id)
    {
        $horario = Horarios::find($id);
        
        if(!$horario) {
            return response()->json([
                'message' => 'Horario não encontrada'
            ], 404);
        }

        $horario->fill($request->all());
        $horario->save();

        return response()->json($horario);
    }

    public function destroy($id)
    {
        $horario = Horarios::find($id);

        if(!$horario) {
            return response()->json([
                'message' => 'Horario não encontrada'
            ], 404);
        }

        $horario->delete();
    }

    public function edit()
    {

    }
}

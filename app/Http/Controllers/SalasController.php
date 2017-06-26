<?php

namespace App\Http\Controllers;

use App\Salas;
use Illuminate\Http\Request;

/**
 * @resource Salas
 *
 * Salas
 */
class SalasController extends Controller
{
    public function index()
    {
        $salas = Salas::all();
        return response()->json($salas);
    }

    public function store(Request $request)
    {
        $sala = new Salas();
        $sala->fill($request->all());
        $sala->save();

        return response()->json($sala, 201);
    }

    public function create()
    {

    }

    public function show($id)
    {
        $sala = Salas::find($id);

        if(!$sala) {
            return response()->json([
                'message' => 'Sala não encontrada'
            ], 404);
        }

        return response()->json($sala);
    }

    public function update(Request $request, $id)
    {
        $sala = Salas::find($id);
        
        if(!$sala) {
            return response()->json([
                'message' => 'Sala não encontrada'
            ], 404);
        }

        $sala->fill($request->all());
        $sala->save();

        return response()->json($sala);
    }

    public function destroy($id)
    {
        $sala = Salas::find($id);

        if(!$sala) {
            return response()->json([
                'message' => 'Sala não encontrada'
            ], 404);
        }

        $sala->delete();
    }

    public function edit()
    {

    }
}

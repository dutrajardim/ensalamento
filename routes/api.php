<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'v1'], function () {

    Route::resource('salas', 'SalasController');
    Route::resource('disciplinas', 'DisciplinasController');

    Route::resource('turmas', 'TurmasController');
    Route::post('/turmas/{id}/disciplinas', 'TurmasController@storeDisciplina');
    Route::get('/turmas/{id}/disciplinas', 'TurmasController@disciplinas');
    Route::get('/turmas/{id}/horarios', 'TurmasController@horarios');
    Route::post('/turmas/{id}/horarios', 'TurmasController@storeHorarios');
    Route::get('/disciplinasTurmas', 'TurmasController@disciplinasTurmas');
    Route::get('/horariosTurmas', 'TurmasController@horariosTurmas');

    Route::resource('horarios', 'HorariosController');
    Route::post('/horarios/{id}/ensalamentos', 'HorariosController@storeEnsalamento');
    Route::get('/horarios/{id}/ensalamentos', 'HorariosController@ensalamentos');
    Route::get('/ensalamentos/pendentes', 'HorariosController@pendentes');
    Route::get('/ensalamentos/callgurobi', 'HorariosController@callgurobi');
    Route::get('/ssh_teste', function() {
        SSH::run(array(
            'cd /home/rafael',
            'mkdir testessh'
        ));
    });

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

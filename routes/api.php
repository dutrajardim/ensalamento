<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'v1'], function () {

    Route::resource('salas', 'SalasController');
    Route::resource('disciplinas', 'DisciplinasController');
    Route::resource('turmas', 'TurmasController');
    Route::resource('horarios', 'HorariosController');
    Route::resource('ensalamentos', 'EnsalamentosController');
    
    Route::get('/turmas/{id}/disciplinas', 'TurmasController@disciplinas')->name('turmas.disciplinas');
    Route::post('/turmas/{id}/disciplinas', 'TurmasController@storeDisciplina')->name('turmas.storeDisciplina');
    Route::put('/turmas/{id}/disciplinas/{disciplinaId}', 'TurmasController@updateDisciplina')->name('turmas.updateDisciplinas');
    Route::delete('/turmas/{id}/disciplinas/{disciplinaId}', 'TurmasController@destroyDisciplina')->name('turmas.destroyDisciplinas');
    Route::get('/turmas/{id}/horarios', 'TurmasController@horarios')->name('turmas.horarios');
    
    Route::post('/ensalamentos/{id}/ensalar', 'EnsalamentosController@ensalar')->name('ensalamentos.ensalar');
    Route::put('/ensalamentos/{id}/replace', 'EnsalamentosController@replace')->name('ensalamentos.replace');

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

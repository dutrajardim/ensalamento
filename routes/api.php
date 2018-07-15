<?php

use Illuminate\Http\Request;


Route::group(['prefix' => 'v1'], function () {

    Route::resource('salas', 'SalasController');
    Route::resource('disciplinas', 'DisciplinasController');
    Route::resource('turmas', 'TurmasController');
    Route::resource('horarios', 'HorariosController');
    Route::resource('ensalamentos', 'EnsalamentosController');
    Route::resource('formacoes', 'FormacoesController');
    Route::resource('professores', 'ProfessoresController');
    
    Route::get('/turmas/{id}/disciplinas', 'TurmasController@disciplinas')->name('turmas.disciplinas');
    Route::post('/turmas/{id}/disciplinas', 'TurmasController@storeDisciplina')->name('turmas.storeDisciplina');
    Route::put('/turmas/{id}/disciplinas/{disciplinaId}', 'TurmasController@updateDisciplina')->name('turmas.updateDisciplinas');
    Route::delete('/turmas/{id}/disciplinas/{disciplinaId}', 'TurmasController@destroyDisciplina')->name('turmas.destroyDisciplinas');
    Route::get('/turmas/{id}/horarios', 'TurmasController@horarios')->name('turmas.horarios');
    
    Route::post('/ensalamentos/{id}/ensalar', 'EnsalamentosController@ensalar')->name('ensalamentos.ensalar');
    Route::put('/ensalamentos/{id}/replace', 'EnsalamentosController@replace')->name('ensalamentos.replace');

    Route::get('/professores/{id}/formacoes', 'ProfessoresController@formacoes')->name('professores.formacoes');
    Route::post('/professores/{id}/formacoes', 'ProfessoresController@storeFormacao')->name('professores.storeFormacao');
    Route::put('/professores/{id}/formacoes/{formacaoId}', 'ProfessoresController@updateFormacao')->name('professores.updateFormacoes');
    Route::delete('/professores/{id}/formacoes/{formacaoId}', 'ProfessoresController@destroyFormacao')->name('professores.destroyFormacoes');

    Route::get('/professores/{id}/disciplinas', 'ProfessoresController@disciplinas')->name('professores.disciplinas');
    Route::post('/professores/{id}/disciplinas/{disciplina_id}', 'ProfessoresController@storeDisciplina')->name('professores.storeDisciplina');
    Route::delete('/professores/{id}/disciplinas/{disciplinaId}', 'ProfessoresController@destroyDisciplinas')->name('professores.destroyDisciplinas');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

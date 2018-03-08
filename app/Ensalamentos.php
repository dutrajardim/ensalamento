<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ensalamentos extends Model
{
    protected $fillable = ['titulo','status'];

    public function horarios()
    {
        $namedAs = [
            /** turmas */ 'turmas.abreviacao as turma_abreviacao', 'turmas.descricao as turma_descricao',
            /** disciplinas */ 'disciplinas.nome as disciplina_nome',
            /** disciplinas_turmas */ 'semestre', 'ano', 'turmas_id as turma_id', 'disciplinas_id as disciplina_id', 'alunos_qtd',
            /** horarios */ 'dia', 'horario', 'disciplinas_turmas_id', 'horarios.id',
            /** salas */ 'salas.nome as sala_nome', 'capacidade as sala_capacidade', 'arCondicionado as sala_ar', 'salas.id as sala_id'
        ];

        return Horarios::leftJoin('disciplinas_turmas', 'horarios.disciplinas_turmas_id','disciplinas_turmas.id')
            ->leftJoin('turmas', 'turmas.id','disciplinas_turmas.turmas_id')
            ->leftJoin('disciplinas', 'disciplinas.id', 'disciplinas_turmas.disciplinas_id')
            ->leftJoin('horarios_salas', 'horarios.id','horarios_salas.horarios_id')
            ->leftJoin('salas', 'horarios_salas.salas_id', 'salas.id')
            ->where('horarios_salas.ensalamentos_id', $this->id)
            ->select($namedAs);
    }

    public function horarioSala($on)
    {
        return Horarios::leftJoin('horarios_salas', 'horarios.id','horarios_salas.horarios_id')
            ->where('horarios_salas.ensalamentos_id', $this->id)
            ->where('horarios_salas.salas_id', $on['salas_id'])
            ->where('horarios.dia', $on['dia'])
            ->where('horarios.horario', $on['horario']);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horarios extends Model
{
    protected $fillable = ['disciplinas_turmas_id', 'dia','horario'];

    public function salas() {
        return $this->belongsToMany('App\Salas')
            ->withPivot('ensalamentos_id')
            ->join('ensalamentos','horarios_salas.ensalamentos_id','ensalamentos.id')
            ->select('ensalamentos.id as ensalamentos_id');
    }

    public function disciplina() {
        return Disciplinas::leftJoin('disciplinas_turmas', 'disciplinas.id','disciplinas_turmas.disciplinas_id')
            ->where('disciplinas_turmas.id', $this->disciplinas_turmas_id)
            ->select('disciplinas.*');
    }

    public static function allPopulated() {
        $namedAs = [
            /** turmas */ 'turmas.abreviacao as turma_abreviacao', 'turmas.descricao as turma_descricao',
            /** disciplinas */ 'disciplinas.nome as disciplina_nome',
            /** disciplinas_turmas */ 'semestre', 'ano', 'turmas_id as turma_id', 'disciplinas_id as disciplina_id', 'alunos_qtd',
            /** horarios */ 'dia', 'horario', 'disciplinas_turmas_id', 'horarios.id'
        ];
        return Horarios::leftJoin('disciplinas_turmas', 'horarios.disciplinas_turmas_id','disciplinas_turmas.id')
            ->leftJoin('turmas', 'turmas.id','disciplinas_turmas.turmas_id')
            ->leftJoin('disciplinas', 'disciplinas.id', 'disciplinas_turmas.disciplinas_id')
            // ->leftJoin('horarios_salas','horarios.id','horarios_salas.horarios_id')
            // ->whereNull('ensalamentos_id')
            ->select($namedAs);
    }
}

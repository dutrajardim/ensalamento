<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turmas extends Model
{
    protected $fillable = ['descricao', 'abreviacao'];

    public function disciplinas()
    {
        return $this->belongsToMany('App\Disciplinas')
            ->withPivot('alunos_qtd','ano','semestre','id')
            ->select('disciplinas.*');
            // ->leftJoin('horarios', 'disciplinas_turmas.id', 'horarios.disciplinas_turmas_id')
    }

    public function horarios()
    {
        return Horarios::leftJoin('disciplinas_turmas','horarios.disciplinas_turmas_id','disciplinas_turmas.id')
            ->leftJoin('horarios_salas','horarios.id','horarios_salas.horarios_id')
            // ->whereNull('ensalamentos_id')
            ->where('disciplinas_turmas.turmas_id',$this->id)->select('horarios.*');
    }
}

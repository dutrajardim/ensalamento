<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ensalamentos extends Model
{
    protected $fillable = ['salas_id','turmas_id', 'disciplinas_id', 'horarios_id', 'dia', 'horario'];

    public function disciplina()
    {
        return $this->belongsTo('App\Disciplinas', 'disciplinas_id');
    }

    public function turma()
    {
        return $this->belongsTo('App\Turmas', 'turmas_id');
    }

    public function scopeWithQtd($query)
    {
        $query->leftjoin('disciplinas_turmas as dt', function ($join) {
            $join->on('dt.turmas_id', '=', 'ensalamentos.turmas_id')
                ->on('dt.disciplinas_id', '=', 'ensalamentos.disciplinas_id');
        });
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turmas extends Model
{
    protected $fillable = ['descricao', 'abreviacao'];

    public function disciplinas()
    {
        return $this->belongsToMany('App\Disciplinas')->withPivot('alunos_qtd')->withTimestamps();
    }

    public function horarios()
    {
        return $this->hasMany('App\Ensalamentos', 'turmas_id');
    }
}

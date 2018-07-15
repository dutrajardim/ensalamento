<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professores extends Model
{
    protected $fillable = ['nome'];

    public function formacoes()
    {
        return $this->belongsToMany('App\Formacoes','professores_formacoes')
            ->withPivot('grau','ano','id');
    }

    public function disciplinas()
    {
        return $this->belongsToMany('App\Disciplinas','professores_disciplinas')
            ->withPivot('id');
    }
}

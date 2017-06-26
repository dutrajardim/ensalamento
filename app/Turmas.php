<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turmas extends Model
{
    protected $fillable = ['descricao', 'abreviacao'];

    public function disciplinas()
    {
        return $this->belongsToMany('App\Disciplinas')->withPivot('horarios')->withTimestamps();
    }
}

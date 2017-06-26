<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horarios extends Model
{
    protected $fillable = ['descricao', 'ano','semestre'];

    public function ensalamentos()
    {
        return $this->hasMany('App\Ensalamentos');
    }
}

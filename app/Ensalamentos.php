<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ensalamentos extends Model
{
    protected $fillable = ['salas_id','turmas_id', 'disciplinas_id', 'horarios_id', 'dia', 'horario'];
}

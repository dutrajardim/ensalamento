<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salas extends Model
{
    protected $fillable = ['nome','capacidade','arCondicionado'];
}

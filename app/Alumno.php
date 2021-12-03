<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';

    public function curso(){
        return $this->belongsTo('App\Curso','curso_id');
    }
}

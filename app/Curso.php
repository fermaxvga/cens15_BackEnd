<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';

    public function materias()
    {
        return $this->hasMany('App\QuizRespuestas','id_curso');
    }
   
}
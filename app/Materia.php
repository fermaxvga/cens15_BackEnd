<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
   protected $table = 'materias';

   public function curso(){
       return $this->belongsTo('App\Curso','id_curso');
   }

}

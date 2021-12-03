<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
   protected $table = 'notas';
   
   public function curso(){
      return $this->belongsTo('App\Curso','id_curso');
  }

  public function materia(){
      return $this->belongsTo('App\Materia','id_materia');
  }

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alumno;
use App\Curso;
use App\HistoricoInscripciones; 
use App\Materia;
use App\Nota; 

class NotasController extends Controller
{
    public function getNotas($id_alumno){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');

    //   $notas=Nota::select('*')->where('id_alumno',$id_alumno)->get()->load('curso')->load('materia')->groupBy('id_curso');
      //  ->groupBy('id_curso');
        $notas=DB::connection('mysql')
                ->table('notas')
                ->join('cursos','cursos.id','=','notas.id_curso')
                ->join('alumnos','alumnos.id','=','notas.id_alumno')
                ->join('materias','materias.id','=','notas.id_materia')
                ->select(DB::raw('notas.id,notas.id_curso,cursos.curso,cursos.division,cursos.especialidad,
                cursos.modalidad,alumnos.nombre,alumnos.apellido,materias.materia,
                notas.cuatrimestre1,notas.cuatrimestre2,notas.final_anual,notas.diciembre,notas.febrero'))
                ->where('id_alumno',$id_alumno)
                ->get()
                ->groupBy('id_curso');
        // $notas=DB::connection('mysql')
        //         ->table('notas')
        //         ->join('cursos','cursos.id','=','notas.id_curso')
        //         ->join('materias','notas.id_materia','=','materias.id')
        //         ->join('alumnos','notas.id_alumno','=','alumnos.id')
        //         ->select(DB::raw('curso,division,especialidad,materias.materia','id_alumno','cuatrimestre1','cuatrimestre2'))
        //                 ->where('notas.id_alumno',$id_alumno)
        //                 ->get();
 
        $data=array(
            'notas' =>$notas,
            'status' =>'success', 
        );

        return response()->json($data,200);
    }

    public function insertarNota(Request $request){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $json=$request->input('json',null);
        $params=json_decode($json);
        $id=$params->id; 

        $update=array(
            'cuatrimestre1'=> $params->cuatrimestre1,
            'cuatrimestre2'=> $params->cuatrimestre2,
            'diciembre'=> $params->diciembre,
            'febrero'=> $params->febrero,
            'final_anual'=> $params->final_anual,
        );
      
        $nota=Nota::select('*')->where('id',$id)->update($update);

        $data=array(
            'notas' =>$nota,
            'status' =>'success', 
        );

        return response()->json($data,200);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Curso;
use App\CursoDivision;
use App\CursoEspecialidad;
use App\CursoModalidad;
use App\CursoNumero;

class CursosController extends Controller
{
    public function getCursos(){
        //Obtiene cursos completos con todos sus atributos
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $cursos=Curso::select('*')->orderBy('curso')->get();
        if(count($cursos)==0){  
            $data=array(
                'message'=>'No se encontraron cursos',
                'status'=>'error'
            );
        }else{
            $data=array(
                'cursos'=>$cursos,
                'status'=>'success'
            );
        }
        return response()->json($data,200);
    }
    public function getNroCursos(){
        //Obtiene los número de cursos
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $nroCursos=CursoNumero::select('*')->get();
        if(count($nroCursos)==0){
            $data=array(
                'message'=>'No se encontraron números de cursos',
                'status'=>'error'
            );
        }else{
            $data=array(
                'nroCursos'=>$nroCursos,
                'status'=>'success'
            );
        }
        return response()->json($data,200);
    }

    public function getDivisiones(){
        //Obtiene la división del curso, A,B,C
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $divisiones=CursoDivision::select('*')->get(); 
        if(count($divisiones)==0){
            $data=array(
                'message'=>'No se encontraron divisiones',
                'status'=>'error'
            );
        }else{
            $data=array(
                'divisiones'=>$divisiones,
                'status'=>'success'
            );
        }
        return response()->json($data,200);
    }

    public function getEspecialidades(){
        //Obtiene las especialidades. 
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $especialidades=CursoEspecialidad::select('*')->get();
        if(count($especialidades)==0){
            $data=array(
                'message'=>'No se encontraron divisiones',
                'status'=>'error'
            );
        }else{
            $data=array(
                'especialidades'=>$especialidades,
                'status'=>'success'
            );
        }
        return response()->json($data,200);
    }

    public function getModalidad(){
        //Obtiene la modalidad, adolescentes, adultos, etc..
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $modalidad=CursoModalidad::select('*')->get();
        if(count($modalidad)==0){
            $data=array(
                'message'=>'No se econtraron modalidades',
                'status'=>'error'
            );     
        }else{
            $data=array(
                'modalidad'=>$modalidad,
                'status'=>'success'
            );
        }
        return response()->json($data,200);
    }

    public function saveCurso(Request $request){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $json=$request->input('json',null);
        $params=json_decode($json);
        $curso=new Curso();
        $curso->curso=$params->curso;
        $curso->division=$params->division;
        $curso->especialidad=$params->especialidad;
        $curso->modalidad=$params->modalidad;
        $curso->semipresencial=$params->semipresencial;
        $repetido=Curso::select('*')
                    ->where('curso',$params->curso)
                    ->where('division',$params->division)
                    ->get();
        if(count($repetido)==0){
            $curso->save();
            $data=array(
                'message'=>'Curso creado exitosamente',
                'status'=>'success'
            );
        }else{
            $data=array(
                'message'=>'Ya existe un curso '.$params->curso.' Division '.$params->division,
                'status'=>'error'
            );
        }
    
        return response()->json($data,200);
    }

}

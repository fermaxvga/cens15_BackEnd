<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materia;
use App\Curso;

class MateriaController extends Controller
{
    public function saveMateria(Request $request){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $json=$request->input('json',null);
        $params=json_decode($json);
     
        $id_curso=$params->id_curso;

        $id_curso=intval($id_curso);
        
    
        $repetido=Materia::select('*')
                                        ->where('materia',$params->materia)
                                        ->where('id_curso',$id_curso)
                                        ->get();
        if(count($repetido)==0){

            $materia=new Materia();
            $materia->materia=$params->materia;
            if(isset($params->profe1)){
                $materia->profesor1=$params->profe1;
            }
            if(isset($params->profe2)){
                $materia->profesor2=$params->profe2;
            }
            $materia->id_curso=$id_curso;
              $materia->save();
            $data=array(
                'materia'=>$materia,
                'status'=>'success'
            );
        }else{
            $data=array(
                'status'=>'repetido'
            );
        }
        return response()->json($data,200);
    }

    public function getMaterias(){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        
        $materias=Materia::select('*')->get()->load('curso');

        if(count($materias)==0){
            $data=array(
                'message'=>'Todavía no se cargaron materias',
                'status'=>'vacio'
            );
        }else{
            $data=array(
                'materias'=>$materias,
                'status'=>'success'
            );
        }
        return response()->json($data,200);
    }

    public function updateMateria(Request $request, $id){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');

        $json=$request->input('json',null);

        $params=json_decode($json);
        
        $id_curso=$params->id_curso;

        $id_curso=intval($id_curso);

        $update=array(
            'materia'   =>  $params->name,
            'id_curso'  =>  $params->id_curso,
            'profesor1' =>  $params->profesor1,
            'profesor2' =>  $params->profesor2
        );

        
        $materia=Materia::select('*')
                                    ->where('id',$id)
                                    ->update($update);


        $data=array(
            'materia'=>$materia,
            'status'=>'success'
        );

        return response()->json($data,200);
    }

    public function getMateriaById($id){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $materia=Materia::select('*')->where('id',$id)->get();
        $data=array(
            'materia'=>$materia,
            'status'=>'success'
        );
        return response()->json($data,200);
    }


    public function deleteMateria($id){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');

        $materia=Materia::select('*')->where('id',$id)->delete();

        $data=array(
            'message'=>'Materia eliminada',
            'status'=>'success'
        );
        return response()->json($data,200);
    }



}

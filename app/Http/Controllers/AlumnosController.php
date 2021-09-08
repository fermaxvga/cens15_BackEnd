<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alumno;

class AlumnosController extends Controller
{
    public function getAlumnos(){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $alumnos=Alumno::select('*')->get()->load('Curso');
        if(count($alumnos)==0){
            $data=array(
                'message'=>'No se encontraron alumnos',
                'status'=>'error'
            );
        }else{
            $data=array(
                'alumnos'=>$alumnos,
                'status'=>'success'
            ); 
        }
        return response()->json($data,200);
    }

    public function inscribirAlumno(Request $request){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $json=$request->input('json',null);
        $params=json_decode($json);
        
     //   dd($params);
        $alumno=new Alumno();

        $alumno->nombre=$params->nombre;
        $alumno->apellido=$params->apellido;
        $alumno->dni=$params->dni;
        $alumno->fecha_de_nacimiento=$params->fecha_de_nacimiento;
        $alumno->domicilio=$params->domicilio;
        $alumno->loc_nac=$params->loc_nac;
        $alumno->prov_nac=$params->prov_nac;
        $alumno->pais_nac=$params->pais_nac;
        $alumno->tel_alumno=$params->tel_alumno;
        $alumno->email=$params->email;
        $alumno->nombre_tutor=$params->nombre_tutor;
        $alumno->tel_tutor=$params->tel_tutor;

        if($params->fot_dni){
            $alumno->fot_dni=1;
        }else{
            $alumno->fot_dni=0;
        }
        if($params->cert_estudio){
            $alumno->cert_estudio=1;
        }else{
            $alumno->cert_estudio=0;
        }
        if($params->pase){
            $alumno->pase=1;
        }else{
            $alumno->pase=0;
        }
        if($params->cuil){
            $alumno->cuil=1;
        }else{
            $alumno->cuil=0;
        }
        $alumno->save(); 
        $data=array(
            'alumnos'=>$alumno,
            'status'=>'success'
        );

        return response()->json($data,200);
    }
}

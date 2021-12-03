<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alumno;
use App\Curso;
use App\HistoricoInscripciones; 
use App\Materia;
use App\Nota; 

class AlumnosController extends Controller
{
    public function getAlumnos(){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $alumnos=Alumno::select('*')->get()->load('curso');
        if(count($alumnos)==0){
            $data=array(
                'message'=>'No se encontraron alumnos',
                'status'=>'empty'
            );
        }else{
            $data=array(
                'alumnos'=>$alumnos,
                'status'=>'success'
            ); 
        }
        return response()->json($data,200);
    }

    public function getAlumno($id){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
        $alumno=Alumno::select('*')->where('id',$id)->get()
     //   ->load('curso')
        ;
        if(count($alumno)==0){
            $data=array(
                'message'=>'Alumno no encontrado',
                'status'=>'error'
            );
        }else{
            $data=array(
                'alumno'=>$alumno,
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
        $doc=$params->dni;
        $doc_repetido=Alumno::select('*')->where('dni',$doc)->count();
        if($doc_repetido==0){
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
            $alumno->curso_id=$params->curso;
            $alumno->inscripcion=$params->inscripcion;

            
    
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
            
           // dd($alumno); 

            $inscripcion=new HistoricoInscripciones();

            $inscripcion->nombre=$params->nombre;
            $inscripcion->apellido=$params->apellido;
            $inscripcion->dni=$params->dni;
            $inscripcion->anio=$params->inscripcion;
           
            $curso=Curso::select('curso','division','especialidad','modalidad')->where('id',$params->curso)->get();
            
            //dd($curso);
            $curso=$curso[0]['curso'].' '.$curso[0]['division'].' '.$curso[0]['especialidad'].' '.$curso[0]['modalidad'];
            
            $inscripcion->curso=$curso; 
            
            $inscripcion->save(); 
            
            //dd($alumno);
           
            $data=array(
                'alumno'=>$alumno,
                'inscripcion'=>$inscripcion, 
                'status'=>'success'
            );

            $materias=Materia::select('id')->where('id_curso',$alumno->curso_id)->get();
       
            for ($i=0; $i < count($materias) ; $i++) { 
                $nota=new Nota();
                $nota->id_alumno=$alumno->id;
                $nota->id_curso=$params->curso;
                $nota->id_materia=$materias[$i]->id;
                $nota->save(); 
            }
        }else{
            $data=array(
                'message'=>'Ya se encuentra un alumno cargado con este DNI',
                'status'=>'repetead'
            );
        }
      
              
         return response()->json($data,200);
    }

    public function getInscripciones($dni){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');

        $historico=HistoricoInscripciones::select('*')->where('dni',$dni)->get();
        
        $data=array(
            'historico'=>$historico,
            'status'=>'success'
        );

        return response()->json($data,200);
    }

    public function reinscribir($id_alumno, $id_curso){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');

      //  $curso=Curso::select('*')->where('id',$id_curso)->get();

        $alumno=Alumno::select('*')->where('id',$id_alumno)->update(['curso_id'=>$id_curso]);
        $alumno=Alumno::select('*')->where('id',$id_alumno)->get();

      // dd($alumno[0]->id);
        $inscripcion=new HistoricoInscripciones();

        $inscripcion->nombre=$alumno[0]->nombre;
        $inscripcion->apellido=$alumno[0]->apellido;
        $inscripcion->dni=$alumno[0]->dni;
        $inscripcion->anio=$alumno[0]->inscripcion;
        
        $inscripcion->save(); 
        //dd($inscripcion);
        $materias=Materia::select('id')->where('id_curso',$id_curso)->get();
      // dd(count($materias));
      for ($i=0; $i < count($materias) ; $i++) { 
        $nota=new Nota();
        $nota->id_alumno=$alumno[0]->id;
        $nota->id_curso=$id_curso;
        $nota->id_materia=$materias[$i]->id;
        $nota->save(); 
      //  dd($nota);
    }

        $data=array(
            'alumno'=>$alumno,
            'status'=>'success'
        );

        return response()->json($data,200);
    }

    public function getNotas($id_alumno){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');

        $notas=Nota::select('*')->where('id_alumno',$id_alumno)->get();

        $data=array(
            'notas'=>$notas,
            'status'=>'success'
        );
        
        return response()->json($data,200);


    }
}

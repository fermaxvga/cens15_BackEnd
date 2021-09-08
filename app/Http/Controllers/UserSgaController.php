<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\ValidarDni;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;

class UserSgaController extends Controller
{
    public function register(Request $request){
        header('Access-Control-Allow-Origin','*');
        header('Access-Control-Allow-Methods','*');
//dd($request);
        $json=$request->input('json',null);
        $params=json_decode($json);
        $email=(!is_null($json))&&isset($params->email)?$params->email:null;
        $name =(!is_null($json))&&isset($params->name)?$params->name:null;
        $surname=(!is_null($json))&&isset($params->surname)?$params->surname:null;
        $dni=(!is_null($json))&&isset($params->dni)?$params->dni:null;

        $role='ROLE_USER';
        $password=(!is_null($json))&&isset($params->password)?$params->password:null;
        if(!is_null($email)&&!is_null($password)&&!is_null($name)){
        
            $user=new User();
            $user->email=$email;
            $user->name=$name;
            $user->dni=$dni;
            $user->role=$role;
            $user->surname=$surname;
            $pwd=hash('sha256',$password);
            $user->password=$pwd;
           // dd($user);
            //comprobar duplicado
            $isset_user=User::where('email','=',$email)->first();

            //dd($isset_user);
            if(!$isset_user){
                    $user->save(); 
                    $dni=ValidarDni::select('dni')->where('dni',$dni)->update(['status'=>1]);
                    $data=array(
                        'status'=>'success',
                        'code'=>200,
                        'message'=>'Usuario creado correcamente'
                    );
            }else{
                $data=array(
                    'status'=>'repetido',
                    'code'=>400,
                    'message'=>'Ya existe un usuario con este email'
                );
            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Usuario no creado'
            );
        }
 
        return response()->json($data,200); 
    }

    public function validarDni($dni){
        header('Access-Control-Allow-Origin', '*');
        header('Access-Control-Allow-Methods', '*');

        $dni=ValidarDni::select('*')->where('dni',$dni)->get();
     //   dd($dni[0]['status']);
        if(count($dni)==0){
            $data=array(
                'message'=>'No se encuentra el DNI, por favor solicite la precarga de sus datos',
                'status'=>'error',
                'code'=>400,
            );
        }elseif($dni[0]['status']==1){
            $data=array(
                'dni'=>$dni[0]['dni'],
                'message'=>'Ya existe un registro con este DNI. Intente loguearse.',
                'status'=>'error',
                'code'=>400,
            );
        }else{
            $data=array(
                'dni'=>$dni[0]['dni'],
                'message'=>'El Dni se encuentra precargado, puede realizar el registro',
                'status'=>'success',
                'code'=>200,
            );
        }
        return response()->json($data,200); 
    }

    public function login(Request $request){
        header('Access-Control-Allow-Origin', '*');
        header('Access-Control-Allow-Methods', '*');

//dd($request);
        $jwtAuth=new JwtAuth();
        //Recibir Post
        $json=$request->input('json',null);
        $params=json_decode($json);
//dd($params);
        $email=(!is_null($json)&&isset($params->email))?$params->email:null;
        $password=(!is_null($json)&&isset($params->password)) ? $params->password:null;
        $getToken=(!is_null($json)&&isset($params->gettoken)) ? $params->gettoken:null;
        //cifrar password
        $pwd=hash('sha256',$password);    
        if(!is_null($email)&& !is_null($password)&&($getToken==null||$getToken==false)){
           // dd($params);
            $singup=$jwtAuth->singup($email,$pwd);
        }elseif($getToken==true){
            $singup=$jwtAuth->singup($email,$pwd,$getToken);
        }else{
            $singup=array(
                'message'=>'Envia tus datos por POST',
                'status'=>'error',
            );
        }
        return response()->json($singup,200); 
    }
  
}

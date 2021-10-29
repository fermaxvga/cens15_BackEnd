<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key='clave-secreta-cens15-8gjd983d*&$#21';
        //dd($key);
    }
    public function singup($email,$password,$getToken=null){
        /* Verificamos si existe el usuario*/
        $user=User::where(
            array(
                'email'=>$email,
                'password'=>$password
            ))->first();
     //  dd($user);          
        $singup=false;
              //  dd($getToken);
        if(is_object($user)){
            $singup=true;
        }
        if($singup){
            //generar token y devolver
            $token=array(
                'sub'=>$user->id,
                'email'=>$user->email,
                'name'=>$user->name,
                'surname'=>$user->surname,
                'role'=>$user->role_id,
                'dni'=>$user->dni,
                'iat'=>time(),
                'exp'=>time()+(7*24*60*60)
                //Comparar si, iat es mayor a 'exp' entonces , el token expiro
            );
            $jwt=JWT::encode($token,$this->key,'HS256');
            $decoded=JWT::decode($jwt,$this->key,array('HS256'));
            if(is_null($getToken)){
                return $jwt;
            }else{
                return $decoded; 
            }

        }else{
            //devolver error
            $data=array(
                'message'=>'Login ha fallado!',
                'status'=>'error'
            );
            return response()->json($data,200); 
        }
    }

    public function checkToken($jwt, $getIdentity=false){
        $auth=false;
        try{
            $decoded=JWT::decode($jwt,$this->key,array('HS256'));
        }catch(\UnexpectedValueException $e){
            $auth=false;
        }catch(\DomainException $e){
            $auth=false;
        } 

        if(is_object($decoded) && isset($decoded->sub)){
            $auth=true;
        }else{
            $auth=false;
        }

        if($getIdentity){
            return $decoded;
        }
    
        return $auth;
    }
}
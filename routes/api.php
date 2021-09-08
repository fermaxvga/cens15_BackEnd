<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/user-register','UserSgaController@register');
// Route::get('/validar-dni/{dni}','UserSgaController@validarDni');

Route::group(['prefix'=>'usuarios'],function(){
    Route::post('/register','UserSgaController@register');
    Route::post('/login','UserSgaController@login');
    Route::get('/validar-dni/{dni}','UserSgaController@validarDni');
});

Route::group(['prefix'=>'alumnos'],function(){
    Route::get('/listado','AlumnosController@getAlumnos');
    Route::post('/inscribir','AlumnosController@inscribirAlumno');
});

Route::group(['prefix'=>'cursos'],function(){
    Route::get('/listado','CursosController@getCursos');   
    Route::get('/cursos','CursosController@getNroCursos');    
    Route::get('/division','CursosController@getDivisiones');    
    Route::get('/especialidad','CursosController@getEspecialidades');    
    Route::get('/modalidad','CursosController@getModalidad');    
    Route::post('/agregar','CursosController@saveCurso');    
});
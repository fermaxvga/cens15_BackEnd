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
    Route::get('/listado','UserSgaController@getUsers');
    Route::post('/register','UserSgaController@register');
    Route::post('/login','UserSgaController@login');
    Route::get('/validar-dni/{dni}','UserSgaController@validarDni');
    Route::post('/precargar','UserSgaController@precargarUsuario');
    Route::get('/listar-precargados','UserSgaController@getPrecargados');
    Route::delete('/delete-precargados/{id}','UserSgaController@deletePrecargados');
    Route::put('/update/{id}','UserSgaController@updateUser');
    Route::get('/{id}','UserSgaController@getUser');
});

Route::group(['prefix'=>'alumnos'],function(){
    Route::get('/listado','AlumnosController@getAlumnos');
    Route::get('/{id}','AlumnosController@getAlumno');
    Route::get('/notas/{id}','AlumnosController@getNotas');
    Route::post('/inscribir','AlumnosController@inscribirAlumno');
    Route::get('/historico-inscripciones/{dni}','AlumnosController@getInscripciones');
    Route::put('/reinscribir/{id_alumno}/{id_curso}','AlumnosController@reinscribir');
});

Route::group(['prefix'=>'cursos'],function(){
    Route::get('/listado','CursosController@getCursos');   
    Route::get('/cursos','CursosController@getNroCursos');    
    Route::get('/division','CursosController@getDivisiones');    
    Route::get('/especialidad','CursosController@getEspecialidades');    
    Route::get('/modalidad','CursosController@getModalidad');    
    Route::post('/agregar','CursosController@saveCurso'); 
    Route::put('/update/{id}','CursosController@updateCurso');
    Route::get('/consultar/{curso}/{division}','CursosController@getCurso');
    Route::get('/consultar/{id}','CursosController@getCursoById');  
    Route::delete('/delete/{id}','CursosController@deleteCurso');  
    Route::get('/materias-curso/{id_curso}','CursosController@materiasPorCurso');
});

Route::group(['prefix'=>'materias'],function(){
    Route::post('/agregar','MateriaController@saveMateria');
    Route::get('/listar','MateriaController@getMaterias');
    Route::get('/get/{id}','MateriaController@getMateriaById');
    Route::put('/update/{id}','MateriaController@updateMateria');
    Route::delete('/delete/{id}','MateriaController@deleteMateria');
});

Route::group(['prefix'=>'notas'],function(){
    Route::get('/{id}','NotasController@getNotas');
    Route::put('/insertar-nota','NotasController@insertarNota');
});



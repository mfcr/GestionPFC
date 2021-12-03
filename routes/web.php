<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/MiLogin', [App\Http\Controllers\HomeController::class, 'milogin'])->name('milogin');


Route::get('/Docs/{user}/{code}/{ciclo}', [App\Http\Controllers\DocsController::class, 'docs'])->name('documentos');

Route::get('/Propuestas/{user}/{code}/{ciclo}/{mode}', [App\Http\Controllers\PropuestasController::class, 'propuesta'])->name('propuestas');

Route::get('/Fechas/{curso}', [App\Http\Controllers\FechaController::class, 'fecha'])->name('fechas');

Route::get('/Documentos', [App\Http\Controllers\DocsController::class, 'docsShow'])->name('gestion_documentos');

Route::get('/TiposProyectos', [App\Http\Controllers\TipoProyectoController::class, 'tipos_proyectos'])->name('tipos_proyectos');

Route::get('/Ciclos', [App\Http\Controllers\CicloModuloController::class, 'ciclos'])->name('ciclos');

Route::get('/Modulos', [App\Http\Controllers\CicloModuloController::class, 'modulos'])->name('modulos');

Route::get('/TutoresColectivos/{curso}', [App\Http\Controllers\TutoresColectivosController::class, 'tutores_colectivos'])->name('tutores_colectivos');


Route::get('/CiclosModulos', [App\Http\Controllers\CicloModuloController::class, 'ciclos_modulos'])->name('ciclos_modulos');

Route::get('/Rubricas/{curso}', [App\Http\Controllers\RubricaController::class, 'rubricas'])->name('rubricas');

Route::get('/Reset/{curso}', [App\Http\Controllers\ResetController::class, 'resetView'])->name('resetView');

Route::get('/Reset/{cursoCierre}/{cursoNuevo}/{alumnos}/{docentes}/{fechas}/{rubricas}/{curso}', [App\Http\Controllers\ResetController::class, 'reset'])->name('reset');


Route::get('/AlumnosCarga/{curso}', [App\Http\Controllers\AlumnoController::class, 'carga'])->name('cargaAlumnos');

Route::get('/AlumnosGestion/{curso}', [App\Http\Controllers\AlumnoController::class, 'gestion'])->name('gestionAlumnos');

Route::post('/Alumnos/AltaFile', [App\Http\Controllers\AlumnoController::class, 'leeFichero'])->name('leeFichero');

Route::post('/AlumnosCargaIndividual', [App\Http\Controllers\AlumnoController::class, 'altaIndividual'])->name('altaIndividual');

Route::get('/AlumnosBorra/{id}', [App\Http\Controllers\AlumnoController::class, 'borrado'])->name('borrado');


Route::get('/Alumnos/alta/{curso}/{ciclo}', [App\Http\Controllers\AlumnoController::class, 'matricula'])->name('cargaAlumnosMatricula');

Route::get('/DocentesCarga/{curso}', [App\Http\Controllers\DocenteController::class, 'carga'])->name('cargaDocentes');

Route::post('/DocentesCargaIndividual', [App\Http\Controllers\DocenteController::class, 'altaIndividual'])->name('altaIndividualDocentes');

Route::get('/DocentesGestion/{curso}', [App\Http\Controllers\DocenteController::class, 'gestion'])->name('gestionDocentes');

Route::post('/DocentesAlta/{curso}', [App\Http\Controllers\DocenteController::class, 'alta'])->name('cargaDocentesAlta');

Route::get('/Proyectos/{id}/{mode}/{code}', [App\Http\Controllers\ProyectoController::class, 'proyectos'])->name('proyectos');

Route::post('/mensaje/{tipoUser}/{idUser}/{idRecipient}/{idProyecto}', [App\Http\Controllers\HomeController::class, 'mensaje'])->name('mensaje');

//Route::get('/Admin/Propuestas/{user}/{code}/{mode}', [App\Http\Controllers\ProyectoPropuestoController::class, 'propuestaVer'])->name('propuestas_ver');




Route::post('custom_login', [CustomAuthController::class, 'custom_login'])->name('custom_login'); 
Route::post('changePassword', [CustomAuthController::class, 'changePassword'])->name('changePassword'); 
Route::post('resetcontra', [CustomAuthController::class, 'resetcontra'])->name('resetcontra'); 
Route::get('logout', [CustomAuthController::class, 'logout'])->name('logout');


//@@@ nuevas rutas para llamar desde aplicacion android. @@@ pdte comprobar y pdte subir a servidor.
Route::post('/api/foreignlogin', [App\Http\Controllers\CustomAuthController::class, 'foreign_login'])->name('foreign_login');
Route::post('/api/changePassword', [CustomAuthController::class, 'foreign_changePassword'])->name('foreign_changePassword'); 








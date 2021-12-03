<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProyectoController extends Controller
{
    
	public function proyectos($id, $mode,$code) { //$code= id del alumno o del usuario.


		$headerData['id']=$id;
		$headerData['mode']=$mode;
		$headerData['code']=$code;
        $headerData['apiUrl']=config('app.apiurl');
        $headerData['publicUrl']=substr(config('app.apiurl'),0,-5);
		
        $proyecto=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectos_full/'.$id);
        if ($proyecto->getStatusCode()>=300) {
            $proyecto=null;
        } else {
            $proyecto=$proyecto->json();
        }
        $estados=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'estados_proyectos');
        if ($estados->getStatusCode()>=300) {
            $estados=null;
        } else {
            $estados=$estados->json();
        }
        $docentes=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docentes_modulos_tutoriascolectivas');
        if ($docentes->getStatusCode()>=300) {
            $docentes=null;
        } else {
            $docentes=$docentes->json();
        }

        $tutColectivo=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docente_tut_colectivo_ciclos/'.$proyecto[0]['curso'].'/'.$proyecto[0]['ciclo_id']);
        if ($tutColectivo->getStatusCode()>=300) {
            $tutColectivo=null;
        } else {
            $tutColectivo=$tutColectivo->json();
        }


        $rubricas=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'rubricas_con_grupos/'.$proyecto[0]['curso'].'/'.$proyecto[0]['ciclo_id']);
        if ($rubricas->getStatusCode()>=300) {
            $rubricas=null;
        } else {
            $rubricas=$rubricas->json();
        }

        $tipos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'tipos_proyecto_ciclos_con_tipos/'.$proyecto[0]['ciclo_id']);
        if ($tipos->getStatusCode()>=300) {
            $tipos=null;
        } else {
            $tipos=$tipos->json();
        }

    	$headerData['proyecto']=$proyecto;
    	$headerData['estados']=$estados;
    	$headerData['docentes']=$docentes;
    	$headerData['tutColectivo']=$tutColectivo;
    	$headerData['rubricas']=$rubricas;
    	$headerData['tipos']=$tipos;

		return view('proyectos',$headerData);
	}
}

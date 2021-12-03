<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TutoresColectivosController extends Controller
{
    public function tutores_colectivos($curso)    {

        $headerData=['curso'=>$curso];
		$headerData['apiUrl']=config('app.apiurl');

        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }
		$headerData['ciclos']=$ciclos;

        $docentes=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docentes'); //Solo docentes en activo.
        if ($docentes->getStatusCode()>=300) {
            $docentes=null;
        } else {
            $docentes=$docentes->json();
        }
		$headerData['docentes']=$docentes;

        $tut_colectivos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docente_tut_colectivo_ciclos'); //Solo docentes en activo.
        if ($tut_colectivos->getStatusCode()>=300) {
            $tut_colectivos=null;
        } else {
            $tut_colectivos=$tut_colectivos->json();
        }
		$headerData['tut_colectivos']=$tut_colectivos;
        return view('tutoresColectivos',$headerData);

    }}

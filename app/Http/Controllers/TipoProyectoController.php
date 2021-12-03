<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TipoProyectoController extends Controller
{

    public function tipos_proyectos()    {
        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }

        $tipos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'tipo_proyectos');
        if ($tipos->getStatusCode()>=300) {
            $tipos=null;
        } else {
            $tipos=$tipos->json();
        }
        $tipos_ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'tipo_proyecto_ciclos');
        if ($tipos_ciclos->getStatusCode()>=300) {
            $tipos_ciclos=null;
        } else {
            $tipos_ciclos=$tipos_ciclos->json();
        }

        $headerData['ciclos']=$ciclos;
        $headerData['tipos']=$tipos;
        $headerData['tipos_ciclos']=$tipos_ciclos;
        $headerData['apiUrl']=config('app.apiurl');

        return view('tiposProyectos',$headerData);

    }

}

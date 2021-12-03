<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class FechaController extends Controller
{

    public function fecha($curso)    {

        $headerData=['curso'=>$curso];
		$headerData['apiUrl']=config('app.apiurl');

        $fechas=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'fechas');
        if ($fechas->getStatusCode()>=300) {
            $fechas=null;
        } else {
            $fechas=$fechas->json();
        }
        $headerData['fechas']=$fechas;

      /*  $estados=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'estados_proyectos');
        if ($estados->getStatusCode()>=300) {
            $estados=null;
        } else {
            $estados=$estados->json();
        }
        $headerData['estados']=$estados; */


        return view('fechas',$headerData);

    }

    //
}

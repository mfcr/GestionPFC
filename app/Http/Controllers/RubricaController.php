<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class RubricaController extends Controller
{

    public function rubricas($curso)    {

        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }

        $grupos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'grupo_rubricas');
        if ($grupos->getStatusCode()>=300) {
            $grupos=null;
        } else {
            $grupos=$grupos->json();
        }
        $rubricas=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'rubricas');
        if ($rubricas->getStatusCode()>=300) {
            $rubricas=null;
        } else {
            $rubricas=$rubricas->json();
        }

        $headerData['ciclos']=$ciclos;
        $headerData['grupos']=$grupos;
        $headerData['rubricas']=$rubricas;
        $headerData['apiUrl']=config('app.apiurl');
        $headerData['curso']=$curso;



        return view('rubricas',$headerData);

    }



}

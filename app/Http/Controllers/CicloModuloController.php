<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class CicloModuloController extends Controller
{
    

    public function ciclos()    {
        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }

        $headerData['ciclos']=$ciclos;
        $headerData['apiUrl']=config('app.apiurl');

        return view('ciclos',$headerData);

    }


    public function modulos()    {
        $modulos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'modulos');
        if ($modulos->getStatusCode()>=300) {
            $modulos=null;
        } else {
            $modulos=$modulos->json();
        }
        $headerData['modulos']=$modulos;
        $headerData['apiUrl']=config('app.apiurl');

        return view('modulos',$headerData);

    }

   public function ciclos_modulos()    {
        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }
        $modulos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'modulos');
        if ($modulos->getStatusCode()>=300) {
            $modulos=null;
        } else {
            $modulos=$modulos->json();
        }

        $ciclo_modulos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclo_modulos');
        if ($ciclo_modulos->getStatusCode()>=300) {
            $ciclo_modulos=null;
        } else {
            $ciclo_modulos=$ciclo_modulos->json();
        }


        $headerData['ciclos']=$ciclos;
        $headerData['modulos']=$modulos;
        $headerData['ciclo_modulos']=$ciclo_modulos;
        $headerData['apiUrl']=config('app.apiurl');

        return view('ciclos_modulos',$headerData);

    }


}

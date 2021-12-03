<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use View;

class PropuestasController extends Controller
{

    public function propuesta($user,$code,$ciclo,$mode)    {
    	//Devuelve los datos necesarios a las vistas para añadir un proyecto propuesto y para ver los proyectos ya propuestos.
        $headerData=['code'=>$code,'ciclo_id'=>$ciclo,'user'=>$user];

        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }
        $headerData['ciclos']=$ciclos;

        $headerData['mode']=$mode;
        if($mode!='add') {
	        $propuestas=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectos_propuestos');
	        if ($propuestas->getStatusCode()>=300) {
	            $propuestas=null;
	        } else {
	            $propuestas=$propuestas->json();
	        }
	        $headerData['propuestas']=$propuestas;
        } else {
        	$headerData['propuestas']=null;
        }
        $headerData['apiUrl']=config('app.apiurl');
        return view('propuestas',$headerData);

    }


    public function propuestaGuardar($user,$code)    {

    return $POST;

    }



}

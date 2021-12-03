<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use View;

class DocsController extends Controller {

    /**
     * Muestra los documentos solicitados.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function docs($user,$code,$ciclo)    {
        //Route::get('/Docs/{user}/{code}/{ciclo}', [App\Http\Controllers\DocsController::class, 'docs'])->name('documentos');
        //user 0/1/2 nologged/docente/alumno/. code id de alumno/docente/0. ciclo=id ciclo o 0 si genreales.

        $headerData=['code'=>$code,'ciclo'=>$ciclo,'user'=>$user];
        $headerData['publicUrl']=substr(config('app.apiurl'),0,-5);

        $docsData=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'documentos/filter/'.$user.'/'.$ciclo);
        if ($docsData->getStatusCode()>=300) {
            $docsData=null;
        } else {
            $docsData=$docsData->json();
        }

        //Unimos los datos necesarios para mostrar los menus junto con los datos para mostrar los documentos.
        $headerData['docs']=$docsData;
        if ($ciclo==0 || $ciclo==null) {
            $headerData['ciclo_doc']=null;
        } else {
            $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos/'.$ciclo);
            if ($ciclos->getStatusCode()>=300) {
                $headerData['ciclo_doc']=null;
            } else {
                $headerData['ciclo_doc']=$ciclos->json();
            }
        }
        return view('documentos',$headerData);
    }


    public function docsShow()    {
        $headerData=[];

        $docsData=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'documentos');
        if ($docsData->getStatusCode()>=300) {
            $docsData=null;
        } else {
            $docsData=$docsData->json();
        }

        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }
        $headerData['docs']=$docsData;
        $headerData['ciclos']=$ciclos;
        $headerData['apiUrl']=config('app.apiurl');
        $headerData['publicUrl']=substr(config('app.apiurl'),0,-5);
        return view('documentosGestion',$headerData);

    }



}
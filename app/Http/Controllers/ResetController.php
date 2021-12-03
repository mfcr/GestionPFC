<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ResetController extends Controller
{
    public function resetView($curso)    {
        $headerData['curso']=$curso;
		$headerData['apiUrl']=config('app.apiurl');

        return view('reset',$headerData);

    }




}

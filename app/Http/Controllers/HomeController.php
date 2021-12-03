<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomAuthController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use View;


class HomeController extends Controller {

    public function milogin() {
        return view('miloginform');
    }

    public function index()    {
        $user=0;
        $code=0;
        $cambiada=true;

        if (Auth::check()) {
            $us = Auth::user();
            $user=$us->category;
            $cambiada=$us->password_modificada;
            if ($user==1) { //Docente
                $do=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'docentes_email/',['email'=>$us->email]);
                if ($do->getStatusCode()>=300) { 
                    $user=0; 
                } else {
                    $code=$do->json()['id'];
                }
            } else { //Alumno
                $al=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'alumnos_email/',['email'=>$us->email]);
                if ($al->getStatusCode()>=300) { 
                    $user=0; 
                } else {
                    $code=$al->json()['id'];
                }
            }
        } 
        return view('home',$this->basicData($user,$code,$cambiada));
    }
//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------
    public function basicData($user,$code,$cambiada) { //$user 0 not logged, 1 docente 2 alumno. $code (id del alumno o docente o 0 si no logged)

        $al=null;           $proyAl=null;        $do=null;           $prop=null;           $ciclos=null;
        $tut_col=null;      $tut_indiv=null;     $proyPub=null;   
        $curso=null;        $adminProy=null;    

        $ci=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos'); 
        if ($ci->getStatusCode()<300) {            $ciclos=$ci->json();        }

        $cu=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'parametros'); 
        if ($cu->getStatusCode()<300) {            $curso=$cu->json()['cursoActual'];        }

        $pp=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectos_publicos'); 
        if ($pp->getStatusCode()<300) {            $proyPub=$pp->json();        }

        if ($ciclos==null || $curso==null) {
            $user=0; $code=0; //Ha habido un 'Error, 'entramos como invitado.
            return(compact('user','curso','ciclos','al','proyAl','do','tut_col','tut_indiv','code','adminProy','proyPub','cambiada')); 
        }

        if ($user>0)  { // logged 80% 
            if ($user==1) { //Docente  
                //Datos del docente
                $d=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docentes_modulos_tutoriascolectivas_tutindiv/'.$code); 
                if ($d->getStatusCode()<300) {                    $do=$d->json();                }
                
                if ($do!=null) {
                    //info relevante si docente es Administrador.
                    if ($do['isAdmin']) {  //carga de proyectos para ver por administrador (todos los del año)
                        //$admin_proy_curso=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectosAdmin/'.$curso);
                        $admin_proy_curso=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectosAdmin/'.$curso);
                        if ($admin_proy_curso->status()<300) {                            $adminProy=$admin_proy_curso->json();                        }
                    }
                    //Envio info si es tut_indiv.
                    $pr=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectosTutIndiv/'.$curso.'/'.$do['id']);
                    if ($pr->getStatusCode()<300) {                        $tut_indiv=$pr->json();                    } 
                    //Envio Info si docente es tutorColectivo.
                    $tuts_response=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'tut_colectivo_ciclos_proyectos/'.$do['id'].'/'.$curso);
                    if ($tuts_response->status()<300) {                        $tut_col=$tuts_response->json();                    }
                }
            } else { //Alumno
                $a=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'alumnos/'.$code); 
                if ($a->getStatusCode()<300) {                    $al=$a->json();                }
                if ($al!=null) {
                    $p=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectos/alumno/'.$al['id']);
                    if (($p->getStatusCode())<300) {$proyAl=$p->json();}
                }
            }
        }
        return(compact('user','curso','ciclos','al','proyAl','do','tut_col','tut_indiv','code','adminProy','proyPub','cambiada')); 
    }
//----------------------------------------------------------------------------------
//----------------------------------------------------------------------------------    
    public function mensaje(Request $request,$tipoUser,$idUser,$idRecipient,$idProyecto) {

        $msg=$request->all();
        $cabecera=$msg['cabecera'];
        $mensaje=$msg['mensaje'];
        $txtaux="";
        $emailAdress="";
        if ($tipoUser=='al') { //Mensaje de alumno a docente
            $alumno=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'alumnos/'.$idUser); 
            if ($alumno->getStatusCode()<300) {
                $alumno=$alumno->json();
                $txtaux.='alumno '.$alumno['nombre'].' '.$alumno['apellido1'].' '.$alumno['apellido2'].' ';
            }
            $rec=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docentes/'.$idRecipient); 
            if ($rec->getStatusCode()<300) {
                $rec=$rec->json();
                $emailAdress=$rec['email'];
            }
        } elseif ($tipoUser=='fecha') { //Mensaje automático por fecha.
            $txtaux='sistema automático de la aplicación PFC del CIFP Avilés ';
            $rec=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'alumnos/'.$idRecipient); 
            if ($rec->getStatusCode()<300) {
                $rec=$rec->json();
                $emailAdress=$rec['email'];
            }
        } else { //Mensaje de tutor individual o colectivo
            $docente=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docentes/'.$idUser); 
            if ($docente->getStatusCode()<300) {
                $docente=$docente->json();
                $txtaux.='docente '.$docente['nombre'].' '.$docente['apellido1'].' '.$docente['apellido2'].' ';
            }
            $txtaux.='en calidad de ';
            if ($tipoUser=='col') {$txtaux.='tutor colectivo ';} else  {$txtaux.='tutor individual ';}
            $rec=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'alumnos/'.$idRecipient); 
            if ($rec->getStatusCode()<300) {
                $rec=$rec->json();
                $emailAdress=$rec['email'];
            }
            
        }
        $proyecto=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectos/'.$idProyecto); 
        if ($proyecto->getStatusCode()<300) {
            $proyecto=$proyecto->json();
            $txtaux.='en relacion al proyecto '.$proyecto['nombreProyecto'];
        }


        $mensaje.="<br/><br/>-----------------------------------------------------------------------<br/><br/> Esta usted recibiendo este mensaje enviado por el ".$txtaux.'<br/><br/> -----------------------------------------------------------------------';
        
        if ($emailAdress=="") {
            return response()->json(['message' => 'No ha sido posible recuperar la dirección de correo del receptor'], 400);            
        } else {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->CharSet = "UTF-8";
                $mail->Encoding = 'base64';
                $mail->SMTPDebug = 1;               
                $mail->isSMTP();                    
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;                        
                $mail->Username   = 'pfc.ifc.cifpaviles@gmail.com';  
                $mail->Password   = 'Campelo_21';                    
                $mail->SMTPSecure = 'ssl';            
                $mail->Port       = 465;

                //Recipients
                $mail->addAddress('manuel_campelo@yahoo.com'); //@@@ LUEGO CAMBIAR POR LOS VÁlIDOS.
                //$mail->addAddress($emailAdress);

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $cabecera;
                $mail->Body    = $mensaje;

                if ($mail->send()) {
                    return response()->noContent(200);    
                } else {
                    return response()->json(['message' => 'Error al enviar mensaje'], 400);    
                }
            } catch (Exception $e) {
                return response()->json(['message' => 'Error al enviar mensaje'], 400);
            }

        }

    }

}




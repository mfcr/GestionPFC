<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;

class AlumnoController extends Controller
{

    public function gestion($curso) {

        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }

        $alumnos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'alumnosConProyectos');
        if ($alumnos->getStatusCode()>=300) {
            $alumnos=null;
        } else {
            $alumnos=$alumnos->json();
        }

    	$headerData['alumnos']=$alumnos;
        $headerData['ciclos']=$ciclos;
        $headerData['apiUrl']=config('app.apiurl');
        $headerData['curso']=$curso;


    	return view('alumnos',$headerData);
    }

    public function altaIndividual(Request $request) {

    	$response=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'alumnosAltaIndividual',$request->all());

    	if ($response->status()<300) { 
    		$user=User::firstOrCreate(['email'=>strtolower($request->input('email'))]);
    		if ($user->password==null || $user->password=="") {
                //@@@ pdte crear passwords de verdad
                $password='prueba';
                //$password=Hash::make(str_random(8));
                //@@@ pdte crear passwords de verdad

                $user->password=bcrypt($password); 
                $user->password_modificada=false;
                //if (!$this->mensaje($user->email,$password)) {return $response->json(['message'=>'Error enviando email '.$user->email],400 );}
                $res=$this->mensaje($user->email,$password);
            }
    		if ($user->category==null) {$user->category=2;}
    		$user->save();
    	}
		return $response->json(['ok'],200);

    }

    public function borrado($id) {

    	$response=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'alumnosBorra/'.$id);

    	return $response->json();

    }

    public function carga($curso)    { //Muestra la pantalla para subir el fichero.
        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }
        $headerData['ciclos']=$ciclos;
        $headerData['curso']=$curso;
        $headerData['apiUrl']=config('app.apiurl');

        return view('alumnos_carga',$headerData);

    }


    public function leeFichero()    { //Muestra la pantalla para subir el fichero.

    	set_time_limit(600);
		$data['curso']=$_POST['curso'];
		$data['ciclo']=$_POST['ciclo_id'];

		$cabecera=true; //Si saltamos primera línea
		$orden=3;  //posición en la que está el email.


       if(!empty($_FILES)){ 
       		if (strpos(strtoupper($_FILES['fichero']['name']),'.CSV')==false) {
       			return response()->json(['message' => 'Error, Fichero erroneo. Debe ser de tipo csv'], 400);
       		}
	        $file = fopen($_FILES['fichero']['tmp_name'], 'r');
	        $linesCount=0;
	        $added=0;
	        $errores=array();
			while (($line = fgetcsv($file)) !== FALSE) {
				$linesCount++;
				if (!$cabecera || ($cabecera && $linesCount>1)) {
					//Obtenemos el email
					$email=explode(";",$line[0])[$orden-1];
					//Enviamos orden para guardar registro en API
					$data['email']=$email;
					$response=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'alumnosMatricula',$data);
			    	if (($response->getStatusCode()>=300)) {
			    		array_push($errores,'Error agregando al alumno con email '.$email.' codigo error: '.$response->getStatusCode().' - '.$response['message']);
			    	} else {
			    		$added++;
			    		$user=User::firstOrCreate(['email'=>strtolower($email)]);
			    		if ($user->password==null || $user->password=="") {
                            //@@@ pdte crear passwords de verdad
                            $password='prueba';
                            //$password=Hash::make(str_random(8));
                            //@@@ pdte crear passwords de verdad

                            $user->password=bcrypt($password); 
                            $user->password_modificada=false;
                            //if (!$this->mensaje($user->email,$password)) {array_push($errores,'Error enviando email '.$data['email']); }
                            $res=$this->mensaje($user->email,$password);
                        }
			    		if ($user->category==null) {$user->category=2;}
			    		$user->save();
			    	}

				}
			}
			fclose($file);
			if (sizeof($errores)==0) {
				return response()->json(['message'=>'Añadidos o modificados '.$added.' alumnos.'],200);
			} else {
				return response()->json($errores,400);
			}
		}

    }

    public function mensaje($emailAdress,$pass) {

        $cabecera='Alta alumn@ PFC';
        $mensaje="Alumn@ dad@ de alta en el sistema de gestión de PFC del CIFP de Avilés. <br/> <br/> Para acceder al servicio utilice las siguientes credenciales: <br/><br/> Usuari@: ".$emailAdress."<br/> Password: ".$pass."<br/><br/> En su primer acceso debera modificar su password.<br/>";
        $mensaje.="<br/><br/>-----------------------------------------------------------------------<br/><br/> Esta usted recibiendo este mensaje enviado por el  sistema automático de la aplicación PFC del CIFP Aviles <br/> Se ha producido el alta como alumn@ usuario de la aplicación <br/><br/> -----------------------------------------------------------------------";

        if ($emailAdress!="" && $emailAdress!=null) {
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
                   // return true;    
                } else {
                   // return false;    
                }
            } catch (Exception $e) {
                //return false;
            }
        }
    }


}

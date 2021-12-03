<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;


class DocenteController extends Controller
{

    public function gestion($curso) {

        $ciclos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos');
        if ($ciclos->getStatusCode()>=300) {
            $ciclos=null;
        } else {
            $ciclos=$ciclos->json();
        }

        $ciclos_modulos=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'ciclos_modulos_nombres');
        if ($ciclos_modulos->getStatusCode()>=300) {
            $ciclos_modulos=null;
        } else {
            $ciclos_modulos=$ciclos_modulos->json();
        }

        $docentes=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'docentes_modulos_tutoriascolectivas');
        if ($docentes->getStatusCode()>=300) {
            $docentes=null;
        } else {
            $docentes=$docentes->json();
        }

    	$headerData['docentes']=$docentes;
        $headerData['ciclos_modulos']=$ciclos_modulos;
        $headerData['ciclos']=$ciclos;
        $headerData['apiUrl']=config('app.apiurl');
        $headerData['curso']=$curso;


    	return view('docentes',$headerData);
    }


    public function altaIndividual(Request $request) {

    	$response=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'docentesAltaIndividual',$request->all());

    	if ($response->status()<300) { 
    		$user=User::firstOrCreate(['email'=>strtolower($request->input('email'))]);
    		if ($user->password==null || $user->password=="") {
    			//@@@ pdte crear passwords de verdad
    			$password='prueba';
                //$password=Hash::make(str_random(8));
    			//@@@ pdte crear passwords de verdad

    			$user->password=bcrypt($password); 
    			$user->password_modificada=false;
    			//if (!$this->mensaje($user->email,$password)) {return $response->json(['message'=>'Error enviando email '.$data['email']],400 );}
    			$res=$this->mensaje($user->email,$password);
    		}
    		if ($user->category==null) {$user->category=1;}
    		$user->save();
    	}

		return $response->json(['ok'],200);

    }



    public function carga($curso)    { //Muestra la pantalla para subir el fichero.

        return view('docentes_carga',['curso'=>$curso]);

    }


    public function alta($curso)    { 

    	set_time_limit(600);
		$cabecera=true; //Si saltamos primera línea
		$eliminar = array(" ", "-", "_", ",", ".", " ", ";", ":");
		//Orden de los campos 0->numero (evitar); 1->nombre; 2->apellido1; 3->apellido2, 4->dni, 5->email; 6->telefono; 7->isAdmin
		//   8->TutorColectivo(ciclo), 9/10 (11/12-13/14...29/30)->Ciclo-Modulo.
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

					$valores=explode(";",$line[0]);
					if (sizeof($valores)!=31) { return response()->json(['message' => 'Error, debe haber 31 campos en cada fila. '.sizeof($valores)], 400); }
					$data=null;
					$data['curso']=$curso;
					$data['activo']=true;
					if ($valores[1]!='') {$data['nombre']=substr($valores[1],0,30);}
					if ($valores[2]!='') {$data['apellido1']=substr($valores[2],0,30);}
					if ($valores[3]!='') {$data['apellido2']=substr($valores[3],0,30);}
					if ($valores[4]!='') {$data['dni']=substr(strtoupper(str_replace($eliminar,'',$valores[4])),0,9);}
					if ($valores[6]!='') {$data['telefono']=substr($valores[6],0,15);}
					if ($valores[5]!='') {$data['email']=substr($valores[5],0,50);} else {continue;}
					if ($valores[7]!='FALSO') {$data['isAdmin']=true;} 
					if ($valores[8]!='') {$data['tutorColectivo']=$valores[8];}
					//if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					//	array_push($errores,'Error añadiendo al docente con email '.$data['email'].' formato email oncorrecto.');
					//	continue;
					//}					
					for ($i=0;$i<11;$i++) {
						if ($valores[2*$i+9]!='' && $valores[2*$i+10]!='') {
							$data['ciclo'.$i]=$valores[2*$i+9];
							$data['modulo'.$i]=$valores[2*$i+10];
						}
					}
					$response=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'docentesCarga',$data);
					if ($response->getStatusCode()>=300) {
						array_push($errores,'Error agregando al docente con email '.$data['email'].' codigo error: '.$response->getStatusCode().' - '.$response['message']);
					} else {
						$added++;
			    		$user=User::firstOrCreate(['email'=>strtolower($data['email'])]);
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
			    		if ($user->category==null) {$user->category=1;}
			    		$user->save();
					}
				}
			}
			fclose($file);
			if (sizeof($errores)==0) {
				return response()->json(['message'=>'Añadidos o modificados '.$added.' docentes.'],200);
			} else {
				return response()->json($errores,400);
			}
		}
	}

    public function mensaje($emailAdress,$pass) {

        $cabecera='Alta docente PFC';
        $mensaje="Docente dado de alta en el sistema de gestión de PFC del CIFP de Avilés. <br/> <br/> Para acceder al servicio utilice las siguientes credenciales: <br/><br/> Usuari@: ".$emailAdress."<br/> Password: ".$pass."<br/><br/> En su primer acceso debera modificar su password.<br/>";
        $mensaje.="<br/><br/>-----------------------------------------------------------------------<br/><br/> Esta usted recibiendo este mensaje enviado por el  sistema automático de la aplicación PFC del CIFP Avilés <br/> Se ha producido el alta como docente usuario de la aplicación <br/><br/> -----------------------------------------------------------------------";
        
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
                    //return false;    
                }
            } catch (Exception $e) {
               // return false;
            }
        }
    }

}




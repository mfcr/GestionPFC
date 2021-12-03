<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{

    public function custom_login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        $credentials['email']=strtolower($credentials['email']);
        if (Auth::attempt($credentials)) {
        	if (Auth::user()->password_modificada==true) {
        		return response()->json(['Ok'],200); 
        	} else {
				return response()->json(['Modificar'],200); 
        	}
        } 
        return response()->json(['Error'],400);
    }

    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('/');
    }

    public function login() {
    	return view('login');
    }

    public function changePassword(Request $request) {
        $request->validate([
            'password' => 'required',
        ]);
        $user=Auth::user();
        $user->password=bcrypt($request->input('password'));
        $user->password_modificada=true;
        if ($user->save()) {
        	return response()->json(['Ok'],200); 
        } else {
     		return response()->json(['Error'],400);   	
        }
    }
    public function resetcontra(Request $request) {
    	$email=strtolower($request->input('email'));
    	if ($email=='' || $email==null) {
    		return response()->json(['Error'],404);   		
    	}
    	$user=User::where('email',strtolower($email))->first();
    	if ($user==null) {
    		return response()->json(['Error'],404);   		
    	} else {
            //@@@ pdte crear passwords de verdad
            $password='prueba';
            //$password=Hash::make(str_random(8));
            //@@@ pdte crear passwords de verdad

            $user->password=bcrypt($password); 
            $user->password_modificada=false;
            $user->save();
            $this->mensaje($user->email,$password);

            return response()->json(['Ok'],200);   		
    	}
    }

    public function mensaje($emailAdress,$pass) {

        $cabecera='Reset de password';
        $mensaje="Clave de acceso modificada en el sistema de gestión de PFC del CIFP de Avilés. <br/> <br/> Para acceder al servicio utilice las siguientes credenciales: <br/><br/> Usuari@: ".$emailAdress."<br/> Password: ".$pass."<br/><br/> En su primer acceso debera modificar su password.<br/>";
        $mensaje.="<br/><br/>-----------------------------------------------------------------------<br/><br/> Esta usted recibiendo este mensaje enviado por el  sistema automático de la aplicación PFC del CIFP Avilés <br/> Se ha producido la modificación de las claves del usuario de la aplicación <br/><br/> -----------------------------------------------------------------------";

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
                $mail->send();
            } catch (Exception $e) {
                //return false;
            }
        }
    }


    public function foreign_login(Request $request) {

         $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        $credentials['email']=strtolower($credentials['email']);


        if (Auth::attempt($credentials)) {
            return response()->json(['user'=>Auth::user()],200);
        } 
        return response()->json(['Error'],400);
    }

    public function foreign_changePassword(Request $request) {

        $email=strtolower($request->input('email'));
        if ($email=='' || $email==null) {
            return response()->json(['Error'],404);         
        }
        $user=User::where('email',strtolower($email))->first();
        if ($user==null) {
            return response()->json(['Error'],404);         
        } else {
            $password=Hash::make(str_random(8));

            $user->password=bcrypt($request->input('password'));
            $user->password_modificada=true;
            if ($user->save()) {
                return response()->json(['Ok'],200); 
            } else {
                return response()->json(['Error'],400);     
            }
        }
    }





}

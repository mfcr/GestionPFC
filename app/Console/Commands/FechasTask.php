<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;

use Illuminate\Console\Command;

class FechasTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fechas:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $curso=null; $fechas=null; $proy=null;
        $cu=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'parametros'); 
        if ($cu->getStatusCode()<300) {
            $curso=$cu->json()['cursoActual'];
        }

        $fe=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'fechas_envio/'.$curso);
        if ($fe->getStatusCode()<300) {
            $fechas=$fe->json();
        }
        $pr=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->get(config('app.apiurl').'proyectosAdmin/'.$curso);
        if ($pr->getStatusCode()<300) {
            $proy=$pr->json();
        }
        if($fechas!=null && $proy!=null) {
            foreach ($fechas as $f) {
               
                $limite=Carbon::createFromFormat('Y-m-d', $f['fechaLimite'], 'Europe/Madrid')->addDays(-$f['diasParaAviso']);
                $hoy=Carbon::now();

                $mensajes=0;
                if ($hoy->gt($limite)) {
                    $cabecera='Aviso de fecha limite acercandose';
                    foreach ($proy as $pr) {
                        $emailAdress=$pr['alumnos']['email'];
                        $mensaje='Recibe este correo en calidad de alumno del ciclo '.$pr['ciclos']['nombreCiclo'].' <br/> <br/> El día: '.$f['fechaLimite'].' es la fecha máxima para el siguiente evento o concepto: '.$f['nombre'].'<br/> <br/>descripcion: '.$f['descripcion']."<br/><br/>-----------------------------------------------------------------------<br/><br/><br/> Esta usted recibiendo este mensaje enviado por el  sistema automático de la aplicación PFC del CIFP Avilés  <br/><br/> -----------------------------------------------------------------------";
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
                                    $mensajes++;
                                   // return true;   
                                   Storage::append('ficheroemails.txt',$hoy.'--'.$emailAdress.'-----'.$mensaje); //Guardamos un registro de los emnsajes enviados. 
                                } else {
                                    Storage::append('ficheroemails.txt',$hoy.'--'.$emailAdress.'-----ERROR DE ENVIO'); //Guardamos un registro de los emnsajes enviados.
                                    //return false;    
                                }
                            } catch (Exception $e) {
                               // return false;
                            }
                        }
                    }
                    $res=Http::withHeaders(['APP_KEY'=>'PFC_APP_2021'])->post(config('app.apiurl').'fechas/'.$f['id'],['enviado'=>true]);
                } 
            }
        }
        return 0;
    }
}

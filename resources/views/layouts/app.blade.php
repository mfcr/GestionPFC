<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style='font-size:calc(8px + 0.4vw);'>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Título e icono-->
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href="imgs/cropped-favicon-32x32.jpg" sizes="32x32">
    <link rel="icon" href="imgs/cropped-favicon-192x192.jpg" sizes="192x192">
    <link rel="apple-touch-icon" href="imgs/cropped-favicon-180x180.jpg">    
    <meta name="msapplication-TileImage" content="imgs/cropped-favicon-270x270.jpg">
    <!--material Icons from google-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href= "/css/app.css" >

    <!--Bootstrap-JS-Ajax-Jquery-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.min.js" integrity="sha384-PsUw7Xwds7x08Ew3exXhqzbhuEYmA2xnwc8BuD6SEr+UmEHlX8/MCltYEodzWA4u" crossorigin="anonymous"></script>
    <script scr="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js" ></script>
    <script scr="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<!--FUNCIONES QUE SIRVEN PARA CARGAR EL CONTENIDO DE CADA MENU DE LA BARRA DE NAVEGACION -->
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <script type='text/javascript'> 
        function milogin()                          {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/MiLogin");                                         $(document.body).css('cursor','default');} 
        function docs(user,code,ciclo)              {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Docs/"+user+'/'+code+'/'+ciclo);                   $(document.body).css('cursor','default');}
        function propuestas(user,code,ciclo,mode)   {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Propuestas/"+user+'/'+code+'/'+ciclo+'/'+mode);    $(document.body).css('cursor','default');}
        function fechas(curso)                      {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Fechas/"+curso);                                   $(document.body).css('cursor','default');}
        function documentos()                       {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Documentos");                                      $(document.body).css('cursor','default');}
        function tipos_proyectos()                  {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/TiposProyectos");                                  $(document.body).css('cursor','default');}
        function ciclos()                           {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Ciclos");                                          $(document.body).css('cursor','default');}
        function tutores_colectivos(curso)          {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/TutoresColectivos/"+curso);                        $(document.body).css('cursor','default');}
        function modulos()                          {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Modulos");                                         $(document.body).css('cursor','default');}
        function ciclos_modulos()                   {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/CiclosModulos");                                   $(document.body).css('cursor','default');}
        function rubricas(curso)                    {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Rubricas/"+curso);                                 $(document.body).css('cursor','default');}
        function reset(curso)                       {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Reset/"+curso);                                    $(document.body).css('cursor','default');} 
        function cargaAlumnos(curso)                {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/AlumnosCarga/"+curso);                             $(document.body).css('cursor','default');} 
        function gestionAlumnos(curso)              {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/AlumnosGestion/"+curso);                           $(document.body).css('cursor','default');} 
        function cargaDocentes(curso)               {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/DocentesCarga/"+curso);                            $(document.body).css('cursor','default');} 
        function gestionDocentes(curso)             {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/DocentesGestion/"+curso);                          $(document.body).css('cursor','default');} 
        function verProyectos(id,mode,code)         {$(document.body).css('cursor','wait');  $("#mainContentDiv").load("/Proyectos/"+id+'/'+mode+'/'+code);                 $(document.body).css('cursor','default');} 
        $(document).ready(function() {
            $("[id^='resetcontra']").click(function() {
              $(document.body).css('cursor','wait');  
              var email=prompt("Introduzca el email del usuari@ a resetear:");
              console.log('email introducido '+email);
              console.log('lienavacia');
              $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
              $.ajax({
                  type: 'POST',
                  url: '/resetcontra',
                  data: {'email':email},
                  crossDomain: true,
                  success: function (result,txt,code) { 
                    console.log('OK resultadod e llamada a fn '+result.responseText);
                    console.log('lienavacia');
                    alert('Contraseña reseteada, el/la usuario/a recibirá un email para futuros accesos.');
                  },
                  error: function (result,txt,code) {
                    console.log('responsetext de envio :'+result.responseText);
                    console.log(result.responsetext=="['Error']");
                    console.log(result.responsetext==="['Error']");
                    console.log('lienavacia');
                    if (result.responseText.search('Error')>0) {
                        alert('Error al resetar la contraseña.');
                    }  else {
                        alert('Contraseña reseteada, el/la usuario/a recibirá un email para futuros accesos.');    
                    }
                  },
              });
              $(document.body).css({'cursor' : 'default'});
            });
        });
        
    </script> 


<!--FUNCIONES QUE SIRVEN PARA CARGAR EL CONTENIDO DE CADA MENU DE LA BARRA DE NAVEGACION -->

</head>

<body >
    <div id="app">
        <div> <!-- class='sticky-top'> -->

        <header style="background-color: black; ">
          <!-- Logo and Login/out -->
            <div class='row'>
                <div style='width: 30%; vertical-align: center;'>
                    <a  class="nav-link" href="{{ url('/') }}">
                            <img style='max-width: 100%; max-height: 100%;  min-width: 150px; vertical-align: center;' src="{{asset('/imgs/logo.jpg')}}" alt='Inicio'>
                    </a>
                </div>
                <div style='width: 20%; vertical-align: center;'>
                    <p  style='color:grey; font-size:1.5em; text-align: center; padding-top:1em; '><i class="material-icons " style="color:white; padding-right :1em;">today</i> {{date('d-m-Y')}}</p>
                </div>

                <!-- Authentication Links. User data are collected in controller and sent here. -->
                @if ($user==0)
                    <div style='width: 40%; '>
                        <h2  style='color:white; font-size:3em; text-align: center; padding-top:0.5em; '>Bienvenido.</h2>
                    </div>
                    <div style='width: 10%; display: flex; justify-content: left; padding-right: 1em; padding-top: 0.5em; '>
                        <a  href="javascript:milogin()" >
                            <img   style='width: 45%; min-width: 30px; border-radius: 10%; border: 5px solid grey;'  src="{{asset('/imgs/notlogged.png')}}" alt='pulse para identificarse' title='Acceder'>
                        </a>
                    </div>
                @else
                    @if ($user==2)
                        <div style='width: 40%; '>
                            @if (($al['nombre']!=null && $al['nombre']!="") || ($al['apellido1']!=null && $al['apellido1']!=""))
                                <h2  style='color:white; font-size:1.5em; text-align: center; padding-top:1em; '>{{'Usuario: '.$al['nombre'].' '.$al['apellido1']}}</h2>
                            @else
                                <h2  style='color:white; font-size:1.5em; text-align: center; padding-top:1em; '>{{'Usuario: Nombre pendiente'}}</h2>
                            @endif
                        </div>
                        <div style='width: 10%; display: flex; justify-content: right; padding-right: 1em; padding-top: 0.5em; '>
                            <a   href="{{ route('logout') }}"  onclick="event.preventDefault();  document.getElementById('logout-form').submit();"  style="text-decoration: none;">
                                <img style='width: 45%; min-width: 30px; border-radius: 10%; border: 5px solid grey;'  src="{{asset('/imgs/student.png')}}" alt='pulse para salir' title='Salir'>
                                <img style='width: 45%; min-width: 30px; border-radius: 10%; border: 5px solid grey;'  src="{{asset('/imgs/logout.png')}}" alt='pulse para salir' title='Salir'>
                            </a>
                        </div>
                    @else
                        <div style='width: 40%; '>
                            @if (($do['nombre']!=null && $do['nombre']!="") || ($do['apellido1']!=null && $do['apellido1']!=""))
                                <h2  style='color:white; font-size:1.5em; text-align: center; padding-top:1em; '>{{'Usuario: '.$do['nombre'].' '.$do['apellido1']}}</h2>
                            @else
                                <h2  style='color:white; font-size:1.5em; text-align: center; padding-top:1em; '>{{'Usuario: Nombre pendiente'}}</h2>
                            @endif
                        </div>
                        <div style='width: 10%; display: flex; justify-content: right; padding-right: 1em; padding-top: 0.5em; '>
                            <a   href="{{ route('logout') }}"  onclick="event.preventDefault();  document.getElementById('logout-form').submit();"  style="text-decoration: none;">
                                <img  style='width: 45%; min-width: 30px; border-radius: 10%; border: 5px solid grey;' src="{{asset('/imgs/teacher.png')}}" alt='pulse para salir' title='Salir'>
                                <img  style='width: 45%; min-width: 30px; border-radius: 10%; border: 5px solid grey;' src="{{asset('/imgs/logout.png')}}" alt='pulse para salir' title='Salir'>
                            </a>
                        </div>
                    @endif

                    <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                        @csrf
                    </form>
                @endguest
            </div>
        </header>
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg  navbar-light bg-white shadow-sm"  > 
        <div class="container-fluid collapse   navbar-collapse  " >
            <ul class="navbar-nav me-auto pb-2 pb-lg-0 ">

<!--MENU DE ALUMNO -->              
                @if ($user==2)
                  <li>
                    <div class="dropdown" style='font-size:calc(12px + 0.2vw);'>
                      <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control"  type="button" id="dropdownMenuButton" style='font-size:calc(12px + 0.2vw); border:2px solid black;' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mis Proyectos
                      </button>
                      @if ($proyAl!=null)
                          <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                            @foreach ($proyAl as $pr)
                                <button class="dropdown-item" onclick='verProyectos({!!$pr["id"]!!},"al",{!!$code!!});' type='button'>
                                <?php echo($pr['estados']['estado'].' -- ');  echo('<strong>'.(isset($pr['nombreProyecto'])?$pr['nombreProyecto']:'Proyecto Pendiente Inicio').'</strong> - ');   ?>
                                {{$pr['curso'].' - '.$pr['ciclos']['nombreCiclo'].' ('.$pr['estados']['estado'].')'}}
                                </button>
                            @endforeach
                          </div>
                      @else
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><strong>Aun no tiene proyectos activos</strong></div>
                      @endif
                    </div>
                  </li>

<!--MENUS DE DOCENTE-->
                @elseif ($user==1)
    
    <!--MENU DE ADMIN-->
                    @if ($do!=null && $do['isAdmin']==true)
                      <li>
                        <div class="dropdown" >
                          <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Administrador
                          </button>
                          <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                            <button class="dropdown-item" onclick='cargaAlumnos({!!$curso!!});' type='button'>Carga de Alumn@s.</button>
                            <button class="dropdown-item" onclick='gestionAlumnos({!!$curso!!});' type='button'>Gestion de Alumn@s.</button>
                            <button class="dropdown-item" onclick="cargaDocentes({!!$curso!!});" type='button'>Carga de Docentes.</button>
                            <button class="dropdown-item" onclick="gestionDocentes({!!$curso!!});" type='button'>Gestion de Docentes.</button>
                            <button class="dropdown-item" onclick="tutores_colectivos({!!$curso!!});" type='button'>Gestión de Tutores Colectivos</button>
                            <button class="dropdown-item" onclick="ciclos();" type='button'>Gestión de Ciclos</button>
                            <button class="dropdown-item" onclick="modulos();" type='button'>Gestión de Módulos</button>
                            <button class="dropdown-item" onclick="ciclos_modulos();" type='button'>Gestión de Ciclos y Módulos</button>
                            <button class="dropdown-item" onclick="tipos_proyectos();" type='button'>Gestión de Tipos de Proyectos</button>
                            <button class="dropdown-item" onclick="documentos();" type='button'>Gestión de Documentos.</button>
                            <button class="dropdown-item" onclick="rubricas({!!$curso!!});" type='button'>Gestión de Rúbricas.</button>
                            <button class="dropdown-item" onclick="fechas({!!$curso!!});" type='button'>Gestión de Fechas.</button>
                            <button class="dropdown-item" onclick="propuestas({!!$user!!},{!!$code!!},0,'see');" type='button'>Gestión de Propuestas de Proyectos</button> 
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item "  type='button' id='resetcontra'>Reset contraseña.</button> 
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item " onclick="reset({!!$curso!!});" type='button'><strong>Reset de curso.</strong></button> 
                          </div>
                        </div>
                      </li>

                      <li>
                        <div class="dropdown" >
                          <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Proyectos
                          </button>
                          <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                            @if($adminProy!=null)
                            @foreach ($adminProy as $pr)
                                <button class="dropdown-item" onclick='verProyectos({!!$pr["id"]!!},"admin",{!!$code!!});' type='button'>
                                    <?php echo($pr['ciclos']['codigoCiclo'].' - ');echo('   ('.$pr['estados']['estado'].') - '); 
                                        echo(isset($pr['nombreProyecto'])?$pr['nombreProyecto'].' - ':'Nombre Proyecto Pendiente - '); 
                                        echo(isset($pr['alumnos']['nombre'])?$pr['alumnos']['nombre'].' '.$pr['alumnos']['apellido1']:'Pendiente nombre alumno'); ?>
                                </button>
                            @endforeach
                            @else
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"><strong>Este curso aun no tiene proyectos.</strong></div>
                            @endif
                          </div>
                        </div>


                      </li>
                    @endif
    <!--MENU DE DOCENTE-->
                    @if ($do!=null && $do['docente_imparte_modulos']!=null && sizeof($do['docente_imparte_modulos'])>0)
                       @php $mostrar=true; @endphp
                       @if ($mostrar)
                          <li>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Docente
                              </button>
                              <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                                @foreach ($do['docente_imparte_modulos'] as $d)
                                        <p class='dropdown-header'>Proyectos de módulo: {{$d['ciclos']['codigoCiclo'].' - '.$d['modulos']['nombreModulo']}}</p>
                                        @if($adminProy!=null)
                                        @foreach ($adminProy as $pr)
                                            @if ($pr['ciclo_id']==$d['ciclos']['id'])
                                                <button class="dropdown-item" onclick='verProyectos({!!$pr["id"]!!},"doc",{!!$code!!});' type='button' >
                                                <?php echo($pr['ciclos']['codigoCiclo'].' - ');echo('   ('.$pr['estados']['estado'].') - '); 
                                                    echo(isset($pr['nombreProyecto'])?$pr['nombreProyecto'].' - ':'Nombre Proyecto Pendiente - '); 
                                                    echo(isset($pr['alumnos']['nombre'])?$pr['alumnos']['nombre'].' '.$pr['alumnos']['apellido1']:'Pendiente nombre alumno'); ?>
                                                </button>
                                            @endif
                                        @endforeach
                                        @endif
                                        <div class="dropdown-divider"></div>
                                @endforeach
                              </div>
                            </div>
                          </li>
                        @endif
                    @endif

    <!--MENU DE TUTOR INDIVIDUAL-->
                    @if (!is_null($tut_indiv))
                      <li>
                        <div class="dropdown">
                          <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tutor Individual 
                          </button>
                          <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                            @foreach ($tut_indiv as $pr)
                                <button class="dropdown-item" onclick='verProyectos({!!$pr["id"]!!},"ind",{!!$code!!});' type='button'>
                                <?php echo($pr['ciclos']['codigoCiclo'].' - ');echo('   ('.$pr['estados']['estado'].') - '); 
                                    echo(isset($pr['nombreProyecto'])?$pr['nombreProyecto'].' - ':'Nombre Proyecto Pendiente - ');   
                                    echo(isset($pr['alumnos']['nombre'])?$pr['alumnos']['nombre'].' '.$pr['alumnos']['apellido1']:'Pendiente nombre alumno'); ?>
                                </button>
                            @endforeach

                          </div>
                        </div>
                      </li>
                    @endif

    <!--MENU DE TUTOR COLECTIVO-->
                    @if (!is_null($tut_col))
                      <li>
                        <div class="dropdown">
                          <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tutor Colectivo 
                          </button>
                          <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                            @foreach ($tut_col as $t)
                                <p class='dropdown-header'>{{'Ciclo: '.$t['ciclos']['nombreCiclo']}}</p>
                                @if ($t['ciclos']['proyectos']!=null && sizeof($t['ciclos']['proyectos'])>0)
                                    @foreach($t['ciclos']['proyectos'] as $pr)
                                        <button class="dropdown-item" onclick='verProyectos({!!$pr["id"]!!},"col",{!!$code!!});' type='button'>
                                        <?php echo($pr['estados']['estado'].' -- '); 
                                            echo(isset($pr['nombreProyecto'])?$pr['nombreProyecto'].' - ':'Nombre Proyecto Pendiente - ');   
                                            echo(isset($pr['alumnos']['nombre'])?$pr['alumnos']['nombre'].' '.$pr['alumnos']['apellido1']:'Pendiente nombre alumno'); ?>
                                        </button>
                                    @endforeach
                                @else
                                    <div class='dropdown-item'>Aún no hay proyectos de este ciclo este curso</div>
                                @endif
                                <div class="dropdown-divider"></div>
                            @endforeach
                          </div>
                        </div>
                      </li>
                    @endif
                @endif

              <li class="nav-item active">
<!--MENU DOCUMENTOS-->
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Documentos
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" onclick='docs({!!$user!!},{!!$code!!},0)' type='button'> Documentos Generales</button>
                    <div class="dropdown-divider"></div>
                    <p class='dropdown-header'>Documentos de ciclos</p>
                    @if ($ciclos!=null)
                    @foreach ($ciclos as $ciclo)
                        <button class="dropdown-item" onclick="docs({!!$user!!},{!!$code!!},{!!$ciclo['id']!!})" type='button'> {{$ciclo['nombreCiclo']}}</button>
                    @endforeach
                    @else
                        <button class="dropdown-item" onclick="return false;" type='button'> No hay ciclos cargados.</button>
                    @endif
                  </div>
                </div>
              </li>
<!--MENU PROYECTOS ANTERIORES-->              
              <li>
                <div class="dropdown">
                  <button  class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Proyectos anteriores
                  </button>
                  <div class="dropdown-menu" style='max-height: 60vh;  overflow-y: auto;' aria-labelledby="dropdownMenuButton">
                    @if ($proyPub==null)
                         <button class="dropdown-item" onclick="return false;" type='button'> Aún no hay proyectos publicados.</button>
                    @else
                        @if ($ciclos!=null)
                            @foreach ($ciclos as $ciclo)
                                <p class='dropdown-header'>{{'Ciclo: '.$ciclo['nombreCiclo']}}</p>
                                @php $hay=false @endphp
                                @foreach ($proyPub as $pr)
                                    @if ($pr['ciclo_id']==$ciclo['id'])
                                        <button class="dropdown-item" onclick='verProyectos({!!$pr["id"]!!},"public",0);' type='button'>
                                        <?php echo(isset($pr['nombreProyecto'])?$pr['nombreProyecto'].' - ':'Nombre Proyecto Pendiente - ');   
                                            echo(isset($pr['alumnos']['nombre'])?$pr['alumnos']['nombre'].' '.$pr['alumnos']['apellido1']:'Pendiente nombre alumno'); 
                                            echo (' -- '.$pr['curso'])?>
                                        </button>
                                        @php $hay=true; @endphp
                                    @endif
                                @endforeach
                                @if (!$hay)
                                     <button class="dropdown-item" onclick="return false;" type='button'> No hay proyectos de ese ciclo.</button>
                                @endif
                            @endforeach
                        @else
                            <button class="dropdown-item" onclick="return false;" type='button'> No hay ciclos cargados.</button>
                        @endif
                    @endif
                  </div>
                </div>
              </li>
<!--MENU PROPUESTAS DE PROYECTOS-->
              <li>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sugerir un proyecto
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <p class='dropdown-header'>Sugerir para el ciclo</p>
                    @if ($ciclos!=null)
                    @foreach ($ciclos as $ciclo)
                        <button class="dropdown-item" onclick="propuestas({!!$user!!},{!!$code!!},{!!$ciclo['id']!!},'add');" type='button'> {{$ciclo['nombreCiclo']}}</button>
                    @endforeach
                    @else
                        <button class="dropdown-item" onclick="return false;" type='button'> No hay ciclos cargados.</button>
                    @endif
                  </div>
                </div>
              </li>
<!--RECARGAR-->
            @if ($user==1 && $do!=null && $do['isAdmin']==true)
              <li>
                <div class="dropdown">
                    <a href="/" class="btn btn-secondary btn-lg btn-light mx-1 form-control" style='font-size:calc(12px + 0.2vw); border:2px solid black;' role="button" title='Pulse para actualizar los cambios en los menús'><i class="material-icons" >refresh</i></a>
                </div>
              </li>
            </ul>
            @endif
        </div>

      </nav>
      <!-- Navbar -->
  </div>

        <!--Main Section-->
        <main id='mainContentDiv' name='mainContentDiv' class="py-4">
            @yield('content')
        </main>


        <!--Footer-->

    </div>
</body>
</html>

@section('content')
<script> 
  var mensajeBorrar=''; //Por ahora no se crea mensaje antes de borrar.

  $(document).ready(function() {

    $("[id^='btn_edit-']").click(function() {
      var currentId=this.id.split('-')[1]; //Obtenemos el ID.
      $("[id$=--"+currentId+"]").prop('disabled',false); //Activamos los campos para edición.
      $('#btn_submit-'+currentId).prop('hidden',false);
      $('#btn_edit-'+currentId).prop('hidden',true);
    });

    $("#btn_add").click(function () { //muestra - oculta el formulario de añadir
        
        if ($(this).hasClass("adding")){
            $("#add_body").show('slow');
            $(this).html('<div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">remove_circle_outline</i> </h3>  </div> ');
           $(this).toggleClass("adding notadding");
        } else {
           $("#add_body").hide('slow');
           $(this).html('<div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i> </h3>  </div> ');
           $(this).toggleClass("notadding adding");   
        }
        return false;
    });

    $("[id^='btn_clear-']").click(function() {
      var currentId=$(this).attr('id').split('-')[1]; //Obtenemos el ID.ç
      var deleteUrl=$(this).data('url');
      var metodo='DELETE';
      if (typeof mensajeBorrar=='undefined' || mensajeBorrar=='' || confirm(mensajeBorrar)) {
        $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
        $.ajax({
            type: metodo,
            url: deleteUrl,
            crossDomain: true,
            success: function (result,txt,code) { 
              $('#headerSaved'+currentId).show("slow");
              $('#full-'+currentId).attr('id','deleted-'+currentId);
              $('#deleted-'+currentId).delay(2000).hide('slow');
            },
            error: function (result,txt,code) { 
              $('#headerError'+currentId).show("slow").delay(4000).hide("slow");
            },
        });
      }
    });

    $("[id^='form_id-']").bind('submit', function (e) {
          var currentId=$(this).attr('name').split('-')[1]; //Obtenemos el ID.
          var thisUrl=$(this).attr('action');
          var metodo=$(this).attr('method');
          e.preventDefault();
          $.ajaxSetup({    headers: {   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') , 'APP_KEY':'PFC_APP_2021'   }});
          $.ajax({
              type: metodo,
              url: thisUrl,
              data: $(this).serialize(),
              crossDomain: true,
              success: function (result,txt,code) { 
console.log(result);
console.log(txt);
console.log(code);

                $('#headerSaved'+currentId).show("slow").delay(2000).hide("slow");
                if (currentId>0) {
                  $("[id$=--"+currentId+"]").prop('disabled',true); //Activamos los campos para edición.
                  $('#btn_submit-'+currentId).prop('hidden',true);
                  $('#btn_edit-'+currentId).prop('hidden',false);
                  $('#form_filter').trigger('submit'); //Actualizamos el filtro.
                }  else {
                  $("#btn_add").trigger('click');
                  new Promise(r => setTimeout(r, 1000));
                  llamadaInicial();
                }
              },
              error: function (result,txt,code) { 
console.log(result);
console.log(txt);
console.log(code);
                $('#headerError'+currentId).show("slow").delay(4000).hide("slow");
              },
          });
          return false;
    }); 


    $("#form_filter").bind('submit', function (e) { //los filtros son propios de cada pantalla. hay que hacer una funcion par cada una
          e.preventDefault();
          var opcion=$("input[name='filtroActivo']:checked").val();
          var formularios=$("*[id^='form_id-']");
          formularios.each( function() {
            var currentId=$(this).attr('id').split('-')[1]; 
            var thisValue=$("#form_id-"+currentId+" input[name='activo']:checked").val(); 
            $("#form2 input[name=name]").val('Hello World!');
            if(currentId>0) {
              if(opcion==-1 || opcion==thisValue) { 
                $('#full-'+currentId).show(1000);
              } else {
                $('#full-'+currentId).hide(1000);
              }
            }
          });
          return false;
    }); 

  function llamadaInicial() {
    gestionDocentes({!!$curso!!});
  }

})
</script>
<div class="container">
    <h1 style='text-align: center;' >Gestión de Docentes.</h1>
<!--Filtro-->
    <div class="card bg-secondary sp-2" style='border-width: 5px'>
        <div class="card-body bg-light " >
          <form id="form_filter" name="form_filter" > 
            <div class="form-group form-inline justify-content-center ">
              <input class="form-check-input ml-2" type="radio" name="filtroActivo" id="filtroActivo1" value="1" checked>
              <label class="form-check-label" for="filtrotipodoc1">Activos</label>
              <input class="form-check-input ml-2" type="radio" name="filtroActivo" id="filtroActivo2" value="0">
              <label class="form-check-label" for="filtroActivo2">Inactivos</label>
              <input class="form-check-input ml-2" type="radio" name="filtroActivo" id="filtroActivo3" value="-1" >
              <label class="form-check-label" for="filtroActivo3">Todos</label>
              <button type="submit" class="btn text-center ml-5"  style="border:4px solid black;" >
                <i class="material-icons " title='Filtrar resultados' style="font-size:2em; color:black;  align-content: center">filter_alt</i></button>         
            </div>
          </form>
        </div>
    </div>



<!--Formulario añadir-->
    <div class="row justify-content-center">
        <div class="col-sm-12 mt-2">
            <div class="card bg-secondary sp-2" style='border-width: 5px'>
                <button id='btn_add'  class='adding' form='form_id-0'><div class="card-header bg-secondary justify-content-center"> <h3><i class="material-icons mr-3" style="font-size:40px;color:white">add_circle_outline</i>   </div> </button>
                
                <div class="card-header bg-success text-center" id="headerSaved0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Documento añadido.</h3>   </div>
                <div class="card-header bg-danger text-center" id="headerError0" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                <div name='add_body' id='add_body' class="card-body bg-light" style="display:none;">
                    <form id='form_id-0' name='form_id-0' enctype='multipart/form-data' method='POST' action='/DocentesCargaIndividual'> 
                      @csrf
                      <input type="hidden" class="form-control " name='activo' id="activo" value=1 form='form_id-0' maxlength="15"> 
                      <div class='form-row'>
                        <label for="nombre" class='col-sm-4'>Nombre:</label>
                        <label for="apellido1" class='col-sm-4'>Primer apellido:</label>
                        <label for="apellido2" class='col-sm-4'>Segundo apellido:</label>
                      </div>
                      <div class='form-row'>
                        <input type="input" class="form-control col-sm-4" name='nombre' id="nombre" aria-describedby="nombre" placeholder="Nombre" form='form_id-0' maxlength="30" >  
                        <input type="input" class="form-control col-sm-4" name='apellido1' id="apellido1" aria-describedby="apellido1" placeholder="primer apellido" form='form_id-0' maxlength="30" >  
                        <input type="input" class="form-control col-sm-4" name='apellido2' id="apellido2" aria-describedby="apellido2" placeholder="segundo apellido" form='form_id-0' maxlength="30" >  
                      </div>
                      <div class='form-row mt-2'>
                        <label for="email" class='col-sm-4'>Email:</label>
                        <label for="dni" class='col-sm-2'>NIF:</label>
                        <label for="telefono" class='col-sm-2'>Teléfono:</label>
                        <label class='col-sm-2 tetx-center'>¿Es Administrador?</label>
                        <label for='curso' class='col-sm-2'>Curso:</label>
                      </div>
                      <div class='form-row'>
                        <input type="email" class="form-control col-sm-4" name='email' id="email" aria-describedby="email" placeholder="dirección de correo electrónico" form='form_id-0' maxlength="50" required>  
                        <input type="input" class="form-control col-sm-2" name='dni' id="dni" aria-describedby="dni" placeholder="NIF (8 números y letra sin guiones ni espacios)" form='form_id-0' maxlength="9" >  
                        <input type="input" class="form-control col-sm-2" name='telefono' id="telefono" aria-describedby="telefono" placeholder="Numero de telefono" form='form_id-0' maxlength="15">  
                        <div class="form-group form-inline justify-content-center col-sm-2">
                           <input class="form-check-input ml-3" type="radio" name="isAdmin" id="isAdmin1" value="1" form='form_id-0' >
                           <label class="form-check-label " for="isAdmin1">Si</label>
                           <input class="form-check-input ml-3" type="radio" name="isAdmin" id="isAdmin2" value="0" form='form_id-0' checked>
                           <label class="form-check-label " for="isAdmin2">No</label>
                        </div>
                        <input type="number" id="curso" name="curso" onKeyDown="return false"   class='col-sm-1 text-center'  min="<?php echo($curso);?>"  value="<?php echo($curso);?>" form='form_id-0'>
                      </div>
                      <hr/>
                      <label >Módulos en los que imparte docencia este curso:</label>
                      <div class="form-row ">
                        @for ($i = 0; $i <= 11; $i++)
                          <select  id="ciclomodulo_id-docencia-<?php echo($i);?>" name='ciclomodulo_id-docencia-<?php echo($i);?>' class='form-control col-sm-6'  form='form_id-0' >
                            <option value='0' selected></option>
                            @if($ciclos_modulos!=null)
                            @foreach ($ciclos_modulos as  $ci)
                              <option value="{{$ci['id']}}" >{{$ci['ciclos']['codigoCiclo'].' - '.$ci['ciclos']['nombreCiclo'].' => '.$ci['modulos']['codigoModulo'].' - '.$ci['modulos']['nombreModulo'] }}</option>
                            @endforeach
                            @endif
                          </select>
                        @endfor
                      </div>
                      <hr/>
                      <label >Ciclos en los que es tutor colectivo curso:</label>
                      <div class="form-row ">
                        @for ($i = 0; $i <= 3; $i++)
                          <select  id="ciclo_id-tutor-<?php echo($i);?>" name='ciclo_id-tutor-<?php echo($i);?>' class='form-control col-sm-6' form='form_id-0' >
                            <option value='0' selected></option>
                            @if($ciclos!=null)
                            @foreach ($ciclos as  $ci)
                              <option value="{{$ci['id']}}" >{{$ci['codigoCiclo'].' - '.$ci['nombreCiclo']}}</option>
                            @endforeach
                            @endif
                          </select>
                        @endfor
                      </div>

                      <hr/>
                      <div class="form-group text-center">
                        <button type="submit" class="btn btn-dark text-center" form='form_id-0'><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>         
                      </div>

                    </form>
                  <hr/>
                      Notas: <br/> 
                        El dato mínimo para dar de alta un docente es el del email. <br/>
                        Si introduce un  email existente se actualizarán y/o añadirán los datos actuales. 

                </div>


            </div>
        </div>
    </div>

    @if($docentes!=null)
    @foreach ($docentes as $it)
    @if ($it['id']!=1) <!--Evitamos mostrar el administrador-->
        <div class="row justify-content-center mt-2"  id="{{'full-'.$it['id']}}"  @if ($it['activo']==false) style="display:none;" @endif >
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-header bg-success text-center" id="{{'headerSaved'.$it['id']}}"  style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_up</i>Cambios realizados.</h3>   </div>                    
                    <div class="card-header bg-danger text-center" id="{{'headerError'.$it['id']}}" style="display:none;"> <h3><i class="material-icons mr-3" style="font-size:35px;color:white">thumb_down</i>Ha habido un error, intentelo de nuevo.</h3>   </div>

                    <div class="card-body bg-light">
                      <form id="{{'form_id-'.$it['id']}}" name="{{'form_id-'.$it['id']}}" method='POST' action="<?php echo($apiUrl); ?>docentes/<?php echo($it['id']); ?>"> 
                          @csrf
                        <div class='form-row'>
                          <label for="nombre--<?php echo ($it['id']); ?>" class='col-sm-4'>Nombre:</label>
                          <label for="apellido1--<?php echo ($it['id']); ?>" class='col-sm-4'>Primer apellido:</label>
                          <label for="apellido2--<?php echo ($it['id']); ?>" class='col-sm-4'>Segundo apellido:</label>
                        </div>
                        <div class='form-row'>
                          <input type="input" class="form-control col-sm-4" name='nombre' id="nombre--<?php echo ($it['id']); ?>" aria-describedby="nombre" placeholder="Nombre" form="{{'form_id-'.$it['id']}}" maxlength="30" value="<?php echo ($it['nombre']); ?>" disabled>  
                          <input type="input" class="form-control col-sm-4" name='apellido1' id="apellido1--<?php echo ($it['id']); ?>" aria-describedby="apellido1" placeholder="primer apellido" form="{{'form_id-'.$it['id']}}" maxlength="30" value="<?php echo ($it['apellido1']); ?>" disabled>  
                          <input type="input" class="form-control col-sm-4" name='apellido2' id="apellido2--<?php echo ($it['id']); ?>" aria-describedby="apellido2" placeholder="segundo apellido" form="{{'form_id-'.$it['id']}}" maxlength="30" value="<?php echo ($it['apellido2']); ?>" disabled>  
                        </div>
                        <div class='form-row mt-2'>
                          <label for="email<?php echo ($it['id']); ?>" class='col-sm-4'>Email:</label>
                          <label for="dni--<?php echo ($it['id']); ?>" class='col-sm-2'>NIF:</label>
                          <label for="telefono--<?php echo ($it['id']); ?>" class='col-sm-2'>Telefono:</label>
                          <label class='col-sm-2 text-center'>¿Es Administrador?</label>
                          <label class='col-sm-2 text-center'>¿Activo?</label>
                        </div>
                        <div class='form-row'>
                          <input type="email" class="form-control col-sm-4" name='email' id="email<?php echo ($it['id']); ?>" aria-describedby="email" placeholder="dirección de correo electrónico" form="{{'form_id-'.$it['id']}}" maxlength="50" value="<?php echo ($it['email']); ?>" disabled required>  
                          <input type="input" class="form-control col-sm-2" name='dni' id="dni--<?php echo ($it['id']); ?>" aria-describedby="dni" placeholder="NIF (8 números y letra sin guiones ni espacios)" form="{{'form_id-'.$it['id']}}" maxlength="9" value="<?php echo ($it['dni']); ?>" disabled >  
                          <input type="input" class="form-control col-sm-2" name='telefono' id="telefono--<?php echo ($it['id']); ?>" aria-describedby="telefono" placeholder="Numero de telefono" form="{{'form_id-'.$it['id']}}" maxlength="15" value="<?php echo ($it['telefono']); ?>" disabled>
                          <div class="form-group form-inline justify-content-center col-sm-2">
                            <input class="form-check-input ml-3" type="radio" name="isAdmin" id="isAdmin1--<?php echo ($it['id']); ?>" value="1" form="{{'form_id-'.$it['id']}}" disabled <?php echo($it['isAdmin']? 'checked' : '') ?>>
                            <label class="form-check-label " for="isAdmin1--<?php echo ($it['id']); ?>">Si</label>
                            <input class="form-check-input ml-3" type="radio" name="isAdmin" id="isAdmin2--<?php echo ($it['id']); ?>" value="0" form="{{'form_id-'.$it['id']}}" disabled <?php echo(!$it['isAdmin']? 'checked' : '') ?>>
                            <label class="form-check-label " for="isAdmin2--<?php echo ($it['id']); ?>">No</label>
                          </div>
                          <div class="form-group form-inline justify-content-center col-sm-2">
                            <input class="form-check-input ml-3" type="radio" name="activo" id="activo1--<?php echo ($it['id']); ?>" value="1" form="{{'form_id-'.$it['id']}}" disabled <?php echo($it['activo']? 'checked' : '') ?>>
                            <label class="form-check-label " for="activo1--<?php echo ($it['id']); ?>">Si</label>
                            <input class="form-check-input ml-3" type="radio" name="activo" id="activo2--<?php echo ($it['id']); ?>" value="0" form="{{'form_id-'.$it['id']}}" disabled <?php echo(!$it['activo']? 'checked' : '') ?>>
                            <label class="form-check-label mr-5" for="activo2--<?php echo ($it['id']); ?>">No</label>
                          </div>
                        </div>
                        <hr/>
                        @php $borrar=true @endphp
                        @if (sizeof($it['docente_imparte_modulos'])>0)
                        	@php $borrar=false; @endphp
                            <h3 class='text-center'>Módulos en los que imparte docencia este curso:</h3>
	                        <hr/>
	                        <div class="form-group ">
	                          @foreach ($it['docente_imparte_modulos'] as $dim)
	                            @if ($dim['curso']==$curso)
		                            @foreach($ciclos_modulos as $ci)
		                            	@if ($ci['id']==$dim['ciclo_modulo_id'])
		                            		<p>{{'Ciclo: '.$ci['ciclos']['codigoCiclo'].' - '.$ci['ciclos']['nombreCiclo'].' => Modulo: '.$ci['modulos']['codigoModulo'].' - '.$ci['modulos']['nombreModulo'] }}</p>
		                            		<hr/>
		                            	@endif
		                            @endforeach
		                        @endif
	                          @endforeach 
	                        </div>
                        @else
                          <h3 class='text-center'>No imparte Módulos este curso.</h3>
                          <hr/>
                        @endif

                        @if (sizeof($it['docente_tut_colectivo_ciclos'])>0)
	                    	@php $borrar=false; @endphp
                            <h3 class='text-center'>Ciclos en los que es tutor colectivo este curso:</h3>
   	                        <hr/>
	                        <div class="form-group ">
	                         @foreach ($it['docente_tut_colectivo_ciclos'] as $dim)
	                            @if ($dim['curso']==$curso)
		                            @foreach($ciclos as $ci)
		                            	@if ($ci['id']==$dim['ciclo_id'])
		                            		<p>{{'Ciclo: '.$ci['codigoCiclo'].' - '.$ci['nombreCiclo'] }}</p> 
		                            		<hr/>
		                            	@endif
		                            @endforeach
		                        @endif
	                         @endforeach 
	                        </div>
                        @else
                          <h3 class='text-center'>No es tutor colectivo este curso.</h3>
                          <hr/>
                        @endif

                        <div class="form-group text-center">
                          <button type="button" id="btn_edit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">edit</i>Editar.</button>
                          <button type="submit" id="btn_submit-<?php echo ($it['id']); ?>" form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" hidden><i class="material-icons mr-3" style="font-size:1em;color:white">send</i>Enviar los datos.</button>   
                          @if ($borrar)
                          <button type="button" id="btn_clear-<?php echo ($it['id']); ?>" data-url="<?php echo($apiUrl.'docentes/'.$it['id']) ?>"; form="{{'form_id-'.$it['id']}}" class="btn btn-dark mr-3 ml-3" ><i class="material-icons mr-3" style="font-size:1em;color:white">clear</i>Eliminar.</button>
                          @endif
                        </div>
                    </form>
                 </div>
              </div>
          </div>
      </div>
    @endif
    @endforeach
    @else
        <div class="row justify-content-center">
            <div class="col-sm-12 mt-2">
                <div class="card bg-secondary sp-2" style='border-width: 5px'>
                    <div class="card-body bg-light">
                        <h2 style='text-align: center;'>No hay Alumnos guardados. </br></h2>
                    </div>
                </div>
            </div>
        </div>
    @endif    




@section('content')

